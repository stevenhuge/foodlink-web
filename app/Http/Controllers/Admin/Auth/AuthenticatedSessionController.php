<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan halaman login Admin.
     */
    public function create(): View
    {
        // Pastikan view mengarah ke form login admin
        return view('admin.auth.login');
    }

    /**
     * Menangani permintaan login Admin.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi menggunakan 'username'
        $request->validate([
            'username' => 'required|string', // Ganti dari email_bisnis
            'password' => 'required|string',
        ]);

        // 2. Siapkan kredensial dengan 'username'
        $credentials = [
            'username' => $request->username, // Ganti dari email_bisnis
            'password' => $request->password,
        ];

        // 3. Coba login menggunakan guard 'admin'
        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {

            // Pengecekan status akun admin jika perlu (misal: aktif/nonaktif)
            // $admin = Auth::guard('admin')->user();
            // if ($admin->status === 'nonaktif') {
            //     Auth::guard('admin')->logout();
            //     // ... throw ValidationException ...
            // }

            // Jika login berhasil & akun aktif
            $request->session()->regenerate();

            // 4. Redirect ke dashboard admin
            return redirect()->intended(route('admin.dashboard'));
        }

        // Jika kredensial salah
        throw ValidationException::withMessages([
            // 5. Tampilkan error pada field 'username'
            'username' => __('auth.failed'), // Pesan error standar Laravel
        ]);
    }

    /**
     * Menangani permintaan logout Admin.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Pastikan logout menggunakan guard 'admin'
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke halaman login admin
        return redirect()->route('admin.login');
    }
}
