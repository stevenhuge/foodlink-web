<?php
// app/Http/Controllers/Api/TransaksiController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Produk;
use App\Models\Transaksi;       // <-- Gunakan model Anda
use App\Models\DetailTransaksi; // <-- Gunakan model Anda
use Illuminate\Support\Str;

class TransaksiController extends Controller
{
    /**
     * Fitur 4: Membeli Produk (Checkout menggunakan Poin)
     * Endpoint: POST /api/transaksi/checkout
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|integer|exists:produk,produk_id',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        $user = $request->user();
        $items = $request->input('items');

        try {
            $result = DB::transaction(function () use ($user, $items) {

                $totalHargaPoin = 0;
                $mitra_id = null;
                $itemsToProcess = [];

                $user = User::lockForUpdate()->find($user->user_id);

                foreach ($items as $item) {
                    $produk = Produk::lockForUpdate()->find($item['produk_id']);

                    if (!$produk) { throw new \Exception("Produk ID {$item['produk_id']} tidak ditemukan."); }
                    if ($produk->status_produk != 'Tersedia') { throw new \Exception("Produk '{$produk->nama_produk}' sedang tidak tersedia."); }
                    if ($produk->stok_tersisa < $item['jumlah']) { throw new \Exception("Stok '{$produk->nama_produk}' tidak mencukupi (sisa: {$produk->stok_tersisa})."); }

                    if ($mitra_id === null) {
                        $mitra_id = $produk->mitra_id;
                    } elseif ($mitra_id != $produk->mitra_id) {
                        throw new \Exception("Checkout hanya bisa untuk produk dari 1 Mitra yang sama.");
                    }

                    $hargaSatuanPoin = $produk->harga_diskon;
                    $totalHargaPoin += ($hargaSatuanPoin * $item['jumlah']);

                    $itemsToProcess[] = [ /* ... */ ];
                }

                if ($user->poin_reward < $totalHargaPoin) {
                    throw new \Exception("Poin Anda tidak cukup (Poin: {$user->poin_reward}, Dibutuhkan: {$totalHargaPoin}).");
                }

                $user->decrement('poin_reward', $totalHargaPoin);

                // === PERBAIKAN DI SINI ===
                // Isi kedua kolom 'total_harga' (lama) dan 'total_harga_poin' (baru)
                $order = Transaksi::create([
                    'user_id' => $user->user_id,
                    'mitra_id' => $mitra_id,
                    'total_harga' => $totalHargaPoin, // <-- TAMBAHKAN INI
                    'total_harga_poin' => $totalHargaPoin,
                    'kode_unik_pengambilan' => 'FD-' . Str::upper(Str::random(8)),
                    'status' => 'dibayar',
                ]);
                // =========================

                foreach ($itemsToProcess as $item) {
                    // ... (logika mengurangi stok & buat detail transaksi) ...
                }

                return $order;
            });

            return response()->json([
                'message' => 'Pembelian berhasil!',
                'order' => $result->load('detailTransaksi')
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Riwayat Transaksi User
     * Endpoint: GET /api/transaksi/riwayat
     */
    public function riwayat(Request $request)
    {
        $orders = Transaksi::where('user_id', $request->user()->user_id)
                           // Gunakan nama relasi Anda: 'detailTransaksi'
                           ->with('detailTransaksi.produk', 'mitra')
                           // Gunakan custom timestamp Anda: 'waktu_pemesanan'
                           ->orderBy('waktu_pemesanan', 'desc')
                           ->get();

        return response()->json($orders);
    }

    // ... (fungsi show, update, destroy tidak diubah) ...
}
