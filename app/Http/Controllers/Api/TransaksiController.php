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
use App\Models\Mitra;
use App\Models\Setting; // <-- WAJIB: Untuk mengambil pajak dinamis
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

                $totalHargaProduk = 0;
                $mitra_id = null;
                $itemsToProcess = [];

                // Lock user untuk update saldo nanti
                $user = User::lockForUpdate()->find($user->user_id);

                // 1. Loop validasi & Hitung Total Harga Produk Murni
                foreach ($items as $item) {
                    $produk = Produk::lockForUpdate()->find($item['produk_id']);

                    if (!$produk) { throw new \Exception("Produk ID {$item['produk_id']} tidak ditemukan."); }
                    if ($produk->status_produk != 'Tersedia') { throw new \Exception("Produk '{$produk->nama_produk}' sedang tidak tersedia."); }
                    if ($produk->stok_tersisa < $item['jumlah']) { throw new \Exception("Stok '{$produk->nama_produk}' tidak mencukupi (sisa: {$produk->stok_tersisa})."); }

                    // Pastikan 1 Transaksi = 1 Mitra
                    if ($mitra_id === null) {
                        $mitra_id = $produk->mitra_id;
                    } elseif ($mitra_id != $produk->mitra_id) {
                        throw new \Exception("Checkout hanya bisa untuk produk dari 1 Mitra yang sama.");
                    }

                    $hargaSatuanPoin = $produk->harga_diskon;
                    $totalHargaProduk += ($hargaSatuanPoin * $item['jumlah']);

                    $itemsToProcess[] = [
                        'produk' => $produk,
                        'jumlah' => $item['jumlah'],
                        'harga_satuan' => $hargaSatuanPoin
                    ];
                }

                // 2. Cek Mitra
                $mitra = Mitra::find($mitra_id);
                if (!$mitra) {
                     throw new \Exception("Mitra (ID: $mitra_id) tidak ditemukan.");
                }

                // === 3. LOGIKA PAJAK & BIAYA DINAMIS (UPDATED) ===

                // Ambil setting dari database (Gunakan nilai default jika belum diset admin)
                $biayaLayananSetting = (int) Setting::ambil('biaya_layanan_user', 2000); // Default Rp 2.000
                $persenPotonganMitra = (float) Setting::ambil('biaya_mitra_persen', 5);  // Default 5%

                // Hitung yang harus dibayar User (Harga Produk + Biaya Layanan Flat)
                $totalFinalUser = $totalHargaProduk + $biayaLayananSetting;

                // Hitung Potongan Mitra (Harga Produk * Persen)
                $potonganPajakMitra = (int) ceil($totalHargaProduk * ($persenPotonganMitra / 100));

                // Hitung Pendapatan Bersih Mitra (Harga Produk - Potongan)
                $pendapatanBersihMitra = $totalHargaProduk - $potonganPajakMitra;

                // =================================================

                // 4. Cek Poin User (User membayar Total Final: Produk + Biaya Layanan)
                if ($user->poin_reward < $totalFinalUser) {
                    throw new \Exception("Poin Anda tidak cukup (Poin: {$user->poin_reward}, Dibutuhkan: {$totalFinalUser}).");
                }

                // 6. Kurangi Poin User
                $user->decrement('poin_reward', $totalFinalUser);

                // 7. Buat Transaksi (SIMPAN NILAI PAJAK KE DB)
                $order = Transaksi::create([
                    'user_id' => $user->user_id,
                    'mitra_id' => $mitra_id,
                    'kode_unik_pengambilan' => 'FD-' . Str::upper(Str::random(8)),
                    'status_pemesanan' => 'paid',

                    // Total yang dibayar user (Poin berkurang segini)
                    'total_harga' => $totalFinalUser,

                    // Harga murni produk (Dasar perhitungan persentase mitra)
                    'total_harga_poin' => $totalHargaProduk,

                    // Rincian Biaya (Penting untuk laporan & riwayat)
                    'biaya_layanan_user' => $biayaLayananSetting,
                    'potongan_pajak_mitra' => $potonganPajakMitra,
                    'pendapatan_bersih_mitra' => $pendapatanBersihMitra,
                ]);

                // 8. Kurangi Stok & Buat Detail
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
