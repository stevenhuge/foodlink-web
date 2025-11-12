<?php
// app/Http/Controllers/Api/ProdukController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
// Hapus Resource jika tidak dipakai
// use App\Http\Resources\ProdukResource;
// use App\Http\Resources\ProdukCollection;

class ProdukController extends Controller
{
    /**
     * Fitur 3: Menampilkan daftar produk
     * Endpoint: GET /api/produk
     */
    public function index()
    {
        $produk = Produk::where('status_produk', 'Tersedia')
                        ->where('stok_tersisa', '>', 0)
                        // --- MODIFIKASI: Filter Mitra Aktif ---
                        ->whereHas('mitra', function($query) {
                            $query->where('status_verifikasi', 'Verified')
                                  ->where('status_akun', 'Aktif');
                        })
                        // ------------------------------------
                        ->with('mitra:mitra_id,nama_mitra,alamat') // Optimasi
                        ->orderBy('created_at', 'desc')
                        ->get();

        // return new ProdukCollection($produk); // Jika pakai Resource
        return response()->json($produk);
    }

    /**
     * Fitur 3: Menampilkan detail produk
     * Endpoint: GET /api/produk/{produk}
     */
    public function show(Produk $produk)
    {
        // --- MODIFIKASI: Cek Mitra Aktif ---
        // Pastikan relasi mitra sudah di-load
        $produk->load('mitra');

        if ($produk->mitra->status_verifikasi != 'Verified' || $produk->mitra->status_akun != 'Aktif' || $produk->status_produk != 'Tersedia') {
             return response()->json(['message' => 'Produk tidak ditemukan atau tidak tersedia'], 404);
        }
        // ----------------------------------

        // return new ProdukResource($produk); // Jika pakai Resource
        return response()->json($produk);
    }

    // ... (fungsi store, update, destroy tidak diubah) ...
}
