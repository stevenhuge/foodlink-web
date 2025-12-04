<?php
// app/Http/Controllers/Mitra/DashboardController.php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailTransaksi; // <-- Import Model Detail Transaksi
use App\Models\MitraNotifikasi;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil Data Mitra yang Sedang Login
        $mitra = Auth::guard('mitra')->user();

        // 2. Cek Status Verifikasi Mitra
        if ($mitra->status_verifikasi === 'Verified') {

            // A. Ambil Notifikasi (Untuk Info Perubahan Pajak/Admin)
            $notifikasi = MitraNotifikasi::where('mitra_id', $mitra->mitra_id)
                ->orderBy('created_at', 'desc')
                ->take(5) // Ambil 5 terbaru
                ->get();

            // B. Ambil Penjualan Terbaru (Logic yang sudah diperbaiki)
            $penjualanTerbaru = DetailTransaksi::select('detail_transaksi.*')
                ->join('transaksi', 'detail_transaksi.transaksi_id', '=', 'transaksi.transaksi_id')
                ->where('transaksi.mitra_id', $mitra->mitra_id)
                ->whereIn('transaksi.status_pemesanan', ['paid', 'selesai'])
                ->with(['produk', 'transaksi']) // Eager load agar tidak N+1 Query
                ->orderBy('transaksi.waktu_pemesanan', 'desc')
                ->limit(5)
                ->get();

            // C. Kirim Semua Data ke View Dashboard
            return view('mitra.dashboard', [
                'mitra' => $mitra,
                'notifikasi' => $notifikasi, // <-- Variabel ini wajib ada di view
                'penjualanTerbaru' => $penjualanTerbaru
            ]);

        } elseif ($mitra->status_verifikasi === 'Pending') {
            // Jika status Pending, arahkan ke halaman tunggu
            return view('mitra.pending', compact('mitra'));

        } else {
            // Jika status Rejected, arahkan ke halaman ditolak
            return view('mitra.rejected', compact('mitra'));
        }
    }
}
