<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// --- DAFTAR CONTROLLER ---
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\TransaksiController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\MitraController; // <--- Baru ditambahkan

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ========================================================================
// 1. RUTE PUBLIK (Tidak perlu login)
// ========================================================================

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- PRODUK ---
// 1. Rute Flashsale (TARUH PALING ATAS di bagian Produk)
Route::get('/produk/flashsale', [ProdukController::class, 'flashSale']);
Route::get('/produk/donasi', [ProdukController::class, 'produkDonasi']);

// 2. Daftar semua produk
Route::get('/produk', [ProdukController::class, 'index']);

// 3. Detail produk (Parameter dinamis ditaruh paling bawah agar tidak bentrok)
Route::get('/produk/{produk}', [ProdukController::class, 'show']);


// Payment Gateway Webhook
Route::post('/payment/webhook', [WalletController::class, 'webhookHandler'])->name('payment.webhook');

// ========================================================================
// 2. RUTE TERPROTEKSI (Wajib Login / Punya Token)
// ========================================================================
Route::middleware('auth:sanctum')->group(function () {

    // --- User Profile & Logout ---
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    // Update profil user berdasarkan id nya
    Route::put('/profile/update', [AuthController::class, 'updateProfile']);

    // --- Mitra / Toko (BARU) ---
    // Mengambil daftar toko untuk ditampilkan di menu "Toko" Android
    Route::get('/mitra', [MitraController::class, 'index']);
    Route::get('/mitra/{id}/produk', [ProdukController::class, 'getByMitra']);

    // --- Wallet / Dompet (Top Up Poin) ---
    Route::post('/wallet/topup', [WalletController::class, 'requestTopup']);

    // --- Transaksi (Belanja) ---
    Route::post('/transaksi/checkout', [TransaksiController::class, 'store']);
    Route::get('/transaksi/history', [TransaksiController::class, 'riwayat']);
    Route::get('/transaksi/{kode_transaksi}', [TransaksiController::class, 'show']);

});
