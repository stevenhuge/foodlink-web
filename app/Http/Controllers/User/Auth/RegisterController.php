<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function create()
    {
        return view('user.auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'email' => 'required|string|email|max:100|unique:users',
            'nomor_telepon' => 'required|string|max:15|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'email' => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
            'password_hash' => Hash::make($request->password),
            'poin_reward' => 0,
            'status_akun' => 'Aktif',
        ]);

        Auth::guard('web')->login($user);

        return redirect()->route('welcome')->with('success_register_user', 'Akun anda berhasil dibuat, akun bisa digunakan pada fitur ai dan login pada aplikasi mobile yang tersedia.');
    }
}
