<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Mitra;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
class TransaksiController extends Controller
{
    // --- 1. CHECKOUT / PEMBELIAN (SUDAH BENAR) ---
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

                // Lock user
                $user = User::lockForUpdate()->find($user->user_id);

                foreach ($items as $item) {
                    $produk = Produk::lockForUpdate()->find($item['produk_id']);

                    if (!$produk) { throw new \Exception("Produk tidak ditemukan."); }
                    if ($produk->status_produk != 'Tersedia') { throw new \Exception("Produk '{$produk->nama_produk}' tidak tersedia."); }
                    if ($produk->stok_tersisa < $item['jumlah']) { throw new \Exception("Stok '{$produk->nama_produk}' kurang."); }

                    if ($mitra_id === null) {
                        $mitra_id = $produk->mitra_id;
                    } elseif ($mitra_id != $produk->mitra_id) {
                        throw new \Exception("Produk harus dari satu Mitra yang sama.");
                    }

                    $hargaSatuanPoin = $produk->harga_diskon >= 0 ? $produk->harga_diskon : $produk->harga_asli;
                    $totalHargaProduk += ($hargaSatuanPoin * $item['jumlah']);

                    $itemsToProcess[] = [
                        'produk' => $produk,
                        'jumlah' => $item['jumlah'],
                        'harga_satuan' => $hargaSatuanPoin
                    ];
                }

                // Ambil Setting (Handle jika setting kosong)
                $biayaLayananSetting = (int) (Setting::where('key', 'biaya_layanan_user')->value('value') ?? 1000);
                $persenPpnUser = (float) (Setting::where('key', 'biaya_ppn_persen')->value('value') ?? 11);
                $persenPotonganMitra = (float) (Setting::where('key', 'biaya_mitra_persen')->value('value') ?? 5);

                // Kalkulasi PPN (dikenakan ke Pembeli)
                $biayaPpnUser = (int) ceil($totalHargaProduk * ($persenPpnUser / 100));

                $totalFinalUser = $totalHargaProduk + $biayaPpnUser + $biayaLayananSetting;
                
                // Kalkulasi Komisi Platform (dikenakan ke Mitra)
                $potonganPajakMitra = (int) ceil($totalHargaProduk * ($persenPotonganMitra / 100));
                $pendapatanBersihMitra = $totalHargaProduk - $potonganPajakMitra;

                if ($user->poin_reward < $totalFinalUser) {
                    throw new \Exception("Poin tidak cukup.");
                }

                $user->decrement('poin_reward', $totalFinalUser);

                $order = Transaksi::create([
                    'user_id' => $user->user_id,
                    'mitra_id' => $mitra_id,
                    'kode_unik_pengambilan' => 'TRX-' . strtoupper(Str::random(6)),
                    'status_pemesanan' => 'Paid', // Sesuaikan dengan enum database (Huruf Besar Awal)
                    'waktu_pemesanan' => Carbon::now(), // Wajib diisi manual karena timestamps false
                    'total_harga' => $totalFinalUser,
                    'total_harga_poin' => $totalHargaProduk,
                    'biaya_ppn_user' => $biayaPpnUser,
                    'biaya_layanan_user' => $biayaLayananSetting,
                    'potongan_pajak_mitra' => $potonganPajakMitra,
                    'pendapatan_bersih_mitra' => $pendapatanBersihMitra,
                ]);

                foreach ($itemsToProcess as $item) {
                    $produk = $item['produk'];
                    $produk->decrement('stok_tersisa', $item['jumlah']);
                    if($produk->stok_tersisa <= 0) {
                        $produk->update(['status_produk' => 'Habis']);
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
                'message' => 'Transaksi Berhasil',
                'transaksi_id' => $result->kode_unik_pengambilan,
                'total_bayar' => $result->total_harga
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    // --- 2. RIWAYAT (SUDAH DIPERBAIKI) ---
    public function riwayat(Request $request)
    {
        try {
            $user_id = $request->user()->user_id;

            $orders = Transaksi::where('user_id', $user_id)
                ->with(['detailTransaksi.produk', 'mitra'])
                ->orderBy('waktu_pemesanan', 'desc') // PERBAIKAN 1: Gunakan waktu_pemesanan
                ->get();

            $formattedOrders = $orders->map(function ($item) {

                // PERBAIKAN 2: Gunakan nama_mitra (bukan nama_toko)
                $namaToko = $item->mitra ? $item->mitra->nama_mitra : 'Mitra Tidak Dikenal';

                // Buat detail singkat
                $detailString = "Item tidak tersedia";
                if ($item->detailTransaksi && $item->detailTransaksi->isNotEmpty()) {
                    $names = [];
                    foreach($item->detailTransaksi as $dt) {
                        if($dt->produk) $names[] = $dt->produk->nama_produk;
                    }
                    $detailString = implode(', ', array_slice($names, 0, 2));
                    if (count($names) > 2) $detailString .= ", dll.";
                    $detailString .= " (" . $item->detailTransaksi->sum('jumlah') . " item)";
                }

                // Status Logic
                $status = 'PENDING';
                // Cek Enum DB Anda: 'Paid','Siap Diambil','Selesai' dianggap sukses
                if (in_array($item->status_pemesanan, ['Paid', 'paid', 'Siap Diambil', 'Selesai', 'selesai'])) {
                    $status = 'SUKSES';
                } elseif (in_array($item->status_pemesanan, ['Batal', 'batal'])) {
                    $status = 'GAGAL';
                }

                // PERBAIKAN 3: Gunakan waktu_pemesanan untuk tanggal
                $tanggal = $item->waktu_pemesanan
                    ? Carbon::parse($item->waktu_pemesanan)->translatedFormat('d M Y, H:i')
                    : '-';

                return [
                    'id' => $item->transaksi_id,
                    'kode_transaksi' => $item->kode_unik_pengambilan,
                    'total_harga' => (int) $item->total_harga,
                    'status' => $status,
                    'created_at' => $tanggal,
                    'mitra_nama' => $namaToko,
                    'detail_singkat' => $detailString
                ];
            });

            return response()->json($formattedOrders, 200);

        } catch (\Exception $e) {
            // Tangkap Error detail agar mudah debug di Android
            return response()->json([
                'message' => 'Gagal memuat history',
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    // --- 3. DETAIL TRANSAKSI (SUDAH DIPERBAIKI) ---
    public function show($kode_transaksi)
    {
        $user = Auth::user(); // Ambil user yg login

        $transaksi = Transaksi::with(['detailTransaksi.produk', 'mitra'])
            ->where('kode_unik_pengambilan', $kode_transaksi)
            ->where('user_id', $user->user_id) // Pastikan milik user yg login
            ->first();

        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        return response()->json([
            'kode_transaksi' => $transaksi->kode_unik_pengambilan,
            'status' => $transaksi->status_pemesanan,
            'mitra_nama' => $transaksi->mitra->nama_mitra ?? 'Mitra', // PERBAIKAN: nama_mitra
            'alamat_mitra' => $transaksi->mitra->alamat ?? '-',
            'tanggal' => Carbon::parse($transaksi->waktu_pemesanan)->translatedFormat('d F Y H:i'), // PERBAIKAN: waktu_pemesanan
            'items' => $transaksi->detailTransaksi->map(function($d) {
                return [
                    'nama_produk' => $d->produk->nama_produk ?? 'Produk dihapus',
                    'qty' => $d->jumlah,
                    'harga' => $d->harga_saat_transaksi,
                    'subtotal' => $d->jumlah * $d->harga_saat_transaksi,
                    // Pastikan di tabel produk kolomnya 'foto_produk'
                    'gambar' => $d->produk->foto_produk ?? null
                ];
            }),
            'rincian_biaya' => [
                'total_produk' => $transaksi->total_harga_poin,
                'biaya_ppn' => $transaksi->biaya_ppn_user,
                'biaya_layanan' => $transaksi->biaya_layanan_user,
                'total_bayar' => $transaksi->total_harga
            ]
        ]);
    }

    // --- 4. MIDTRANS CHECKOUT ---
    public function checkout(Request $request)
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

                foreach ($items as $item) {
                    $produk = Produk::lockForUpdate()->find($item['produk_id']);

                    if (!$produk) { throw new \Exception("Produk tidak ditemukan."); }
                    if ($produk->status_produk != 'Tersedia') { throw new \Exception("Produk '{$produk->nama_produk}' tidak tersedia."); }
                    if ($produk->stok_tersisa < $item['jumlah']) { throw new \Exception("Stok '{$produk->nama_produk}' kurang."); }

                    if ($mitra_id === null) {
                        $mitra_id = $produk->mitra_id;
                    } elseif ($mitra_id != $produk->mitra_id) {
                        throw new \Exception("Produk harus dari satu Mitra yang sama.");
                    }

                    $hargaSatuan = $produk->harga_diskon >= 0 ? $produk->harga_diskon : $produk->harga_asli;
                    if (!$hargaSatuan) $hargaSatuan = $produk->harga_normal; // fallback
                    $totalHargaProduk += ($hargaSatuan * $item['jumlah']);

                    $itemsToProcess[] = [
                        'produk' => $produk,
                        'jumlah' => $item['jumlah'],
                        'harga_satuan' => $hargaSatuan
                    ];
                }

                $biayaLayananSetting = (int) (Setting::where('key', 'biaya_layanan_user')->value('value') ?? 1000);
                $persenPpnUser = (float) (Setting::where('key', 'biaya_ppn_persen')->value('value') ?? 11);
                $persenPotonganMitra = (float) (Setting::where('key', 'biaya_mitra_persen')->value('value') ?? 5);

                $biayaPpnUser = (int) ceil($totalHargaProduk * ($persenPpnUser / 100));
                $totalFinalUser = $totalHargaProduk + $biayaPpnUser + $biayaLayananSetting;
                
                $potonganPajakMitra = (int) ceil($totalHargaProduk * ($persenPotonganMitra / 100));
                $pendapatanBersihMitra = $totalHargaProduk - $potonganPajakMitra;

                $orderId = 'FL-' . time() . '-' . mt_rand(100, 999);

                // Buat record baru di tabel transaksis dengan status awal Pending
                $order = Transaksi::create([
                    'user_id' => $user->user_id,
                    'mitra_id' => $mitra_id,
                    'kode_unik_pengambilan' => $orderId, 
                    'kode_pemesanan' => $orderId,
                    'status_pemesanan' => 'Pending', 
                    'waktu_pemesanan' => Carbon::now(),
                    'total_harga' => $totalFinalUser,
                    'total_harga_poin' => $totalHargaProduk,
                    'biaya_ppn_user' => $biayaPpnUser,
                    'biaya_layanan_user' => $biayaLayananSetting,
                    'potongan_pajak_mitra' => $potonganPajakMitra,
                    'pendapatan_bersih_mitra' => $pendapatanBersihMitra,
                    'metode_pembayaran' => 'Midtrans',
                ]);

                $itemDetails = [];
                foreach ($itemsToProcess as $item) {
                    $produk = $item['produk'];
                    DetailTransaksi::create([
                        'transaksi_id' => $order->transaksi_id,
                        'produk_id' => $produk->produk_id,
                        'jumlah' => $item['jumlah'],
                        'harga_saat_transaksi' => $item['harga_satuan'],
                    ]);

                    $itemDetails[] = [
                        'id' => $produk->produk_id,
                        'price' => $item['harga_satuan'],
                        'quantity' => $item['jumlah'],
                        'name' => substr($produk->nama_produk, 0, 50)
                    ];
                }

                // Tambahan biaya ke item details Midtrans
                if ($biayaPpnUser > 0) {
                    $itemDetails[] = [
                        'id' => 'FEE-PPN',
                        'price' => $biayaPpnUser,
                        'quantity' => 1,
                        'name' => 'PPN'
                    ];
                }
                if ($biayaLayananSetting > 0) {
                    $itemDetails[] = [
                        'id' => 'FEE-LAYANAN',
                        'price' => $biayaLayananSetting,
                        'quantity' => 1,
                        'name' => 'Biaya Layanan'
                    ];
                }

                // MIDTRANS CONFIGURATION
                Config::$serverKey = config('services.midtrans.server_key');
                Config::$isProduction = config('services.midtrans.is_production');
                Config::$isSanitized = true;
                Config::$is3ds = true;

                $params = [
                    'transaction_details' => [
                        'order_id' => $orderId,
                        'gross_amount' => $totalFinalUser,
                    ],
                    'customer_details' => [
                        'first_name' => $user->nama ?? $user->name ?? 'User',
                        'email' => $user->email,
                        'phone' => $user->no_telp ?? $user->phone ?? '081111111111',
                    ],
                    'item_details' => $itemDetails,
                ];

                $snapToken = Snap::getSnapToken($params);

                return [
                    'snap_token' => $snapToken,
                    'transaksi_id' => $order->transaksi_id,
                    'kode_transaksi' => $orderId
                ];
            });

            return response()->json([
                'message' => 'Checkout Berhasil',
                'snap_token' => $result['snap_token'],
                'transaksi_id' => $result['transaksi_id'],
                'kode_transaksi' => $result['kode_transaksi']
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    // --- 5. MIDTRANS WEBHOOK CALLBACK ---
    public function midtransCallback(Request $request)
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');

        try {
            $notif = new Notification();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transactionStatus = $notif->transaction_status;
        $orderId = $notif->order_id;
        
        $transaksi = Transaksi::where('kode_pemesanan', $orderId)->orWhere('kode_unik_pengambilan', $orderId)->first();
        if (!$transaksi) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        switch ($transactionStatus) {
            case 'capture':
            case 'settlement':
                if ($transaksi->status_pemesanan == 'Pending') {
                    DB::transaction(function () use ($transaksi) {
                        $transaksi->update(['status_pemesanan' => 'Paid']);
                        
                        // Kurangi stok produk secara permanen
                        $details = DetailTransaksi::where('transaksi_id', $transaksi->transaksi_id)->get();
                        foreach ($details as $detail) {
                            $produk = Produk::lockForUpdate()->find($detail->produk_id);
                            if ($produk) {
                                $produk->decrement('stok_tersisa', $detail->jumlah);
                                if($produk->stok_tersisa <= 0) {
                                    $produk->update(['status_produk' => 'Habis']);
                                }
                            }
                        }
                    });
                }
                break;
            case 'pending':
                $transaksi->update(['status_pemesanan' => 'Pending']);
                break;
            case 'deny':
            case 'expire':
            case 'cancel':
                $transaksi->update(['status_pemesanan' => 'Batal']);
                break;
        }

        return response()->json(['message' => 'OK']);
    }
}
