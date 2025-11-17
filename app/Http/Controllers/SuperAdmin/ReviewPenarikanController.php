<?php
// app/Http/Controllers/SuperAdmin/ReviewPenarikanController.php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PenarikanDana;
use App\Models\Mitra;
use App\Models\Admin;
use App\Models\RekeningBank;
use App\Models\LogKeuangan;
use Illuminate\Support\Facades\DB;

class ReviewPenarikanController extends Controller
{
    /**
     * Menampilkan daftar semua penarikan dana dari Mitra.
     */
    public function index()
    {
        $penarikanMitra = PenarikanDana::where('penarikanable_type', Mitra::class)
                                      ->with('penarikanable', 'rekeningBank')
                                      ->orderBy('created_at', 'desc')
                                      ->get();

        return view('superadmin.review-penarikan.index', compact('penarikanMitra'));
    }

    /**
     * Mengupdate status penarikan (Setuju / Tolak).
     */
    public function update(Request $request, PenarikanDana $penarikan)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan_admin' => 'nullable|string|max:255',
        ]);

        if ($penarikan->penarikanable_type != Mitra::class || $penarikan->status != 'Pending') {
             // === PERBAIKAN 1 === (Redirect jika validasi gagal)
             return redirect()->route('admin.pemasukan.index')
                             ->with('error', 'Aksi tidak valid atau sudah diproses.');
        }

        $action = $request->input('action');
        $catatan = $request->input('catatan_admin');

        try {
            DB::transaction(function () use ($penarikan, $action, $catatan) {

                if ($action == 'approve') {

                    $superAdmin = Admin::lockForUpdate()->where('role', 'SuperAdmin')->firstOrFail();
                    $rekening = RekeningBank::lockForUpdate()->findOrFail($penarikan->rekening_bank_id);

                    // 3. Hitung Pajak & Jumlah Bersih
                    $jumlahPenarikan = $penarikan->jumlah;
                    $pajakPenarikan = (int) ceil($jumlahPenarikan * 0.025); // Pajak 2.5%
                    $jumlahMasukRekening = $jumlahPenarikan - $pajakPenarikan; // Uang bersih untuk Mitra

                    // 4. Tambahkan Saldo ke Rekening Bank Mitra
                    $rekening->increment('saldo', $jumlahMasukRekening);

                    // 5. Tambahkan Saldo (Pajak) ke Pemasukan SuperAdmin
                    $superAdmin->increment('saldo_pemasukan', $pajakPenarikan);

                    // 6. Buat Log Keuangan untuk Pajak
                    LogKeuangan::create([
                        'transaksi_id' => null,
                        'penarikan_id' => $penarikan->penarikan_id,
                        'penerima_type' => Admin::class,
                        'penerima_id' => $superAdmin->admin_id,
                        'tipe' => 'pajak_penarikan_mitra',
                        'jumlah' => $pajakPenarikan
                    ]);

                    // 7. Update status penarikan
                    $penarikan->update([
                        'status' => 'Selesai',
                        'potongan_pajak' => $pajakPenarikan,
                        'catatan_admin' => $catatan ?? 'Disetujui. Dikenakan biaya admin 2.5%.'
                    ]);

                } elseif ($action == 'reject') {
                    // (Logika Tolak tidak berubah)
                    $mitra = $penarikan->penarikanable;
                    $mitra->increment('saldo_pemasukan', $penarikan->jumlah);
                    $penarikan->update([
                        'status' => 'Ditolak',
                        'catatan_admin' => $catatan ?? 'Penarikan ditolak.'
                    ]);
                }
            });

            if ($action == 'approve') {
                 // === PERBAIKAN 2 === (Redirect jika sukses setuju)
                 return redirect()->route('admin.pemasukan.index')
                                 ->with('success', 'Penarikan dana berhasil disetujui (Pajak 2.5% diterapkan).');
            } else {
                 // (Redirect jika sukses tolak, boleh ke halaman review lagi)
                 return redirect()->route('admin.review.penarikan.index')
                                 ->with('success', 'Penarikan dana berhasil ditolak dan saldo telah dikembalikan ke Mitra.');
            }

        } catch (\Exception $e) {
            // === PERBAIKAN 3 === (Redirect jika ada error)
            return redirect()->route('admin.pemasukan.index')
                             ->with('error', 'Gagal memproses aksi: ' . $e->getMessage());
        }
    }
}
