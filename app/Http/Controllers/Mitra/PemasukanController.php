<?php
// app/Http/Controllers/Mitra/PemasukanController.php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\LogKeuangan;
use App\Models\PenarikanDana;
use App\Models\Mitra; // <-- Pastikan Anda import model Mitra
use App\Models\Transaksi; // <-- Import Transaksi
use Illuminate\Validation\Rule;

class PemasukanController extends Controller
{
    /**
     * Menampilkan halaman statistik pemasukan Mitra.
     */
    public function index()
    {
        $mitra = Auth::guard('mitra')->user();

        // 1. Saldo saat ini (yang bisa ditarik)
        $saldoSaatIni = $mitra->saldo_pemasukan;

        // 2. Total pemasukan kotor (hanya dari penjualan bersih)
        $totalPemasukan = $mitra->logKeuangan()
                                ->where('tipe', 'penjualan_bersih')
                                ->sum('jumlah');

        // 3. Total yang sudah berhasil ditarik
        $totalDitarik = $mitra->penarikanDana()
                              ->where('status', 'Selesai')
                              ->sum('jumlah');

        // 4. Riwayat penarikan milik Mitra ini
        $riwayatPenarikan = $mitra->penarikanDana()
                                  ->with('rekeningBank') // Ambil data rekening
                                  ->orderBy('created_at', 'desc')
                                  ->get();

        // 5. Ambil rekening bank milik Mitra (untuk dropdown)
        $rekeningBank = $mitra->rekeningBanks;

        // 6. Ambil Rincian Transaksi (untuk melihat potongan pajak)
        $rincianTransaksi = Transaksi::where('mitra_id', $mitra->mitra_id)
                                    ->with('user')
                                    ->orderBy('waktu_pemesanan', 'desc')
                                    ->get();

        return view('mitra.pemasukan.index', compact(
            'saldoSaatIni',
            'totalPemasukan',
            'totalDitarik',
            'riwayatPenarikan',
            'rekeningBank',
            'rincianTransaksi'
        ));
    }

    /**
     * Memproses permintaan penarikan dana oleh Mitra.
     */
    public function storePenarikan(Request $request)
    {
        $mitra = Auth::guard('mitra')->user();

        $request->validate([
            'jumlah' => [
                'required',
                'integer',
                'min:50000', // Minimal penarikan 50rb
                'max:' . $mitra->saldo_pemasukan, // Tidak boleh > saldo
            ],
            'rekening_bank_id' => [
                'required',
                'integer',
                // Validasi bahwa rekening_id yang dipilih adalah BENAR milik mitra ini
                Rule::exists('rekening_bank', 'rekening_id')->where(function ($query) use ($mitra) {
                    $query->where('rekeningable_id', $mitra->mitra_id)
                          ->where('rekeningable_type', Mitra::class); // Pastikan namespace 'App\Models\Mitra'
                }),
            ]
        ], [
            'jumlah.max' => 'Jumlah penarikan melebihi saldo Anda yang tersedia.',
            'rekening_bank_id.exists' => 'Rekening bank yang dipilih tidak valid.'
        ]);

        try {
            DB::transaction(function () use ($request, $mitra) {
                $jumlah = $request->input('jumlah');

                // 1. Kurangi saldo Mitra
                $mitra->decrement('saldo_pemasukan', $jumlah);

                // 2. Buat catatan penarikan
                // Status 'Pending' karena harus di-review SuperAdmin
                PenarikanDana::create([
                    'penarikanable_id' => $mitra->mitra_id,
                    'penarikanable_type' => Mitra::class, // Pastikan namespace 'App\Models\Mitra'
                    'rekening_bank_id' => $request->input('rekening_bank_id'),
                    'jumlah' => $jumlah,
                    'status' => 'Pending', // <-- Status Pending
                ]);
            });

            return redirect()->route('mitra.pemasukan.index')
                             ->with('success', 'Permintaan penarikan sebesar ' . number_format($request->input('jumlah')) . ' Poin telah diajukan dan sedang diproses.');

        } catch (\Exception $e) {
            return redirect()->route('mitra.pemasukan.index')
                             ->with('error', 'Gagal memproses penarikan: ' . $e->getMessage());
        }
    }
}
