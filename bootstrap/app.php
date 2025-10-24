<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Pastikan use statement ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // --- Ini kode untuk memperbaiki redirect (sudah benar) ---
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('admin/*')) {
                return route('admin.login');
            }
            if ($request->is('mitra/*')) {
                return route('mitra.login');
            }
            return route('login');
        });

        $middleware->redirectUsersTo(function (Request $request) {
            if (Auth::guard('admin')->check()) {
                 return route('admin.dashboard');
            }
            if (Auth::guard('mitra')->check()) {
                 return route('mitra.dashboard');
            }
            return RouteServiceProvider::HOME;
        });


        // --- INI PERBAIKANNYA ---
        // Ganti 'withAliases' (plural) menjadi 'alias' (singular)
        $middleware->alias([
            'role.admin' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
        // -------------------------

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
