<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles (Contoh: 'SuperAdmin', 'Admin')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Ambil admin yang sedang login
        // Kita harus spesifik menggunakan guard 'admin'
        $admin = Auth::guard('admin')->user();

        // 2. Jika tidak ada admin yang login (seharusnya ditangani auth middleware, tapi untuk jaga-jaga)
        if (! $admin) {
            abort(403, 'Unauthorized action.');
        }

        // 3. Bersihkan data role dari database (untuk jaga-jaga dari karakter aneh)
        $adminRole = trim($admin->role);

        // 4. Cek apakah role admin ada di dalam daftar role yang diizinkan
        foreach ($roles as $role) {
            if ($adminRole === $role) {
                // Jika cocok, izinkan request
                return $next($request);
            }
        }

        // 5. Jika tidak ada role yang cocok, tolak
        abort(403, 'Unauthorized action.');
    }
}
