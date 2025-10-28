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
        // Cek apakah user adalah Mitra dan sedang login
        if (Auth::guard('mitra')->check()) {
            $mitra = Auth::guard('mitra')->user();

            // Jika statusnya Diblokir
            if ($mitra->status_akun === 'Diblokir') {
                Auth::guard('mitra')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Ambil alasan dari relasi
                $mitra->loadMissing('alasanBlokir'); // Pastikan relasi diload
                $reasonText = $mitra->alasanBlokir?->alasan_text;
                $errorMessage = 'Akun Anda telah diblokir.';
                if ($reasonText) {
                    $errorMessage .= ' Alasan: ' . $reasonText;
                }

                // Redirect ke login dengan pesan error
                return redirect()->route('mitra.login')
                                 ->withErrors(['email_bisnis' => $errorMessage]);
                                 // ->withInput($request->only('email_bisnis')); // Opsional: isi ulang email
            }
        }

        // Jika aktif atau bukan mitra, lanjutkan request
        return $next($request);
    }
}
