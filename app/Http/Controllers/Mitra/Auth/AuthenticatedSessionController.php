<?php

namespace App\Http\Controllers\Mitra\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function create(): View
    {
        // Arahkan ke view login mitra
        return view('mitra.auth.login');
    }

    /**
     * Menangani permintaan login.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email_bisnis' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = [
            'email_bisnis' => $request->email_bisnis,
            'password' => $request->password,
        ];

        // Coba login menggunakan guard 'mitra'
        if (Auth::guard('mitra')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect ke dashboard mitra
            return redirect()->intended(route('mitra.dashboard'));
        }

        return back()->withErrors([
            'email_bisnis' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.',
        ])->onlyInput('email_bisnis');
    }

    /**
     * Menangani permintaan logout.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('mitra')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke halaman login mitra
        return redirect()->route('mitra.login');
    }
}
