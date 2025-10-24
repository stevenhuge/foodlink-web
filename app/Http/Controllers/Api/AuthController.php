<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Fungsi Register User
    public function register(Request $request)
    {
        $fields = $request->validate([
            'nama_lengkap' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'nama_lengkap' => $fields['nama_lengkap'],
            'email' => $fields['email'],
            'password_hash' => bcrypt($fields['password']),
        ]);

        $token = $user->createToken('token-foodlink')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    // Fungsi Login User
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
}
