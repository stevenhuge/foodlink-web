<?php
// app/Http/Controllers/Admin/UserManagementController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AlasanBlokirOption; // <-- 1. IMPORT MODEL ALASAN

class UserManagementController extends Controller
{
    /**
     * Menampilkan daftar semua user (pelanggan).
     */
    public function index()
    {
        $users = User::orderBy('nama_lengkap')->get();

        /**
         * === TAMBAHAN WAJIB ===
         * Ambil daftar alasan (berdasarkan 'alasan_text' Anda)
         * dan kirim ke view.
         */
        $alasanBlokirOptions = AlasanBlokirOption::orderBy('alasan_text')->get();

        return view('admin.users.index', compact('users', 'alasanBlokirOptions'));
    }

    /**
     * Menampilkan form untuk mengedit user.
     */
    public function edit(User $user)
    {
        // $user otomatis diambil oleh Route Model Binding
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Memperbarui data user di database.
     */

    public function update(Request $request, User $user)
    {
        // 1. Validasi Data Masuk
        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            // Pengecualian ID saat update email
            'email' => 'required|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
            'poin_reward' => 'required|integer|min:0',

            // Menggunakan min:8 untuk input password (plainteks)
            'password_hash' => 'nullable|string|min:8',
        ]);

        // 2. Memproses Password (Opsional)

        // Periksa apakah input password_hash diisi oleh user
        if (!empty($validatedData['password_hash'])) {
            // Jika diisi, hash password plainteks sebelum diupdate
            $user->password_hash = Hash::make($validatedData['password_hash']);

            // Hapus password_hash dari $validatedData karena sudah dimasukkan secara manual ke $user
            unset($validatedData['password_hash']);
        } else {
            // Jika tidak diisi (kosong), HAPUS password_hash dari array data yang akan diupdate.
            // Ini memastikan field password_hash TIDAK dikirim sebagai null ke database.
            unset($validatedData['password_hash']);
        }

        // 3. Update data yang tersisa
        // Menggunakan fill() dan save() atau update()
        $user->fill($validatedData);
        $user->save();

        // 4. Redirect dan Notifikasi
        return redirect()->route('admin.users.index')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Memblokir akun user.
     */
    public function block(Request $request, User $user)
    {
        /**
         * === PERUBAHAN WAJIB ===
         * Validasi 'alasan_blokir_id' dari modal, bukan 'alasan_blokir' (teks)
         */
        $request->validate(
            [
                'alasan_blokir_id' => 'required|integer|exists:alasan_blokir_options,alasan_id'
            ],
            [
                'alasan_blokir_id.required' => 'Anda harus memilih alasan pemblokiran.'
            ]
        );

        // Cari alasan berdasarkan ID
        $alasan = AlasanBlokirOption::find($request->alasan_blokir_id);

        $user->update([
            'status_akun' => 'Diblokir', // Gunakan 'Diblokir' agar konsisten
            'alasan_blokir' => $alasan->alasan_text, // Simpan 'alasan_text' (sesuai Model Anda)
        ]);

        // Hapus semua token login API user tersebut
        $user->tokens()->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'User ' . $user->nama_lengkap . ' berhasil diblokir.');
    }

    /**
     * Membuka blokir akun user.
     */
    public function unblock(User $user)
    {
        $user->update([
            'status_akun' => 'Aktif', // Gunakan 'Aktif' agar konsisten
            'alasan_blokir' => null,
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Blokir user ' . $user->nama_lengkap . ' berhasil dibuka.');
    }
}
