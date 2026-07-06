<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// --- DAFTAR CONTROLLER ---
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\TransaksiController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\MitraController;

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

// --- WEBHOOK & PAYMENT GATEWAY ---
Route::post('/payment/webhook', [WalletController::class, 'webhookHandler'])->name('payment.webhook');

// Cukup gunakan satu route match ini saja untuk Midtrans, yang Route::post sebelumnya dihapus agar tidak bentrok
Route::match(['get', 'post'], '/transaksi/midtrans-callback', [TransaksiController::class, 'midtransCallback']);


// ========================================================================
// 2. RUTE TERPROTEKSI (Wajib Login / Punya Token)
// ========================================================================
Route::middleware('auth:sanctum')->group(function () {

    // --- User Profile & Logout ---
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    // Update profil user berdasarkan id nya
    Route::put('/profile/update', [AuthController::class, 'updateProfile']);

    // --- Mitra / Toko ---
    // Mengambil daftar toko untuk ditampilkan di menu "Toko" Android
    Route::get('/mitra', [MitraController::class, 'index']);
    Route::get('/mitra/{id}/produk', [ProdukController::class, 'getByMitra']);

    // --- Wallet / Dompet (Top Up Poin) ---
    Route::post('/wallet/topup', [WalletController::class, 'requestTopup']);

    // --- Transaksi (Belanja) ---
    // Semua duplikat sudah dihapus, cukup panggil masing-masing satu kali
    Route::post('/transaksi/checkout', [TransaksiController::class, 'checkout']);
    Route::post('/transaksi/checkout-poin', [TransaksiController::class, 'checkoutPoin']);
    Route::get('/transaksi/history', [TransaksiController::class, 'riwayat']);
    Route::get('/transaksi/{kode_transaksi}', [TransaksiController::class, 'show']);
    Route::get('/transaksi/{kode_transaksi}/status', [TransaksiController::class, 'cekStatus']);
    Route::post('/transaksi/{kode_transaksi}/bayar-poin', [TransaksiController::class, 'bayarDenganPoin']);

});