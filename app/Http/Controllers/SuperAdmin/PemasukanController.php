<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\LogKeuangan;
use App\Models\PenarikanDana;
use App\Models\Admin;
use App\Models\RekeningBank;
use App\Models\Transaksi; // <-- WAJIB: Import Model Transaksi
use Illuminate\Validation\Rule;

class PemasukanController extends Controller
{
    public function index()
    {
        // 1. Ambil Admin yang Sedang Login
        $admin = Auth::guard('admin')->user();

        // 2. Ambil Saldo Saat Ini (Real Money di Dompet Admin)
        $saldoSaatIni = $admin->saldo_pemasukan;

        // 3. HITUNG TOTAL PEMASUKAN BERSIH (Dari Transaksi Selesai)
        // Rumus: Total Biaya Layanan User + Total Potongan Mitra

        $totalBiayaLayanan = Transaksi::where('status_pemesanan', 'selesai')
                                      ->sum('biaya_layanan_user');

        $totalPotonganMitra = Transaksi::where('status_pemesanan', 'selesai')
                                       ->sum('potongan_pajak_mitra');

        $totalPemasukan = $totalBiayaLayanan + $totalPotonganMitra;

        // 4. Hitung Total yang Sudah Ditarik Admin (Withdrawal)
        $totalDitarik = $admin->penarikanDana()
                              ->where('status', 'Selesai')
                              ->sum('jumlah');

        // 5. Data Pendukung Tampilan (Riwayat & Rekening)
        $riwayatPenarikan = $admin->penarikanDana()
                                  ->with('rekeningBank')
                                  ->orderBy('created_at', 'desc')
                                  ->get();

        $rekeningBank = $admin->rekeningBanks;

        $rincianPemasukan = $admin->logKeuangan()
                                  ->with(['transaksi.user', 'penarikanDana'])
                                  ->orderBy('created_at', 'desc')
                                  ->get();

        return view('superadmin.pemasukan.index', compact(
            'saldoSaatIni',
            'totalPemasukan',
            'totalDitarik',
            'riwayatPenarikan',
            'rekeningBank',
            'rincianPemasukan'
        ));
    }

    public function storePenarikan(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'jumlah' => [
                'required', 'integer', 'min:20000',
                'max:' . $admin->saldo_pemasukan,
            ],
            'rekening_bank_id' => [
                'required', 'integer',
                Rule::exists('rekening_bank', 'rekening_id')->where(function ($query) use ($admin) {
                    $query->where('rekeningable_id', $admin->admin_id)
                          ->where('rekeningable_type', Admin::class);
                }),
            ]
        ], [
            'jumlah.max' => 'Jumlah penarikan melebihi saldo Anda yang tersedia.',
            'rekening_bank_id.exists' => 'Rekening bank yang dipilih tidak valid.'
        ]);

        try {
            DB::transaction(function () use ($request, $admin) {
                $jumlah = $request->input('jumlah');
                $rekeningId = $request->input('rekening_bank_id');

                // Lock Rekening & Admin Saldo
                $rekening = RekeningBank::lockForUpdate()->find($rekeningId);
                $admin = Admin::lockForUpdate()->find($admin->admin_id); // Re-lock admin

                // Kurangi Saldo Admin
                $admin->decrement('saldo_pemasukan', $jumlah);

                // Tambah Saldo Rekening (Simulasi Transfer)
                $rekening->increment('saldo', $jumlah);

                // Catat Penarikan
                $penarikan = PenarikanDana::create([
                    'penarikanable_id' => $admin->admin_id,
                    'penarikanable_type' => Admin::class,
                    'rekening_bank_id' => $rekeningId,
                    'jumlah' => $jumlah,
                    'potongan_pajak' => 0, // SuperAdmin tidak kena pajak
                    'status' => 'Selesai',
                    'catatan_admin' => 'Penarikan oleh SuperAdmin.'
                ]);

                // WAJIB: Catat Log Keuangan (Uang Keluar) agar rincian balance
                LogKeuangan::create([
                    'penerima_type' => Admin::class,
                    'penerima_id' => $admin->admin_id,
                    'penarikan_dana_id' => $penarikan->penarikan_id, // Link ke penarikan
                    'tipe' => 'penarikan_saldo',
                    'jumlah' => -($jumlah), // Minus karena uang keluar
                    'keterangan' => 'Penarikan ke ' . $rekening->nama_bank
                ]);
            });

            return redirect()->route('admin.pemasukan.index')
                            ->with('success', 'Penarikan dana sebesar ' . number_format($request->input('jumlah')) . ' Poin berhasil.');

        } catch (\Exception $e) {
            return redirect()->route('admin.pemasukan.index')
                            ->with('error', 'Gagal memproses penarikan: ' . $e->getMessage());
        }
    }
}
