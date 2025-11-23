<?php
// app/Http/Middleware/EnsureMitraIsActive.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureMitraIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('mitra')->check()) {
            $mitra = Auth::guard('mitra')->user();

            if ($mitra->status_akun === 'Diblokir') {

                // 1. Logout paksa
                Auth::guard('mitra')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // 2. Siapkan Pesan
                $mitra->loadMissing('alasanBlokir');
                $reasonText = $mitra->alasanBlokir?->alasan_text;

                $errorMessage = '<strong>Akun Anda telah diblokir.</strong>';
                if ($reasonText) {
                    $errorMessage .= '<br>Alasan: ' . $reasonText;
                }

                // 3. Tambahkan Link ke Halaman Sanggah (Publik)
                // Pastikan route 'mitra.blokir.public' sudah dibuat nanti
                $urlSanggah = route('mitra.blokir.public');
                $errorMessage .= '<br><br>Silahkan lakukan penyanggahan akun dengan klik <a href="'.$urlSanggah.'" class="alert-link">Laman Penyanggahan</a>';

                // 4. Redirect dengan pesan error (HTML)
                return redirect()->route('mitra.login')
                                 ->with('error_html', $errorMessage);
                                 // Kita pakai key khusus 'error_html' agar dibedakan di View
            }
        }

        return $next($request);
    }
}
