<?php
// app/Http/Controllers/SuperAdmin/PemasukanController.php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\LogKeuangan;
use App\Models\PenarikanDana;
use App\Models\Admin;
use Illuminate\Validation\Rule;

class PemasukanController extends Controller
{
    /**
     * Menampilkan halaman statistik pemasukan SuperAdmin.
     */
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        // 1. Saldo saat ini (yang bisa ditarik)
        $saldoSaatIni = $admin->saldo_pemasukan;

        // 2. Total pemasukan kotor (dari semua pajak + biaya)
        $totalPemasukan = $admin->logKeuangan()->sum('jumlah');

        // 3. Total yang sudah berhasil ditarik
        $totalDitarik = $admin->penarikanDana()
                              ->where('status', 'Selesai')
                              ->sum('jumlah');

        // 4. Riwayat penarikan milik SuperAdmin sendiri
        $riwayatPenarikan = $admin->penarikanDana()
                                  ->with('rekeningBank')
                                  ->orderBy('created_at', 'desc')
                                  ->get();

        // 5. Ambil rekening bank milik SuperAdmin (untuk dropdown)
        $rekeningBank = $admin->rekeningBanks;

        /**
         * === TAMBAHAN BARU ===
         * 6. Ambil Rincian Pemasukan (log pajak + layanan)
         */
        $rincianPemasukan = $admin->logKeuangan()
                                 ->with('transaksi.user') // Ambil data transaksi & pembeli
                                 ->orderBy('created_at', 'desc')
                                 ->get();

        return view('superadmin.pemasukan.index', compact(
            'saldoSaatIni',
            'totalPemasukan',
            'totalDitarik',
            'riwayatPenarikan',
            'rekeningBank',
            'rincianPemasukan' // <-- Kirim data baru ke view
        ));
    }

    /**
     * Memproses permintaan penarikan dana oleh SuperAdmin.
     */
    public function storePenarikan(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'jumlah' => [
                'required',
                'integer',
                'min:50000',
                'max:' . $admin->saldo_pemasukan,
            ],
            'rekening_bank_id' => [
                'required',
                'integer',
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
                $admin->decrement('saldo_pemasukan', $jumlah);

                PenarikanDana::create([
                    'penarikanable_id' => $admin->admin_id,
                    'penarikanable_type' => Admin::class,
                    'rekening_bank_id' => $request->input('rekening_bank_id'),
                    'jumlah' => $jumlah,
                    'status' => 'Selesai',
                    'catatan_admin' => 'Penarikan oleh SuperAdmin.'
                ]);
            });

            return redirect()->route('admin.pemasukan.index') // <-- Rute sudah benar
                             ->with('success', 'Penarikan dana sebesar ' . number_format($request->input('jumlah')) . ' Poin berhasil.');

        } catch (\Exception $e) {
            return redirect()->route('admin.pemasukan.index') // <-- Rute sudah benar
                             ->with('error', 'Gagal memproses penarikan: ' . $e->getMessage());
        }
    }
}
