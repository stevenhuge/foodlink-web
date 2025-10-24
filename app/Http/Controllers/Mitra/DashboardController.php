<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\Mitra $mitra */
        $mitra = Auth::guard('mitra')->user();

        // Cek status verifikasi
        if ($mitra->status_verifikasi === 'Verified') {
            // Jika sudah diverifikasi, tampilkan dashboard utama
            // Di sini Anda akan menambahkan logika CRUD Produk
            return view('mitra.dashboard', compact('mitra'));

        } elseif ($mitra->status_verifikasi === 'Pending') {
            // Jika masih pending, tampilkan halaman tunggu
            return view('mitra.pending', compact('mitra'));

        } else {
            // Jika ditolak (Rejected)
            return view('mitra.rejected', compact('mitra'));
        }
    }
}
