<?php

// --- CONTROLLER UNTUK ADMIN ---
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\MitraVerificationController;
use App\Http\Controllers\Admin\KategoriUsahaController;
use App\Http\Controllers\Admin\AlasanBlokirController;

// --- CONTROLLER UNTUK MITRA ---
use App\Http\Controllers\Mitra\Auth\RegisterController;
use App\Http\Controllers\Mitra\Auth\AuthenticatedSessionController as MitraLoginController; // Alias tetap
use App\Http\Controllers\Mitra\DashboardController as MitraDashboardController;
use App\Http\Controllers\Mitra\ProdukController;
use App\Http\Controllers\Mitra\BarterController;
use App\Http\Controllers\Mitra\ProfileController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| GRUP RUTE ADMIN
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    // --- Rute Tamu Admin (Login) ---
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store']);
    });

    // --- Rute Admin Terproteksi (WAJIB LOGIN) ---
    Route::middleware('auth:admin')->group(function () {
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
        Route::get('dashboard', [DashboardController::class, 'index'])->middleware('role.admin:Admin,SuperAdmin')->name('dashboard');

        // GRUP KHUSUS SUPERADMIN
        Route::middleware('role.admin:SuperAdmin')->group(function () {
            Route::resource('admins', AdminManagementController::class);
            Route::get('kategori-usaha', [KategoriUsahaController::class, 'index'])->name('kategori-usaha.index');
            Route::get('kategori-usaha/{kategori_usaha}/edit', [KategoriUsahaController::class, 'edit'])->name('kategori-usaha.edit');
            Route::put('kategori-usaha/{kategori_usaha}', [KategoriUsahaController::class, 'update'])->name('kategori-usaha.update');
            Route::delete('kategori-usaha/{kategori_usaha}', [KategoriUsahaController::class, 'destroy'])->name('kategori-usaha.destroy');
            Route::delete('mitra/{mitra}', [MitraVerificationController::class, 'destroy'])->name('mitra.destroy');
            Route::resource('alasan-blokir', AlasanBlokirController::class)->except(['show']);
        });

        // GRUP ADMIN & SUPERADMIN
        Route::middleware('role.admin:Admin,SuperAdmin')->group(function () {
             // === SEMUA RUTE KATEGORI USAHA SEKARANG DI SINI ===
             Route::get('kategori-usaha', [KategoriUsahaController::class, 'index'])->name('kategori-usaha.index'); // <-- Pindah ke sini
             Route::get('kategori-usaha/create', [KategoriUsahaController::class, 'create'])->name('kategori-usaha.create');
             Route::post('kategori-usaha', [KategoriUsahaController::class, 'store'])->name('kategori-usaha.store');
             Route::get('kategori-usaha/{kategori_usaha}/edit', [KategoriUsahaController::class, 'edit'])->name('kategori-usaha.edit'); // <-- Pindah ke sini
             Route::put('kategori-usaha/{kategori_usaha}', [KategoriUsahaController::class, 'update'])->name('kategori-usaha.update'); // <-- Pindah ke sini
             Route::delete('kategori-usaha/{kategori_usaha}', [KategoriUsahaController::class, 'destroy'])->name('kategori-usaha.destroy'); // <-- Pindah ke sini
             // ===============================================
        });

        // GRUP MANAJEMEN MITRA (ADMIN & SUPERADMIN)
        Route::prefix('mitra')->name('mitra.')->middleware('role.admin:Admin,SuperAdmin')->group(function() {
            Route::get('/', [MitraVerificationController::class, 'index'])->name('index');
            Route::get('/{mitra}', [MitraVerificationController::class, 'show'])->name('show');
            Route::patch('/{mitra}/verify', [MitraVerificationController::class, 'verify'])->name('verify');
            Route::patch('/{mitra}/reject', [MitraVerificationController::class, 'reject'])->name('reject');
            Route::get('/{mitra}/edit', [MitraVerificationController::class, 'edit'])->name('edit');
            Route::put('/{mitra}', [MitraVerificationController::class, 'update'])->name('update');
            Route::patch('/{mitra}/block', [MitraVerificationController::class, 'block'])->name('block');
            Route::patch('/{mitra}/unblock', [MitraVerificationController::class, 'unblock'])->name('unblock');
        });
    });
});


/*
|--------------------------------------------------------------------------
| GRUP RUTE MITRA
|--------------------------------------------------------------------------
*/
Route::prefix('mitra')->name('mitra.')->group(function () {

    // --- Rute Tamu Mitra (Login & Register) ---
    Route::middleware('guest:mitra')->group(function () {
        Route::get('register', [RegisterController::class, 'create'])->name('register');
        Route::post('register', [RegisterController::class, 'store']);
        // Pastikan kedua rute login ini ada dan benar
        Route::get('login', [MitraLoginController::class, 'create'])->name('login');
        Route::post('login', [MitraLoginController::class, 'store']);
    });

    // --- Rute Mitra Terproteksi (Wajib Login & Akun Aktif) ---
    Route::middleware(['auth:mitra', 'mitra.active'])->group(function () {
        Route::post('logout', [MitraLoginController::class, 'destroy'])->name('logout');
        Route::get('dashboard', [MitraDashboardController::class, 'index'])->name('dashboard');

        // Rute Manajemen Produk (Mitra)
        Route::resource('produk', ProdukController::class);
        Route::patch('produk/{produk}/publish', [ProdukController::class, 'publish'])->name('produk.publish');
        Route::patch('produk/{produk}/unpublish', [ProdukController::class, 'unpublish'])->name('produk.unpublish');

        // Rute Barter
        Route::prefix('barter')->name('barter.')->group(function () {
            Route::get('/', [BarterController::class, 'index'])->name('index');
            Route::get('ajukan/{produk}', [BarterController::class, 'create'])->name('create');
            Route::post('ajukan/{produk}', [BarterController::class, 'store'])->name('store');
            Route::get('inbox', [BarterController::class, 'inbox'])->name('inbox');
            Route::patch('{barter}/accept', [BarterController::class, 'accept'])->name('accept');
            Route::patch('{barter}/reject', [BarterController::class, 'reject'])->name('reject');
            Route::patch('{barter}/cancel', [BarterController::class, 'cancel'])->name('cancel');
        });

        // Rute Edit Profil Mitra
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    });
});
