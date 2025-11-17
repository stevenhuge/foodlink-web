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
    // app/Http/Controllers/SuperAdmin/PemasukanController.php

    public function storePenarikan(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'jumlah' => [
                'required', 'integer', 'min:20000', // <-- UBAH DI SINI
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

                $rekening = RekeningBank::lockForUpdate()->find($rekeningId);
                $admin->decrement('saldo_pemasukan', $jumlah);
                $rekening->increment('saldo', $jumlah);

                PenarikanDana::create([
                    'penarikanable_id' => $admin->admin_id,
                    'penarikanable_type' => Admin::class,
                    'rekening_bank_id' => $rekeningId,
                    'jumlah' => $jumlah,
                    'potongan_pajak' => 0, // SuperAdmin tidak kena pajak
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
