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
use App\Models\Mitra;         // <-- TAMBAHAN
use App\Models\Admin;        // <-- TAMBAHAN
use App\Models\LogKeuangan;  // <-- TAMBAHAN
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

                $totalHargaProduk = 0; // <-- Ini adalah total murni harga produk
                $mitra_id = null;
                $itemsToProcess = [];

                $user = User::lockForUpdate()->find($user->user_id);

                // 1. Loop pertama untuk validasi dan hitung total harga produk
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
                    $totalHargaProduk += ($hargaSatuanPoin * $item['jumlah']); // <-- Total murni

                    $itemsToProcess[] = [
                        'produk' => $produk,
                        'jumlah' => $item['jumlah'],
                        'harga_satuan' => $hargaSatuanPoin
                    ];
                }

                // --- MULAI LOGIKA KEUANGAN BARU ---

                // 2. Ambil model Mitra dan SuperAdmin (lock mereka juga)
                $mitra = Mitra::lockForUpdate()->find($mitra_id);
                if (!$mitra) {
                     throw new \Exception("Mitra (ID: $mitra_id) tidak ditemukan.");
                }
                $superAdmin = Admin::lockForUpdate()->where('role', 'SuperAdmin')->first(); // Asumsi SuperAdmin punya 'role'
                if (!$superAdmin) {
                    throw new \Exception("Kesalahan Sistem: SuperAdmin tidak ditemukan.");
                }

                // 3. Hitung Pajak (Req 2 & 3)
                // User dibebankan 0.2% dari total harga produk
                $biayaLayananUser = (int) ceil($totalHargaProduk * 0.002); // <-- TAMBAHAN

                // Total yang HARUS dibayar user
                $totalFinalUser = $totalHargaProduk + $biayaLayananUser; // <-- TAMBAHAN

                // 4. Cek Poin User (Menggunakan Total Final)
                if ($user->poin_reward < $totalFinalUser) { // <-- MODIFIKASI
                    throw new \Exception("Poin Anda tidak cukup (Poin: {$user->poin_reward}, Dibutuhkan: {$totalFinalUser}).");
                }

                // 5. Hitung Pemasukan (Req 2)
                // Mitra dipotong 0.5% dari total harga produk
                $potonganPajakMitra = (int) ceil($totalHargaProduk * 0.005); // <-- TAMBAHAN
                $pendapatanBersihMitra = $totalHargaProduk - $potonganPajakMitra; // <-- TAMBAHAN

                // SuperAdmin dapat kedua pajak
                $pendapatanSuperAdmin = $potonganPajakMitra + $biayaLayananUser; // <-- TAMBAHAN

                // 6. Jalankan Transaksi Saldo/Poin
                $user->decrement('poin_reward', $totalFinalUser); // <-- MODIFIKASI
                $mitra->increment('saldo_pemasukan', $pendapatanBersihMitra); // <-- TAMBAHAN
                $superAdmin->increment('saldo_pemasukan', $pendapatanSuperAdmin); // <-- TAMBAHAN

                // 7. Buat Transaksi (Menyimpan catatan pajak)
                $order = Transaksi::create([
                    'user_id' => $user->user_id,
                    'mitra_id' => $mitra_id,
                    'total_harga' => $totalHargaProduk,
                    'total_harga_poin' => $totalHargaProduk,
                    'kode_unik_pengambilan' => 'FD-' . Str::upper(Str::random(8)),
                    'status_pemesanan' => 'paid',
                    'biaya_layanan_user' => $biayaLayananUser, // <-- TAMBAHAN
                    'potongan_pajak_mitra' => $potonganPajakMitra, // <-- TAMBAHAN
                    'pendapatan_bersih_mitra' => $pendapatanBersihMitra, // <-- TAMBAHAN
                ]);

                // 8. Buat Log Keuangan untuk Statistik (Req 4 & 5)
                LogKeuangan::create([ // <-- TAMBAHAN
                    'transaksi_id' => $order->transaksi_id,
                    'penerima_type' => Mitra::class,
                    'penerima_id' => $mitra->mitra_id,
                    'tipe' => 'penjualan_bersih',
                    'jumlah' => $pendapatanBersihMitra
                ]);
                LogKeuangan::create([ // <-- TAMBAHAN
                    'transaksi_id' => $order->transaksi_id,
                    'penerima_type' => Admin::class,
                    'penerima_id' => $superAdmin->admin_id,
                    'tipe' => 'pajak_mitra',
                    'jumlah' => $potonganPajakMitra
                ]);
                LogKeuangan::create([ // <-- TAMBAHAN
                    'transaksi_id' => $order->transaksi_id,
                    'penerima_type' => Admin::class,
                    'penerima_id' => $superAdmin->admin_id,
                    'tipe' => 'biaya_layanan',
                    'jumlah' => $biayaLayananUser
                ]);

                // 9. Kurangi Stok & Buat Detail (Logika Anda sudah benar)
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
        // ... (Fungsi ini tidak perlu diubah) ...
        $user_id = $request->user()->user_id;
        $orders = Transaksi::where('user_id', $user_id)
                            ->with('detailTransaksi.produk', 'mitra')
                            ->orderBy('waktu_pemesanan', 'desc')
                            ->get();
        return response()->json($orders);
    }

    public function show(string $id)
    {
        // ... (Fungsi ini tidak perlu diubah) ...
        $transaksi = Transaksi::with('detailTransaksi.produk', 'mitra')
                                ->where('transaksi_id', $id)
                                ->where('user_id', Auth::id())
                                ->firstOrFail();
        return response()->json($transaksi);
    }
}
