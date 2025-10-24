<?php

// --- CONTROLLER UNTUK ADMIN ---
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\MitraVerificationController;

// --- CONTROLLER UNTUK MITRA ---
use App\Http\Controllers\Mitra\Auth\RegisterController;
use App\Http\Controllers\Mitra\Auth\AuthenticatedSessionController as MitraLoginController;
use App\Http\Controllers\Mitra\DashboardController as MitraDashboardController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| GRUP RUTE ADMIN
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    // --- Rute Tamu (Login) ---
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store']);
    });

    // --- Rute Admin Terproteksi (WAJIB LOGIN) ---
    Route::middleware('auth:admin')->group(function () {

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

        // Dashboard (Bisa diakses Admin & SuperAdmin)
        Route::get('dashboard', [DashboardController::class, 'index'])
            ->middleware('role.admin:Admin,SuperAdmin')
            ->name('dashboard');

        // Manajemen Admin (Hanya SuperAdmin)
        Route::middleware('role.admin:SuperAdmin')->group(function () {
            Route::resource('admins', AdminManagementController::class);
        });

        /*
        |------------------------------------------------------------------
        | GRUP MANAJEMEN MITRA (DIATUR ULANG)
        |------------------------------------------------------------------
        */
        Route::prefix('mitra')->name('mitra.')->group(function() {

            // (Admin & SuperAdmin)
            Route::get('/', [MitraVerificationController::class, 'index'])
                ->middleware('role.admin:Admin,SuperAdmin')
                ->name('index');

            // (Hanya SuperAdmin) - EDIT
            // Rute ini harus di atas 'show'
            Route::get('/{mitra}/edit', [MitraVerificationController::class, 'edit'])
                ->middleware('role.admin:SuperAdmin')
                ->name('edit');

            // (Admin & SuperAdmin) - SHOW/DETAIL
            Route::get('/{mitra}', [MitraVerificationController::class, 'show'])
                ->middleware('role.admin:Admin,SuperAdmin')
                ->name('show');

            // (Hanya SuperAdmin) - UPDATE
            Route::put('/{mitra}', [MitraVerificationController::class, 'update'])
                ->middleware('role.admin:SuperAdmin')
                ->name('update');

            // (Hanya SuperAdmin) - DELETE
            Route::delete('/{mitra}', [MitraVerificationController::class, 'destroy'])
                ->middleware('role.admin:SuperAdmin')
                ->name('destroy');

            // (Admin & SuperAdmin) - VERIFIKASI
            Route::patch('/{mitra}/verify', [MitraVerificationController::class, 'verify'])
                ->middleware('role.admin:Admin,SuperAdmin')
                ->name('verify');

            // (Admin & SuperAdmin) - REJECT
            Route::patch('/{mitra}/reject', [MitraVerificationController::class, 'reject'])
                ->middleware('role.admin:Admin,SuperAdmin')
                ->name('reject');
        });
    });
});


/*
|--------------------------------------------------------------------------
| GRUP RUTE MITRA
|--------------------------------------------------------------------------
*/
Route::prefix('mitra')->name('mitra.')->group(function () {

    // Rute Tamu (Login & Register)
    Route::middleware('guest:mitra')->group(function () {
        Route::get('register', [RegisterController::class, 'create'])->name('register');
        Route::post('register', [RegisterController::class, 'store']);
        Route::get('login', [MitraLoginController::class, 'create'])->name('login');
        Route::post('login', [MitraLoginController::class, 'store']);
    });

    // Rute Mitra Terproteksi (Wajib Login)
    Route::middleware('auth:mitra')->group(function () {
        Route::post('logout', [MitraLoginController::class, 'destroy'])->name('logout');
        Route::get('dashboard', [MitraDashboardController::class, 'index'])->name('dashboard');
    });
});
