<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// --- TAMBAHKAN 'USE' STATMENTS YANG BENAR INI ---
// Ini akan memperbaiki error "Class ... not found"
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\TransaksiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/produk', [ProdukController::class, 'index']);
Route::get('/produk/{produk}', [ProdukController::class, 'show']);

// Rute Terproteksi (User harus login dan mengirim token)
Route::middleware('auth:sanctum')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Profil User
    Route::get('/user/profile', function (Request $request) {
        return $request->user();
    });

    // Proses Checkout / Transaksi
    Route::post('/transaksi/checkout', [TransaksiController::class, 'store']);
    Route::get('/transaksi/riwayat', [TransaksiController::class, 'riwayat']);
});

