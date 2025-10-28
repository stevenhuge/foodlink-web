<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Pastikan use statement ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider; // Jika Anda masih menggunakannya untuk HOME


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // Sesuaikan jika path berbeda
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // --- Redirect Logic ---
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('admin/*')) { return route('admin.login'); }
            if ($request->is('mitra/*')) { return route('mitra.login'); }
            // return route('login'); // Redirect default untuk user biasa
        });

        $middleware->redirectUsersTo(function (Request $request) {
            if (Auth::guard('admin')->check()) { return route('admin.dashboard'); }
            if (Auth::guard('mitra')->check()) { return route('mitra.dashboard'); }
            // return RouteServiceProvider::HOME; // Redirect default untuk user biasa
            return '/'; // Atau langsung ke root jika tidak pakai RouteServiceProvider
        });
        // --- End Redirect Logic ---


        // --- Pendaftaran Alias Middleware ---
        $middleware->alias([
            'role.admin' => \App\Http\Middleware\RoleMiddleware::class,
            'mitra.active' => \App\Http\Middleware\EnsureMitraIsActive::class, // <-- TAMBAHKAN INI
        ]);
        // ------------------------------------

        // Opsional: Validasi CSRF (biasanya sudah ada)
        // $middleware->validateCsrfTokens(except: [
        //     // 'api/*' // Contoh
        // ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ... Konfigurasi Exception Handling ...
    })->create();
