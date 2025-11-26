<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Mitra;

class ForgotPasswordController extends Controller
{
    // 1. Tampilkan Form Input Email
    public function showLinkRequestForm()
    {
        return view('mitra.auth.passwords.email');
    }

    // 2. Proses Kirim Link ke Email
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email_bisnis' => 'required|email']);

        // Kirim link menggunakan Broker 'mitra'
        $status = Password::broker('mitra')->sendResetLink(
            $request->only('email_bisnis')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        return back()->withErrors(['email_bisnis' => __($status)]);
    }

    // 3. Tampilkan Form Reset Password (Setelah klik link email)
    public function showResetForm(Request $request, $token = null)
    {
        return view('mitra.auth.passwords.reset')->with(
        [
                'token' => $token,
                'email_bisnis' => $request->email
            ]
        );
    }

    // 4. Proses Update Password Baru
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email_bisnis' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Reset password menggunakan Broker 'mitra'
        $status = Password::broker('mitra')->reset(
            $request->only('email_bisnis', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password_hash' => Hash::make($password) // Sesuaikan nama kolom password di DB Anda
                ])->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('mitra.login')->with('success', 'Password berhasil diubah! Silakan login.');
        }

        return back()
            ->withInput($request->only('email_bisnis'))
            ->withErrors(['email_bisnis' => __($status)]);
    }
}
