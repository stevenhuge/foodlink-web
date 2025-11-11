<?php
// app/Http/Controllers/Api/TransaksiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
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

                    $itemsToProcess[] = [
                        'produk' => $produk,
                        'jumlah' => $item['jumlah'],
                        'harga_satuan' => $hargaSatuanPoin
                    ];
                }

                if ($user->poin_reward < $totalHargaPoin) {
                    throw new \Exception("Poin Anda tidak cukup (Poin: {$user->poin_reward}, Dibutuhkan: {$totalHargaPoin}).");
                }

                $user->decrement('poin_reward', $totalHargaPoin);

                // === PERBAIKAN DI SINI ===
                $order = Transaksi::create([
                    'user_id' => $user->user_id,
                    'mitra_id' => $mitra_id,
                    'total_harga' => $totalHargaPoin,
                    'total_harga_poin' => $totalHargaPoin,
                    'kode_unik_pengambilan' => 'FD-' . Str::upper(Str::random(8)),
                    'status_pemesanan' => 'paid', // <-- Menggunakan kolom dan value Anda
                ]);
                // =========================

                foreach ($itemsToProcess as $item) {
                    $produk = $item['produk'];
                    $produk->decrement('stok_tersisa', $item['jumlah']);
                    if($produk->stok_tersisa <= 0) {
                        $produk->status_produk = 'Habis';
                        $produk->save();
                    }
                    DetailTransaksi::create([
                        'transaksi_id' => $order->transaksi_id,
                        'produk_id' => $produk->produk_id,
                        'jumlah' => $item['jumlah'],
                        'harga_saat_transaksi' => $item['harga_satuan'],
                    ]);
                }

                return $order;
            });

            return response()->json([
                'message' => 'Pembelian berhasil! Tunjukkan kode pengambilan ke Mitra.',
                'order' => $result->load('detailTransaksi')
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function riwayat(Request $request)
    {
        $user_id = $request->user()->user_id;
        $orders = Transaksi::where('user_id', $user_id)
                           ->with('detailTransaksi.produk', 'mitra')
                           ->orderBy('waktu_pemesanan', 'desc')
                           ->get();
        return response()->json($orders);
    }

    public function show(string $id)
    {
        $transaksi = Transaksi::with('detailTransaksi.produk', 'mitra')
                              ->where('transaksi_id', $id)
                              ->where('user_id', Auth::id())
                              ->firstOrFail();
        return response()->json($transaksi);
    }
}
