<?php

namespace App\Http\Controllers\Mitra\Auth;

use App\Http\Controllers\Controller;
use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RegisterController extends Controller
{
    /**
     * Menampilkan halaman registrasi.
     */
    public function create(): View
    {
        return view('mitra.auth.register');
    }

    /**
     * Menangani permintaan registrasi.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_mitra' => 'required|string|max:255',
            'email_bisnis' => 'required|string|email|max:255|unique:mitra',
            'nomor_telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'deskripsi' => 'nullable|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $mitra = Mitra::create([
            'nama_mitra' => $request->nama_mitra,
            'email_bisnis' => $request->email_bisnis,
            'nomor_telepon' => $request->nomor_telepon,
            'alamat' => $request->alamat,
            'deskripsi' => $request->deskripsi,
            'password_hash' => Hash::make($request->password),
            // Status Verifikasi otomatis 'Pending' sesuai setup migrasi
        ]);

        // Otomatis loginkan mitra setelah registrasi
        Auth::guard('mitra')->login($mitra);

        // Redirect ke dashboard mitra
        return redirect()->route('mitra.dashboard');
    }
}
