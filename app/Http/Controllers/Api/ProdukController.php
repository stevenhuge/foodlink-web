<?php
// app/Http/Controllers/Api/ProdukController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;

class ProdukController extends Controller
{
    /**
     * Helper method to format product and replace base64 image with URL
     */
    private function formatProduk($item)
    {
        $foto_url = $item->foto_produk;
        if ($foto_url && str_starts_with($foto_url, 'data:image')) {
            $foto_url = url('/mitra/produk/' . $item->produk_id . '/image');
        } elseif ($foto_url) {
            $foto_url = \Illuminate\Support\Facades\Storage::url($foto_url);
            if (!str_starts_with($foto_url, 'http')) {
                $foto_url = url($foto_url);
            }
        }

        return [
            'produk_id'    => $item->produk_id,
            'nama_produk'  => $item->nama_produk,
            'deskripsi'    => $item->deskripsi,
            'harga_normal' => (double) $item->harga_normal,
            'harga_diskon' => (double) $item->harga_diskon,
            'tipe_penawaran' => $item->tipe_penawaran,
            'stok_tersisa' => (int) $item->stok_tersisa,
            'foto_produk'  => $foto_url,
            'nama_toko'    => $item->mitra ? $item->mitra->nama_mitra : 'Mitra Foodlink',
            'mitra_id'     => $item->mitra_id,
            'created_at'   => $item->created_at,
        ];
    }

    /**
     * Fitur 3: Menampilkan daftar produk
     * Endpoint: GET /api/produk
     */
    public function index()
    {
        $produk = Produk::where('status_produk', 'Tersedia')
                        ->where('stok_tersisa', '>', 0)
                        ->whereHas('mitra', function($query) {
                            $query->where('status_verifikasi', 'Verified')
                                  ->where('status_akun', 'Aktif');
                        })
                        ->with('mitra:mitra_id,nama_mitra,alamat')
                        ->orderBy('created_at', 'desc')
                        ->get();

        $formattedData = $produk->map(function($item) {
            return $this->formatProduk($item);
        });

        return response()->json($formattedData, 200);
    }

    /**
     * Fitur 3: Menampilkan detail produk
     * Endpoint: GET /api/produk/{produk}
     */
    public function show(Produk $produk)
    {
        $produk->load('mitra');

        if ($produk->mitra->status_verifikasi != 'Verified' || $produk->mitra->status_akun != 'Aktif' || $produk->status_produk != 'Tersedia') {
             return response()->json(['message' => 'Produk tidak ditemukan atau tidak tersedia'], 404);
        }

        return response()->json($this->formatProduk($produk), 200);
    }

    /**
     * Fitur Tambahan: Menampilkan produk berdasarkan ID Mitra
     * Endpoint: GET /api/mitra/{id}/produk
     */
    public function getByMitra($id)
    {
        $produk = Produk::where('mitra_id', $id)
                        ->where('status_produk', 'Tersedia')
                        ->where('stok_tersisa', '>', 0)
                        ->with('mitra')
                        ->orderBy('created_at', 'desc')
                        ->get();

        if ($produk->isEmpty()) {
            return response()->json([], 200);
        }

        $formattedData = $produk->map(function($item) {
            return $this->formatProduk($item);
        });

        return response()->json($formattedData, 200);
    }

    public function flashSale()
    {
        $produk = Produk::where('tipe_penawaran', 'Jual-Cepat')
            ->where('status_produk', 'Tersedia')
            ->where('stok_tersisa', '>', 0)
            ->whereHas('mitra', function($query) {
                $query->where('status_verifikasi', 'Verified')
                      ->where('status_akun', 'Aktif');
            })
            ->with('mitra')
            ->orderBy('created_at', 'desc')
            ->get();

        $formattedData = $produk->map(function($item) {
            return $this->formatProduk($item);
        });

        return response()->json($formattedData, 200);
    }

    public function produkDonasi()
    {
        $produk = Produk::where('tipe_penawaran', 'Donasi')
            ->where('status_produk', 'Tersedia')
            ->where('stok_tersisa', '>', 0)
            ->whereHas('mitra', function($query) {
                $query->where('status_verifikasi', 'Verified')
                      ->where('status_akun', 'Aktif');
            })
            ->with('mitra')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $formattedData = $produk->map(function($item) {
            return $this->formatProduk($item);
        });
        
        return response()->json($formattedData, 200);
    }
}
