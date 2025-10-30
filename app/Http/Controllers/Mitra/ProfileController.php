<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\KategoriUsaha; // <-- Pastikan ini ada
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password; // <-- Rules password Laravel
// Hapus alias PasswordBroker karena tidak bentrok

class ProfileController extends Controller
{
    /**
     * Tampilkan form edit profil mitra.
     */
    public function edit()
    {
        $mitra = Auth::guard('mitra')->user();
        $kategoriUsaha = KategoriUsaha::orderBy('nama_kategori')->get();
        // Pastikan nama view di sini TEPAT 'mitra.profile.edit'
        return view('mitra.profile.edit', compact('mitra', 'kategoriUsaha'));
    }

    /**
     * Update profil mitra.
     */
    public function update(Request $request)
    {
        $mitra = Auth::guard('mitra')->user();

        $validated = $request->validate([
            'nama_mitra' => ['required', 'string', 'max:255'],
            // Email tidak diubah di sini, biasanya proses terpisah
            'nomor_telepon' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
            'deskripsi' => ['nullable', 'string'],
            'kategori_usaha_id' => ['required', 'integer', 'exists:kategori_usaha,kategori_usaha_id'], // Wajib diisi mitra
            // Validasi password jika ingin diubah
            // 'current_password' => ['nullable', 'required_with:new_password', 'current_password:mitra'], // Gunakan rule Laravel
            // 'new_password' => ['nullable', 'string', 'min:8', 'confirmed'], // Gunakan rule Laravel

            // Cara validasi password yang lebih benar:
            'current_password' => ['nullable', 'required_with:new_password', function ($attribute, $value, $fail) use ($mitra) {
                if (!Hash::check($value, $mitra->password_hash)) {
                    $fail('Password saat ini yang Anda masukkan salah.');
                }
            }],
            'new_password' => ['nullable', 'confirmed', Password::min(8)],
        ], [
            // Pesan error custom (opsional)
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        // Update data dasar
        $mitra->nama_mitra = $validated['nama_mitra'];
        $mitra->nomor_telepon = $validated['nomor_telepon'];
        $mitra->alamat = $validated['alamat'];
        $mitra->deskripsi = $validated['deskripsi'] ?? $mitra->deskripsi; // Tetap pakai deskripsi lama jika kosong
        $mitra->kategori_usaha_id = $validated['kategori_usaha_id'];

        // Update password hanya jika new_password diisi DAN validasi current_password lolos
        if ($request->filled('new_password') && $request->filled('current_password') && Hash::check($request->current_password, $mitra->password_hash)) {
             $mitra->password_hash = Hash::make($validated['new_password']);
        } elseif ($request->filled('new_password') && !$request->filled('current_password')) {
            // Jika password baru diisi tapi password lama kosong, kembalikan error
            return back()->withErrors(['current_password' => 'Password saat ini wajib diisi untuk mengubah password.'])->withInput();
        }
         // Jika current_password salah, validasi bawaan sudah menanganinya

        $mitra->save();

        return redirect()->route('mitra.profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
