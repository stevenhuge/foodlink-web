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

    /**
     * Fitur Tambahan: Menampilkan produk berdasarkan ID Mitra
     * Endpoint: GET /api/mitra/{id}/produk
     */
    public function getByMitra($id)
    {
        $produk = Produk::where('mitra_id', $id)
                        ->where('status_produk', 'Tersedia') // Hanya yang tersedia
                        ->where('stok_tersisa', '>', 0)      // Hanya yang ada stok
                        ->orderBy('created_at', 'desc')
                        ->get();

        if ($produk->isEmpty()) {
            return response()->json([], 200); // Balikan array kosong jika tidak ada produk
        }

        return response()->json($produk, 200);
    }

    // ... (fungsi store, update, destroy tidak diubah) ...

    public function flashSale()
    {
        // 1. Ambil data dengan filter 'Jual-Cepat'
        $produk = Produk::where('tipe_penawaran', 'Jual-Cepat')
            ->where('status_produk', 'Tersedia')
            ->where('stok_tersisa', '>', 0)
            // Pastikan Mitra-nya aktif & terverifikasi
            ->whereHas('mitra', function($query) {
                $query->where('status_verifikasi', 'Verified')
                      ->where('status_akun', 'Aktif');
            })
            ->with('mitra') // Load relasi mitra
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. Format ulang JSON agar sesuai dengan Model Android (Produk.kt)
        // Kita perlu mengeluarkan 'nama_mitra' menjadi 'nama_toko' di root JSON
        $formattedData = $produk->map(function($item) {
            return [
                'produk_id'    => $item->produk_id,
                'nama_produk'  => $item->nama_produk,
                'deskripsi'    => $item->deskripsi,
                'harga_normal' => (double) $item->harga_normal, // Casting ke double
                'harga_diskon' => (double) $item->harga_diskon,
                'stok_tersisa' => (int) $item->stok_tersisa,
                'foto_produk'  => $item->foto_produk,

                // Ambil nama toko dari relasi mitra
                'nama_toko'    => $item->mitra ? $item->mitra->nama_mitra : 'Mitra Foodlink',

                // Field lain jika diperlukan
                'mitra_id'     => $item->mitra_id,
                'created_at'   => $item->created_at,
            ];
        });

        return response()->json($formattedData, 200);
    }

    // buatkan fungsi produkDonasi di sini sama persis dengan fungsi flashSale tapi dengan filter tipe_penawaran 'Donasi'
    public function produkDonasi()
    {
        // 1. Ambil data dengan filter 'Donasi'
        $produk = Produk::where('tipe_penawaran', 'Donasi')
            ->where('status_produk', 'Tersedia')
            ->where('stok_tersisa', '>', 0)
            // Pastikan Mitra-nya aktif & terverifikasi
            ->whereHas('mitra', function($query) {
                $query->where('status_verifikasi', 'Verified')
                      ->where('status_akun', 'Aktif');
            })
            ->with('mitra') // Load relasi mitra
            ->orderBy('created_at', 'desc')
            ->get();
        // 2. Format ulang JSON agar sesuai dengan Model Android (Produk.kt)
        // Kita perlu mengeluarkan 'nama_mitra' menjadi 'nama_toko' di root JSON
        $formattedData = $produk->map(function($item) {
            return [
                'produk_id'    => $item->produk_id,
                'nama_produk'  => $item->nama_produk,
                'deskripsi'    => $item->deskripsi,
                'harga_normal' => (double) $item->harga_normal, // Casting ke double
                'harga_diskon' => (double) $item->harga_diskon,
                'stok_tersisa' => (int) $item->stok_tersisa,
                'foto_produk'  => $item->foto_produk,
                // Ambil nama toko dari relasi mitra
                'nama_toko'    => $item->mitra ? $item->mitra->nama_mitra : 'Mitra Foodlink',
                // Field lain jika diperlukan
                'mitra_id'     => $item->mitra_id,
                'created_at'   => $item->created_at,
            ];
        });
        return response()->json($formattedData, 200);
    }
}


