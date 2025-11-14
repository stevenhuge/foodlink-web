<?php
// app/Http/Controllers/Mitra/DashboardController.php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailTransaksi; // <-- Import Model Detail Transaksi

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\Mitra $mitra */
        $mitra = Auth::guard('mitra')->user();
        $mitra_id = $mitra->mitra_id;

        // Cek status verifikasi
        if ($mitra->status_verifikasi === 'Verified') {

            /**
             * === PERBAIKAN DI SINI ===
             * Error "Unknown column 'created_at'" terjadi karena
             * kita sorting di tabel 'detail_transaksi' yang tidak punya timestamps.
             *
             * Kita harus sorting berdasarkan 'waktu_pemesanan' dari tabel 'transaksi'
             * yang terhubung, menggunakan JOIN.
             */
            $penjualanTerbaru = DetailTransaksi::select('detail_transaksi.*') // 1. Pilih kolom dari detail_transaksi
                ->join('transaksi', 'detail_transaksi.transaksi_id', '=', 'transaksi.transaksi_id') // 2. Join ke tabel transaksi
                ->where('transaksi.mitra_id', $mitra_id) // 3. Filter transaksi milik mitra
                ->whereIn('transaksi.status_pemesanan', ['paid', 'selesai']) // 4. Filter status
                ->with(['produk', 'transaksi']) // 5. Eager load relasi (tetap diperlukan view)
                ->orderBy('transaksi.waktu_pemesanan', 'desc') // 6. <-- INI PERBAIKANNYA (sort by waktu_pemesanan)
                ->limit(5) // 7. Ambil 5 saja
                ->get();
            // === AKHIR PERBAIKAN ===

            // Kirim data baru ke view dashboard utama
            return view('mitra.dashboard', [
                'mitra' => $mitra,
                'penjualanTerbaru' => $penjualanTerbaru
            ]);

        } elseif ($mitra->status_verifikasi === 'Pending') {
            // Jika masih pending, tampilkan halaman tunggu
            return view('mitra.pending', compact('mitra'));

        } else {
            // Jika ditolak (Rejected)
            return view('mitra.rejected', compact('mitra'));
        }
    }
}
