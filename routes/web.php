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
use App\Http\Controllers\Mitra\Auth\AuthenticatedSessionController as MitraLoginController;
use App\Http\Controllers\Mitra\DashboardController as MitraDashboardController;
use App\Http\Controllers\Mitra\ProdukController;
use App\Http\Controllers\Mitra\BarterController;
use App\Http\Controllers\Mitra\ProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Mitra\RiwayatTransaksiController; // Pastikan ini ada
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

/*
|--------------------------------------------------------------------------
| GRUP RUTE ADMIN
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // ... (Semua Rute Admin Anda, tidak perlu diubah) ...
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store']);
    });
    Route::middleware('auth:admin')->group(function () {
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
        Route::get('dashboard', [DashboardController::class, 'index'])->middleware('role.admin:Admin,SuperAdmin')->name('dashboard');
        Route::middleware('role.admin:SuperAdmin')->group(function () {
            Route::resource('admins', AdminManagementController::class);
            Route::get('kategori-usaha', [KategoriUsahaController::class, 'index'])->name('kategori-usaha.index');
            Route::get('kategori-usaha/{kategori_usaha}/edit', [KategoriUsahaController::class, 'edit'])->name('kategori-usaha.edit');
            Route::put('kategori-usaha/{kategori_usaha}', [KategoriUsahaController::class, 'update'])->name('kategori-usaha.update');
            Route::delete('kategori-usaha/{kategori_usaha}', [KategoriUsahaController::class, 'destroy'])->name('kategori-usaha.destroy');
            Route::delete('mitra/{mitra}', [MitraVerificationController::class, 'destroy'])->name('mitra.destroy');
            Route::resource('alasan-blokir', AlasanBlokirController::class)->except(['show']);
            Route::get('pemasukan', [App\Http\Controllers\SuperAdmin\PemasukanController::class, 'index'])->name('pemasukan.index');

            // CRUD Rekening Bank milik SuperAdmin
            Route::resource('rekening-bank', App\Http\Controllers\SuperAdmin\RekeningBankController::class);

            // Proses request penarikan dana oleh SuperAdmin (untuk dirinya sendiri)
            Route::post('pemasukan/tarik', [App\Http\Controllers\SuperAdmin\PemasukanController::class, 'storePenarikan'])
                ->name('pemasukan.tarik');

            // Halaman untuk me-review penarikan dana dari para MITRA
            Route::get('review-penarikan', [App\Http\Controllers\SuperAdmin\ReviewPenarikanController::class, 'index'])
                ->name('review.penarikan.index');
            Route::patch('review-penarikan/{penarikan}', [App\Http\Controllers\SuperAdmin\ReviewPenarikanController::class, 'update'])
                ->name('review.penarikan.update');
        });
        Route::middleware('role.admin:Admin,SuperAdmin')->group(function () {
             Route::get('kategori-usaha', [KategoriUsahaController::class, 'index'])->name('kategori-usaha.index');
             Route::get('kategori-usaha/create', [KategoriUsahaController::class, 'create'])->name('kategori-usaha.create');
             Route::post('kategori-usaha', [KategoriUsahaController::class, 'store'])->name('kategori-usaha.store');
             Route::get('kategori-usaha/{kategori_usaha}/edit', [KategoriUsahaController::class, 'edit'])->name('kategori-usaha.edit');
             Route::put('kategori-usaha/{kategori_usaha}', [KategoriUsahaController::class, 'update'])->name('kategori-usaha.update');
             Route::delete('kategori-usaha/{kategori_usaha}', [KategoriUsahaController::class, 'destroy'])->name('kategori-usaha.destroy');
             Route::get('/penyanggahan', [App\Http\Controllers\Admin\PenyanggahanController::class, 'index'])->name('penyanggahan.index');
             Route::put('/penyanggahan/{id}', [App\Http\Controllers\Admin\PenyanggahanController::class, 'update'])->name('penyanggahan.update');
        });
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
        Route::prefix('users')->name('users.')->middleware('role.admin:Admin,SuperAdmin')->group(function() {
            Route::get('/', [App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('index');
            Route::get('/{user}/edit', [App\Http\Controllers\Admin\UserManagementController::class, 'edit'])->name('edit');
            Route::put('/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'update'])->name('update');
            Route::patch('/{user}/block', [App\Http\Controllers\Admin\UserManagementController::class, 'block'])->name('block');
            Route::patch('/{user}/unblock', [App\Http\Controllers\Admin\UserManagementController::class, 'unblock'])->name('unblock');
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
        Route::get('/test-email', function () {
    try {
        Mail::raw('Halo, ini tes email dari Foodlink apakah masuk Mailtrap?', function ($msg) {
            $msg->to('tes@mitra.com') // Ganti dengan email tujuan sembarang
                ->subject('Tes Koneksi Mailtrap');
        });
        return 'Email berhasil dikirim! Cek Mailtrap sekarang.';
    } catch (\Exception $e) {
        return 'Gagal kirim: ' . $e->getMessage();
    }
});
        Route::get('register', [RegisterController::class, 'create'])->name('register');
        Route::post('register', [RegisterController::class, 'store']);
        Route::get('login', [MitraLoginController::class, 'create'])->name('login');
        Route::post('login', [MitraLoginController::class, 'store']);

        Route::get('/penyanggahan-akun', [App\Http\Controllers\Mitra\BlokirController::class, 'publicIndex'])->name('blokir.public');
        Route::post('/penyanggahan-akun', [App\Http\Controllers\Mitra\BlokirController::class, 'publicStore'])->name('blokir.public.store');

        Route::get('/forgot-password', [App\Http\Controllers\Mitra\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('/forgot-password', [App\Http\Controllers\Mitra\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('/reset-password/{token}', [App\Http\Controllers\Mitra\ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('/reset-password', [App\Http\Controllers\Mitra\ForgotPasswordController::class, 'reset'])->name('password.update');
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

        // Rute Riwayat Transaksi
        Route::get('riwayat-transaksi', [RiwayatTransaksiController::class, 'index'])
             ->name('riwayat.index');
        Route::get('riwayat-transaksi/{id}', [RiwayatTransaksiController::class, 'show'])
             ->name('riwayat.show');

        Route::get('pesanan', [RiwayatTransaksiController::class, 'index2'])
             ->name('pesanan.index');

        // === TAMBAHAN BARU: RUTE UNTUK KONFIRMASI ===
        Route::patch('riwayat-transaksi/{id}/konfirmasi', [RiwayatTransaksiController::class, 'konfirmasi'])
             ->name('riwayat.konfirmasi');

        Route::patch('riwayat-transaksi/{id}/batalkan', [RiwayatTransaksiController::class, 'batalkan'])
             ->name('riwayat.batalkan');

        Route::patch('riwayat-transaksi/{id}/batalkan', [RiwayatTransaksiController::class, 'batalkan'])
             ->name('riwayat.batalkan');

        Route::get('riwayat-transaksi/export/excel', [RiwayatTransaksiController::class, 'exportExcel'])
             ->name('riwayat.export.excel');

        Route::get('pemasukan', [App\Http\Controllers\Mitra\PemasukanController::class, 'index'])
         ->name('pemasukan.index');

        // CRUD Rekening Bank milik Mitra
        Route::resource('rekening-bank', App\Http\Controllers\Mitra\RekeningBankController::class);

        // Proses request penarikan dana oleh Mitra
        Route::post('pemasukan/tarik', [App\Http\Controllers\Mitra\PemasukanController::class, 'storePenarikan'])
            ->name('pemasukan.tarik');

    });
});
