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
use App\Models\RekeningBank; // <-- TAMBAHKAN IMPORT INI
use Illuminate\Validation\Rule;

class PemasukanController extends Controller
{
    // ... (Fungsi index() Anda tidak berubah) ...
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        $saldoSaatIni = $admin->saldo_pemasukan;
        $totalPemasukan = $admin->logKeuangan()->sum('jumlah');
        $totalDitarik = $admin->penarikanDana()
                              ->where('status', 'Selesai')
                              ->sum('jumlah');
        $riwayatPenarikan = $admin->penarikanDana()
                                  ->with('rekeningBank')
                                  ->orderBy('created_at', 'desc')
                                  ->get();
        $rekeningBank = $admin->rekeningBanks;
        $rincianPemasukan = $admin->logKeuangan()
                                 ->with('transaksi.user')
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

    /**
     * Memproses permintaan penarikan dana oleh SuperAdmin.
     */
    public function storePenarikan(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'jumlah' => [
                'required', 'integer', 'min:50000',
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

                // 1. Ambil dan Kunci Rekening Bank
                $rekening = RekeningBank::lockForUpdate()->find($rekeningId);

                // 2. Kurangi saldo SuperAdmin
                $admin->decrement('saldo_pemasukan', $jumlah);

                // 3. TAMBAHKAN SALDO KE REKENING BANK
                $rekening->increment('saldo', $jumlah);

                // 4. Buat catatan penarikan
                PenarikanDana::create([
                    'penarikanable_id' => $admin->admin_id,
                    'penarikanable_type' => Admin::class,
                    'rekening_bank_id' => $rekeningId,
                    'jumlah' => $jumlah,
                    'status' => 'Selesai',
                    'catatan_admin' => 'Penarikan oleh SuperAdmin.'
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
