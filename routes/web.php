<?php

use Illuminate\Support\Facades\Route;

// --- CONTROLLER ADMIN ---
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\MitraVerificationController;
use App\Http\Controllers\Admin\KategoriUsahaController;
use App\Http\Controllers\Admin\AlasanBlokirController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Mitra\BlokirController;
use App\Http\Controllers\Admin\PenyanggahanController;

// --- CONTROLLER SUPER ADMIN (KHUSUS) ---
use App\Http\Controllers\SuperAdmin\PemasukanController as SuperPemasukanController;
use App\Http\Controllers\SuperAdmin\RekeningBankController as SuperRekeningController;
use App\Http\Controllers\SuperAdmin\ReviewPenarikanController;

// --- CONTROLLER MITRA ---
use App\Http\Controllers\Mitra\Auth\RegisterController;
use App\Http\Controllers\Mitra\Auth\AuthenticatedSessionController as MitraLoginController;
use App\Http\Controllers\Mitra\DashboardController as MitraDashboardController;
use App\Http\Controllers\Mitra\ProdukController;
use App\Http\Controllers\Mitra\BarterController;
use App\Http\Controllers\Mitra\ProfileController;
use App\Http\Controllers\Mitra\RiwayatTransaksiController;
use App\Http\Controllers\Mitra\PemasukanController as MitraPemasukanController;
use App\Http\Controllers\Mitra\RekeningBankController as MitraRekeningController;
use App\Http\Controllers\Mitra\ForgotPasswordController; // <-- Jangan lupa ini

// --- CONTROLLER UMUM ---
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Artisan;

Route::get('/migrate', function () {
    Artisan::call('migrate', ['--force' => true]);
    return "Database migration success!";
});
/*
|--------------------------------------------------------------------------
| RUTE UMUM (LANDING PAGE)
|--------------------------------------------------------------------------
*/
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

/*
|--------------------------------------------------------------------------
| GRUP RUTE ADMIN
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    // 1. Rute Tamu (Belum Login)
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store']);
    });

    // 2. Rute Terproteksi (Sudah Login)
    Route::middleware('auth:admin')->group(function () {
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Pengaturan Sistem (Pajak & Biaya) - Bisa diakses Admin & SuperAdmin
        Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::put('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');

        // --- KHUSUS SUPER ADMIN ---
        Route::middleware('role.admin:SuperAdmin')->group(function () {
            // Manajemen Admin Lain
            Route::resource('admins', AdminManagementController::class);

            // Alasan Blokir
            Route::resource('alasan-blokir', AlasanBlokirController::class)->except(['show']);

            // Keuangan SuperAdmin
            Route::get('pemasukan', [SuperPemasukanController::class, 'index'])->name('pemasukan.index');
            Route::resource('rekening-bank', SuperRekeningController::class);
            Route::post('pemasukan/tarik', [SuperPemasukanController::class, 'storePenarikan'])->name('pemasukan.tarik');

            // Review Penarikan Mitra
            Route::get('review-penarikan', [ReviewPenarikanController::class, 'index'])->name('review.penarikan.index');
            Route::patch('review-penarikan/{penarikan}', [ReviewPenarikanController::class, 'update'])->name('review.penarikan.update');
        });

        // --- ADMIN & SUPER ADMIN (BISA AKSES SEMUA INI) ---
        Route::middleware('role.admin:Admin,SuperAdmin')->group(function () {

            // Kategori Usaha (Saya gabungkan di sini agar tidak duplikat)
            Route::resource('kategori-usaha', KategoriUsahaController::class);

            // Penyanggahan
            Route::get('/penyanggahan', [PenyanggahanController::class, 'index'])->name('penyanggahan.index');
            Route::put('/penyanggahan/{id}', [PenyanggahanController::class, 'update'])->name('penyanggahan.update');

            // Manajemen Mitra (Verifikasi & Blokir)
            Route::prefix('mitra')->name('mitra.')->group(function() {
                Route::get('/', [MitraVerificationController::class, 'index'])->name('index');
                Route::get('/{mitra}', [MitraVerificationController::class, 'show'])->name('show');
                Route::patch('/{mitra}/verify', [MitraVerificationController::class, 'verify'])->name('verify');
                Route::patch('/{mitra}/reject', [MitraVerificationController::class, 'reject'])->name('reject');
                Route::get('/{mitra}/edit', [MitraVerificationController::class, 'edit'])->name('edit'); // Opsional
                Route::put('/{mitra}', [MitraVerificationController::class, 'update'])->name('update'); // Opsional
                Route::patch('/{mitra}/block', [MitraVerificationController::class, 'block'])->name('block');
                Route::patch('/{mitra}/unblock', [MitraVerificationController::class, 'unblock'])->name('unblock');

                // Tambahan: Hapus Mitra (Hanya SuperAdmin sebaiknya, tapi ikuti role grup ini)
                Route::delete('/{mitra}', [MitraVerificationController::class, 'destroy'])->name('destroy');
            });

            // Manajemen User
            Route::prefix('users')->name('users.')->group(function() {
                Route::get('/', [UserManagementController::class, 'index'])->name('index');
                Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
                Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
                Route::patch('/{user}/block', [UserManagementController::class, 'block'])->name('block');
                Route::patch('/{user}/unblock', [UserManagementController::class, 'unblock'])->name('unblock');
            });
        });
    });
});

/*
|--------------------------------------------------------------------------
| GRUP RUTE MITRA
|--------------------------------------------------------------------------
*/
Route::prefix('mitra')->name('mitra.')->group(function () {

    // 1. Rute Tamu Mitra (Login, Register, Lupa Password) - [INI YANG ANDA KURANG]
    Route::middleware('guest:mitra')->group(function () {
        Route::get('akun-diblokir', [BlokirController::class, 'publicIndex'])->name('blokir.public');

    // Menangani pengiriman data sanggahan (POST) - MENGATASI ERROR mitra.blokir.public.store
        Route::post('akun-diblokir', [BlokirController::class, 'publicStore'])->name('blokir.public.store');

        // Login
        Route::get('login', [MitraLoginController::class, 'create'])->name('login');
        Route::post('login', [MitraLoginController::class, 'store']);

        // Register
        Route::get('register', [RegisterController::class, 'create'])->name('register');
        Route::post('register', [RegisterController::class, 'store']);

        // Lupa Password (Yang baru kita buat)
        Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');
    });

    // 2. Rute Mitra Terproteksi (Wajib Login & Akun Aktif)
    Route::middleware(['auth:mitra', 'mitra.active'])->group(function () {
        Route::post('logout', [MitraLoginController::class, 'destroy'])->name('logout');
        Route::get('dashboard', [MitraDashboardController::class, 'index'])->name('dashboard');

        // Manajemen Produk
        Route::resource('produk', ProdukController::class);
        Route::patch('produk/{produk}/publish', [ProdukController::class, 'publish'])->name('produk.publish');
        Route::patch('produk/{produk}/unpublish', [ProdukController::class, 'unpublish'])->name('produk.unpublish');

        // Barter
        Route::prefix('barter')->name('barter.')->group(function () {
            Route::get('/', [BarterController::class, 'index'])->name('index');
            Route::get('ajukan/{produk}', [BarterController::class, 'create'])->name('create');
            Route::post('ajukan/{produk}', [BarterController::class, 'store'])->name('store');
            Route::get('inbox', [BarterController::class, 'inbox'])->name('inbox');
            Route::patch('{barter}/accept', [BarterController::class, 'accept'])->name('accept');
            Route::patch('{barter}/reject', [BarterController::class, 'reject'])->name('reject');
            Route::patch('{barter}/cancel', [BarterController::class, 'cancel'])->name('cancel');
        });

        // Edit Profil
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');

        // Riwayat Transaksi & Pesanan
        Route::get('riwayat-transaksi', [RiwayatTransaksiController::class, 'index'])->name('riwayat.index');
        Route::get('riwayat-transaksi/{id}', [RiwayatTransaksiController::class, 'show'])->name('riwayat.show');
        Route::get('pesanan', [RiwayatTransaksiController::class, 'index2'])->name('pesanan.index');

        // Aksi Transaksi
        Route::patch('riwayat-transaksi/{id}/konfirmasi', [RiwayatTransaksiController::class, 'konfirmasi'])->name('riwayat.konfirmasi');
        Route::patch('riwayat-transaksi/{id}/batalkan', [RiwayatTransaksiController::class, 'batalkan'])->name('riwayat.batalkan'); // Cukup satu kali saja

        // Export Excel
        Route::get('riwayat-transaksi/export/excel', [RiwayatTransaksiController::class, 'exportExcel'])->name('riwayat.export.excel');

        // Keuangan Mitra
        Route::get('pemasukan', [MitraPemasukanController::class, 'index'])->name('pemasukan.index');
        Route::resource('rekening-bank', MitraRekeningController::class);
        Route::post('pemasukan/tarik', [MitraPemasukanController::class, 'storePenarikan'])->name('pemasukan.tarik');
    });
});
