<?php
// app/Http/Controllers/Mitra/RiwayatTransaksiController.php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class RiwayatTransaksiController extends Controller
{
    public function index()
    {
        $mitraId = Auth::guard('mitra')->id();
        $transaksis = Transaksi::where('mitra_id', $mitraId)
                            ->with('user', 'detailTransaksi.produk')
                            ->orderBy('waktu_pemesanan', 'desc')
                            ->get();
        return view('mitra.riwayat.index', compact('transaksis'));
    }

    public function show($id)
    {
        $mitraId = Auth::guard('mitra')->id();
        $transaksi = Transaksi::where('mitra_id', $mitraId)
                             ->with('user', 'detailTransaksi.produk')
                             ->findOrFail($id);
        return view('mitra.riwayat.show', compact('transaksi'));
    }

    public function konfirmasi($id)
    {
        $mitraId = Auth::guard('mitra')->id();

        $transaksi = Transaksi::where('transaksi_id', $id)
                             ->where('mitra_id', $mitraId)
                             ->firstOrFail();

        // === PERBAIKAN DI SINI ===
        // Gunakan strtolower() untuk mengubah 'Paid' atau 'paid' menjadi 'paid'
        if (strtolower($transaksi->status_pemesanan) == 'paid') {

            // Ubah status menjadi 'selesai' (huruf kecil standar)
            $transaksi->status_pemesanan = 'selesai';
            $transaksi->save();

            return redirect()->route('mitra.riwayat.index')
                             ->with('success', 'Transaksi ' . $transaksi->kode_unik_pengambilan . ' telah dikonfirmasi (Selesai).');
        }

        return redirect()->route('mitra.riwayat.index')
                         ->with('error', 'Transaksi ini sudah dikonfirmasi sebelumnya atau statusnya bukan "paid".');
    }
}
