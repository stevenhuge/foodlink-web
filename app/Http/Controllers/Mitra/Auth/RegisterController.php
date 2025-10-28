<?php

namespace App\Http\Controllers\Mitra\Auth;

use App\Http\Controllers\Controller;
use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\KategoriUsaha; // Pastikan ini sudah ada

class RegisterController extends Controller
{
    /**
     * Menampilkan halaman registrasi.
     */
    public function create(): View // Menambahkan return type hint
    {
        // Ambil semua kategori usaha, urutkan berdasarkan nama
        $kategoriUsaha = KategoriUsaha::orderBy('nama_kategori')->get();
        // Kirim data kategori ke view 'register'
        return view('mitra.auth.register', compact('kategoriUsaha'));
    }

    /**
     * Menangani permintaan registrasi.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_mitra' => ['required', 'string', 'max:255'],
            'email_bisnis' => ['required', 'string', 'email', 'max:255', 'unique:mitra,email_bisnis'], // Lebih eksplisit unique:tabel,kolom
            'nomor_telepon' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
            'deskripsi' => ['nullable', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // --- 1. TAMBAHKAN VALIDASI KATEGORI ---
            'kategori_usaha_id' => ['required', 'integer', 'exists:kategori_usaha,kategori_usaha_id'],
            // ------------------------------------
        ]);

        $mitra = Mitra::create([
            'nama_mitra' => $request->nama_mitra,
            'email_bisnis' => $request->email_bisnis,
            'nomor_telepon' => $request->nomor_telepon,
            'alamat' => $request->alamat,
            'deskripsi' => $request->deskripsi,
            'password_hash' => Hash::make($request->password),
            // --- 2. TAMBAHKAN PENYIMPANAN KATEGORI ---
            'kategori_usaha_id' => $request->kategori_usaha_id,
            // ----------------------------------------
            // Status Verifikasi otomatis 'Pending' (jika ada default di DB)
        ]);

        // Otomatis loginkan mitra setelah registrasi
        Auth::guard('mitra')->login($mitra);

        // Redirect ke dashboard mitra
        return redirect()->route('mitra.dashboard');
    }
}
