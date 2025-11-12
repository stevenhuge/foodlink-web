<?php
// routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// --- TAMBAHKAN 'USE' STATMENTS YANG BENAR INI ---
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\TransaksiController;
use App\Http\Controllers\Api\WalletController; // <-- Pastikan ini ada

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// == Fitur 1: Auth (Login & Register) ==
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// == Fitur 3: Melihat Produk (Publik) ==
Route::get('/produk', [ProdukController::class, 'index']);
Route::get('/produk/{produk}', [ProdukController::class, 'show']); // Gunakan route model binding

// == Rute Terproteksi (Wajib Login/Punya Token) ==
Route::middleware('auth:sanctum')->group(function () {

    // Auth & Profil
    Route::post('/logout', [AuthController::class, 'logout']);

    // === PERBAIKAN DI SINI ===
    Route::get('/profile', [AuthController::class, 'profile']); // Ganti . menjadi ::
    // =========================

    // == Fitur 5: Membayar (Top-Up Poin) ==
    Route::post('/wallet/topup', [WalletController::class, 'requestTopup']);

    // == Fitur 4: Membeli Produk (Checkout pakai Poin) ==
    Route::post('/transaksi/checkout', [TransaksiController::class, 'store']);
    Route::get('/transaksi/riwayat', [TransaksiController::class, 'riwayat']);
});

// == Rute Eksternal (Untuk Payment Gateway) ==
// Endpoint ini dipanggil oleh server Midtrans, bukan oleh Android
Route::post('/payment/webhook', [WalletController::class, 'webhookHandler'])->name('payment.webhook');
