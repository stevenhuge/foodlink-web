<?php
// app/Http/Controllers/Api/AuthController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini

class AuthController extends Controller
{
    /**
     * Fitur 1: Register User
     * Endpoint: POST /api/register
     */
    public function register(Request $request)
    {
        $fields = $request->validate([
            'nama_lengkap' => 'required|string',
            // Validasi hanya boleh Laki-laki atau Perempuan
            // 'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed|min:8'
        ]);

        $user = User::create([
            'nama_lengkap' => $fields['nama_lengkap'],
            // 'jenis_kelamin' => $fields['jenis_kelamin'], // <-- Simpan ke DB
            'email' => $fields['email'],
            'password_hash' => bcrypt($fields['password']),
            'poin_reward' => 0,
        ]);

        $token = $user->createToken('token-foodlink')->plainTextToken;
        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    /**
     * Fitur 1: Login User
     * Endpoint: POST /api/login
     */
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password_hash)) {
            return response()->json(['message' => 'Email atau Password salah'], 401);
        }

        $token = $user->createToken('token-foodlink')->plainTextToken;
        return response()->json(['user' => $user, 'token' => $token], 200);
    }

    // --- FUNGSI BARU ---

    /**
     * Logout User
     * Endpoint: POST /api/logout (Middleware auth:sanctum)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil'], 200);
    }

    /**
     * Melihat profil user (termasuk poin)
     * Endpoint: GET /api/profile (Middleware auth:sanctum)
     */
    // app/Http/Controllers/Api/AuthController.php

    public function profile(Request $request)
    {
        // Ambil data user dari database berdasarkan user_id token yang login
        $user = User::where('user_id', $request->user()->user_id)->first();

        return response()->json([
            'message' => 'Berhasil ambil data',
            'user' => $user, // Laravel akan otomatis menyertakan semua kolom fillable
            'token' => null
        ]);
    }

    // buatkan untuk update profile
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $fields = $request->validate([
            'nama_lengkap' => 'sometimes|required|string',
            'jenis_kelamin' => 'sometimes|in:Laki-laki,Perempuan',
            'email' => 'sometimes|required|string|email|unique:users,email,' . $user->user_id . ',user_id',
            'nomor_telepon' => 'sometimes|required|string',
            'password' => 'sometimes|required|string|confirmed|min:8'
        ]);

        if (isset($fields['nama_lengkap'])) {
            $user->nama_lengkap = $fields['nama_lengkap'];
        }
        if (isset($fields['jenis_kelamin'])) {
            $user->jenis_kelamin = $fields['jenis_kelamin'];
        }
        if (isset($fields['email'])) {
            $user->email = $fields['email'];
        }
        if (isset($fields['nomor_telepon'])) {
            $user->nomor_telepon = $fields['nomor_telepon'];
        }
        if (isset($fields['password'])) {
            $user->password_hash = bcrypt($fields['password']);
        }

        $user->save();

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'user' => $user,
            'token' => null
        ]);
    }
}
