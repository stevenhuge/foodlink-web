<?php
// app/Http/Controllers/Admin/UserManagementController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AlasanBlokirOption;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Menampilkan daftar semua user (pelanggan).
     */
    public function index()
    {
        $users = User::orderBy('nama_lengkap')->get();

        $alasanBlokirOptions = AlasanBlokirOption::orderBy('alasan_text')->get();

        return view('admin.users.index', compact('users', 'alasanBlokirOptions'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'email' => 'required|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
            'poin_reward' => 'required|integer|min:0',
            'password_hash' => 'nullable|string|min:8',
        ]);

        if (!empty($validatedData['password_hash'])) {
            $user->password_hash = Hash::make($validatedData['password_hash']);
        }
        unset($validatedData['password_hash']);

        $user->fill($validatedData);
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    public function block(Request $request, User $user)
    {
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
