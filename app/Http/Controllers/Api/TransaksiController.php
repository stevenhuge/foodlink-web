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
    // =========================================================================
    // HELPER: Kalkulasi biaya (DRY)
    // =========================================================================
    private function hitungBiaya(array $items): array
    {
        $totalHargaProduk = 0;
        $mitra_id = null;
        $itemsToProcess = [];

        foreach ($items as $item) {
            $produk = Produk::lockForUpdate()->find($item['produk_id']);

            if (!$produk) throw new \Exception("Produk tidak ditemukan.");
            if ($produk->status_produk != 'Tersedia') throw new \Exception("Produk '{$produk->nama_produk}' tidak tersedia.");
            if ($produk->stok_tersisa < $item['jumlah']) throw new \Exception("Stok '{$produk->nama_produk}' kurang.");

            if ($mitra_id === null) {
                $mitra_id = $produk->mitra_id;
            } elseif ($mitra_id != $produk->mitra_id) {
                throw new \Exception("Produk harus dari satu Mitra yang sama.");
            }

            // Gunakan harga_diskon jika > 0, fallback ke harga_asli
            $hargaSatuan = ($produk->harga_diskon > 0) ? $produk->harga_diskon : $produk->harga_asli;
            $totalHargaProduk += ($hargaSatuan * $item['jumlah']);

            $itemsToProcess[] = [
                'produk'      => $produk,
                'jumlah'      => $item['jumlah'],
                'harga_satuan'=> $hargaSatuan,
            ];
        }

        $biayaLayanan    = (int)(Setting::where('key', 'biaya_layanan_user')->value('value') ?? 1000);
        $persenPpn       = (float)(Setting::where('key', 'biaya_ppn_persen')->value('value') ?? 11);
        $persenMitra     = (float)(Setting::where('key', 'biaya_mitra_persen')->value('value') ?? 5);

        $biayaPpn        = (int) ceil($totalHargaProduk * ($persenPpn / 100));
        $totalFinal      = $totalHargaProduk + $biayaPpn + $biayaLayanan;
        $potonganMitra   = (int) ceil($totalHargaProduk * ($persenMitra / 100));
        $pendapatanMitra = $totalHargaProduk - $potonganMitra;

        return compact(
            'totalHargaProduk', 'biayaPpn', 'biayaLayanan',
            'totalFinal', 'potonganMitra', 'pendapatanMitra',
            'mitra_id', 'itemsToProcess'
        );
    }

    // =========================================================================
    // HELPER: Kurangi stok produk
    // =========================================================================
    private function kurangiStok(array $itemsToProcess): void
    {
        foreach ($itemsToProcess as $item) {
            $produk = $item['produk'];
            $produk->decrement('stok_tersisa', $item['jumlah']);
            if ($produk->fresh()->stok_tersisa <= 0) {
                $produk->update(['status_produk' => 'Habis']);
            }
        }
    }

    // =========================================================================
    // 1. CHECKOUT DENGAN POIN
    // =========================================================================
    public function checkoutPoin(Request $request)
    {
        $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.produk_id'  => 'required|integer|exists:produk,produk_id',
            'items.*.jumlah'     => 'required|integer|min:1',
        ]);

        $user  = $request->user();
        $items = $request->input('items');

        try {
            $result = DB::transaction(function () use ($user, $items) {
                $user = User::lockForUpdate()->find($user->user_id);
                $calc = $this->hitungBiaya($items);

                if ($user->poin_reward < $calc['totalFinal']) {
                    throw new \Exception("Poin tidak cukup. Dibutuhkan {$calc['totalFinal']}, dimiliki {$user->poin_reward}.");
                }

                // Potong poin
                $user->decrement('poin_reward', $calc['totalFinal']);

                // Buat transaksi langsung Paid
                $kode = 'TRX-' . strtoupper(Str::random(6));
                $order = Transaksi::create([
                    'user_id'               => $user->user_id,
                    'mitra_id'              => $calc['mitra_id'],
                    'kode_unik_pengambilan' => $kode,
                    'kode_pemesanan'        => $kode,
                    'status_pemesanan'      => 'Paid',
                    'metode_pembayaran'     => 'Poin',
                    'waktu_pemesanan'       => Carbon::now(),
                    'total_harga'           => $calc['totalFinal'],
                    'total_harga_poin'      => $calc['totalHargaProduk'],
                    'biaya_ppn_user'        => $calc['biayaPpn'],
                    'biaya_layanan_user'    => $calc['biayaLayanan'],
                    'potongan_pajak_mitra'  => $calc['potonganMitra'],
                    'pendapatan_bersih_mitra' => $calc['pendapatanMitra'],
                ]);

                // Buat detail & kurangi stok
                foreach ($calc['itemsToProcess'] as $item) {
                    DetailTransaksi::create([
                        'transaksi_id'       => $order->transaksi_id,
                        'produk_id'          => $item['produk']->produk_id,
                        'jumlah'             => $item['jumlah'],
                        'harga_saat_transaksi'=> $item['harga_satuan'],
                    ]);
                }
                $this->kurangiStok($calc['itemsToProcess']);

                return $order;
            });

            return response()->json([
                'message'      => 'Pembayaran dengan poin berhasil',
                'transaksi_id' => $result->kode_unik_pengambilan,
                'kode_transaksi' => $result->kode_unik_pengambilan,
                'total_bayar'  => $result->total_harga,
                'snap_token'   => null,
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    // =========================================================================
    // 2. CHECKOUT DENGAN MIDTRANS (generate Snap Token)
    // =========================================================================
    public function checkout(Request $request)
    {
        $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.produk_id'  => 'required|integer|exists:produk,produk_id',
            'items.*.jumlah'     => 'required|integer|min:1',
        ]);

        $user  = $request->user();
        $items = $request->input('items');

        try {
            $result = DB::transaction(function () use ($user, $items) {
                $calc = $this->hitungBiaya($items);

                $orderId = 'FL-' . time() . '-' . mt_rand(100, 999);

                // Buat transaksi dengan status Pending
                $order = Transaksi::create([
                    'user_id'               => $user->user_id,
                    'mitra_id'              => $calc['mitra_id'],
                    'kode_unik_pengambilan' => $orderId,
                    'kode_pemesanan'        => $orderId,
                    'status_pemesanan'      => 'Pending',
                    'metode_pembayaran'     => 'Midtrans',
                    'waktu_pemesanan'       => Carbon::now(),
                    'total_harga'           => $calc['totalFinal'],
                    'total_harga_poin'      => $calc['totalHargaProduk'],
                    'biaya_ppn_user'        => $calc['biayaPpn'],
                    'biaya_layanan_user'    => $calc['biayaLayanan'],
                    'potongan_pajak_mitra'  => $calc['potonganMitra'],
                    'pendapatan_bersih_mitra' => $calc['pendapatanMitra'],
                ]);

                // Simpan detail (stok BELUM dikurangi — dikurangi saat webhook settlement)
                $itemDetails = [];
                foreach ($calc['itemsToProcess'] as $item) {
                    DetailTransaksi::create([
                        'transaksi_id'        => $order->transaksi_id,
                        'produk_id'           => $item['produk']->produk_id,
                        'jumlah'              => $item['jumlah'],
                        'harga_saat_transaksi'=> $item['harga_satuan'],
                    ]);
                    $itemDetails[] = [
                        'id'       => (string) $item['produk']->produk_id,
                        'price'    => $item['harga_satuan'],
                        'quantity' => $item['jumlah'],
                        'name'     => substr($item['produk']->nama_produk, 0, 50),
                    ];
                }

                if ($calc['biayaPpn'] > 0) {
                    $itemDetails[] = ['id' => 'FEE-PPN', 'price' => $calc['biayaPpn'], 'quantity' => 1, 'name' => 'PPN'];
                }
                if ($calc['biayaLayanan'] > 0) {
                    $itemDetails[] = ['id' => 'FEE-LAYANAN', 'price' => $calc['biayaLayanan'], 'quantity' => 1, 'name' => 'Biaya Layanan'];
                }

                Config::$serverKey    = config('services.midtrans.server_key');
                Config::$isProduction = config('services.midtrans.is_production');
                Config::$isSanitized  = true;
                Config::$is3ds        = true;

                $params = [
                    'transaction_details' => [
                        'order_id'     => $orderId,
                        'gross_amount' => $calc['totalFinal'],
                    ],
                    'customer_details' => [
                        'first_name' => $user->nama_lengkap ?? $user->nama ?? 'User',
                        'email'      => $user->email,
                        'phone'      => $user->nomor_telepon ?? '081111111111',
                    ],
                    'item_details' => $itemDetails,
                ];

                $snapToken = Snap::getSnapToken($params);

                // Simpan snap_token ke DB agar bisa dipakai bayar ulang
                $order->update(['snap_token' => $snapToken]);

                return [
                    'snap_token'     => $snapToken,
                    'transaksi_id'   => $order->transaksi_id,
                    'kode_transaksi' => $orderId,
                ];
            });

            return response()->json([
                'message'        => 'Checkout berhasil',
                'snap_token'     => $result['snap_token'],
                'transaksi_id'   => $result['kode_transaksi'],
                'kode_transaksi' => $result['kode_transaksi'],
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    // =========================================================================
    // 3. RIWAYAT TRANSAKSI
    // =========================================================================
    public function riwayat(Request $request)
    {
        try {
            $orders = Transaksi::where('user_id', $request->user()->user_id)
                ->with(['detailTransaksi.produk', 'mitra'])
                ->orderBy('waktu_pemesanan', 'desc')
                ->paginate(15); // Menggunakan pagination agar tidak memberatkan server/HP

            $formatted = $orders->getCollection()->map(function ($item) {
                $namaToko = $item->mitra?->nama_mitra ?? 'Mitra Tidak Dikenal';

                $names = $item->detailTransaksi
                    ->filter(fn($dt) => $dt->produk)
                    ->map(fn($dt) => $dt->produk->nama_produk)
                    ->values();

                $detailString = $names->isEmpty()
                    ? 'Item tidak tersedia'
                    : $names->slice(0, 2)->implode(', ')
                        . ($names->count() > 2 ? ', dll.' : '')
                        . ' (' . $item->detailTransaksi->sum('jumlah') . ' item)';

                $status = match(true) {
                    in_array($item->status_pemesanan, ['Paid', 'Siap Diambil', 'Selesai']) => 'SUKSES',
                    in_array($item->status_pemesanan, ['Batal']) => 'GAGAL',
                    default => 'PENDING',
                };

                return [
                    'id'             => $item->transaksi_id,
                    'kode_transaksi' => $item->kode_unik_pengambilan,
                    'total_harga'    => (int) $item->total_harga,
                    'status'         => $status,
                    'created_at'     => Carbon::parse($item->waktu_pemesanan)->translatedFormat('d M Y, H:i'),
                    'mitra_nama'     => $namaToko,
                    'detail_singkat' => $detailString,
                    'metode_pembayaran' => $item->metode_pembayaran ?? '-',
                ];
            });

            // Set kembali collection yang sudah di-format ke dalam objek paginator
            $orders->setCollection($formatted);

            return response()->json($orders, 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal memuat history', 'error' => $e->getMessage()], 500);
        }
    }

    // =========================================================================
    // 4. DETAIL TRANSAKSI (termasuk info bayar ulang untuk Pending Midtrans)
    // =========================================================================
    public function show($kode_transaksi)
    {
        $user = Auth::user();

        $transaksi = Transaksi::with(['detailTransaksi.produk', 'mitra'])
            ->where('kode_unik_pengambilan', $kode_transaksi)
            ->where('user_id', $user->user_id)
            ->first();

        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        // Cek apakah snap_token masih valid (Midtrans token expired setelah 24 jam)
        // Kita cukup sertakan token-nya; Android yang handle tampilan
        $isPendingMidtrans = $transaksi->status_pemesanan === 'Pending'
            && $transaksi->metode_pembayaran === 'Midtrans';

        // Hitung batas bayar (24 jam dari waktu pemesanan)
        $batasBayar = null;
        if ($isPendingMidtrans) {
            $batasBayar = Carbon::parse($transaksi->waktu_pemesanan)
                ->addHours(24)
                ->format('d M Y, H:i');
        }

        return response()->json([
            'kode_transaksi'    => $transaksi->kode_unik_pengambilan,
            'status'            => $transaksi->status_pemesanan,
            'metode_pembayaran' => $transaksi->metode_pembayaran ?? 'Poin',
            'mitra_nama'        => $transaksi->mitra?->nama_mitra ?? 'Mitra',
            'alamat_mitra'      => $transaksi->mitra?->alamat ?? '-',
            'tanggal'           => Carbon::parse($transaksi->waktu_pemesanan)->translatedFormat('d F Y H:i'),

            // Info bayar ulang (hanya ada jika Pending & Midtrans)
            'snap_token'        => $isPendingMidtrans ? $transaksi->snap_token : null,
            'batas_bayar'       => $batasBayar,
            'va_number'         => $transaksi->va_number ?? null,
            'payment_type'      => $transaksi->payment_type ?? null,

            'items' => $transaksi->detailTransaksi->map(fn($d) => [
                'nama_produk' => $d->produk?->nama_produk ?? 'Produk dihapus',
                'qty'         => $d->jumlah,
                'harga'       => $d->harga_saat_transaksi,
                'subtotal'    => $d->jumlah * $d->harga_saat_transaksi,
                'gambar'      => $d->produk?->foto_produk ?? null,
            ]),

            'rincian_biaya' => [
                'total_produk'  => (int) $transaksi->total_harga_poin,
                'biaya_ppn'     => (int) $transaksi->biaya_ppn_user,
                'biaya_layanan' => (int) $transaksi->biaya_layanan_user,
                'total_bayar'   => (int) $transaksi->total_harga,
            ],
        ]);
    }

    // =========================================================================
    // 5. MIDTRANS WEBHOOK CALLBACK
    // =========================================================================
    public function midtransCallback(Request $request)
{
    try {
        // 1. Jika dites via browser (GET), balas dengan ramah tanpa error 500
        if ($request->isMethod('get')) {
            return response()->json(['message' => 'Endpoint Webhook Midtrans Aktif. Menunggu POST request.'], 200);
        }

        $payload = $request->all();
        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? null;

        // Jika data kosong (misal saat ditekan tombol "Test" di dashboard Midtrans)
        if (!$orderId) {
            return response()->json(['message' => 'Format validasi test sukses.'], 200);
        }

        // Cari transaksi di DB
        $transaksi = Transaksi::where('kode_pemesanan', $orderId)
            ->orWhere('kode_unik_pengambilan', $orderId)
            ->first();

        // Jika transaksi dummy dari tombol Test Midtrans
        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi tidak ditemukan, test dianggap sukses.'], 200);
        }

        // Ambil nomor VA (jika ada)
        $vaNumber = null;
        if (!empty($payload['va_numbers']) && is_array($payload['va_numbers'])) {
            $vaNumber = $payload['va_numbers'][0]['va_number'] ?? null;
        } elseif (!empty($payload['permata_va_number'])) {
            $vaNumber = $payload['permata_va_number'];
        } elseif (!empty($payload['bill_key'])) {
            $vaNumber = $payload['bill_key'];
        }

        // Update status berdasarkan notifikasi
        switch ($transactionStatus) {
            case 'capture':
            case 'settlement':
                if ($transaksi->status_pemesanan === 'Pending') {
                    DB::transaction(function () use ($transaksi, $paymentType, $vaNumber) {
                        $transaksi->update([
                            'status_pemesanan' => 'Paid',
                            'payment_type'     => $paymentType,
                            'va_number'        => $vaNumber,
                        ]);
                        
                        $details = DetailTransaksi::where('transaksi_id', $transaksi->transaksi_id)->get();
                        foreach ($details as $detail) {
                            $produk = Produk::lockForUpdate()->find($detail->produk_id);
                            if ($produk) {
                                $produk->decrement('stok_tersisa', $detail->jumlah);
                                if ($produk->fresh()->stok_tersisa <= 0) {
                                    $produk->update(['status_produk' => 'Habis']);
                                }
                            }
                        }
                    }); 
                }
                break;
            case 'pending':
                $transaksi->update([
                    'status_pemesanan' => 'Pending',
                    'payment_type'     => $paymentType,
                    'va_number'        => $vaNumber,
                ]);
                break;
            case 'deny':
            case 'expire':
            case 'cancel':
                $transaksi->update(['status_pemesanan' => 'Batal']);
                break;
        }

        return response()->json(['message' => 'Notifikasi berhasil diproses'], 200);

    } catch (\Throwable $th) {
        // Tangkap SEMUA error agar Laravel tidak melakukan redirect 301/302!
        return response()->json([
            'message' => 'Ada kesalahan kode, namun dikembalikan 200 agar webhook tidak retry berulang',
            'error'   => $th->getMessage()
        ], 200); 
    }
}

    // ─── TransaksiController.php ────────────────────────────────────────────────

    /**
     * Cek status transaksi — dipanggil dari dialog "Cek Status" di PaymentActivity
     */
    public function cekStatus(Request $request, $kode_transaksi)
    {
        $transaksi = Transaksi::where('kode_unik_pengambilan', $kode_transaksi)
            ->where('user_id', $request->user()->user_id)
            ->first();

        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        return response()->json([
            'status'          => $transaksi->status_pemesanan,
            'kode_transaksi'  => $transaksi->kode_unik_pengambilan,
            'total_harga'     => $transaksi->total_harga,
        ]);
    }

    /**
     * Ganti metode pembayaran dari Midtrans ke Poin
     * Dipanggil dari popup "Ganti Metode" di DetailTransaksiActivity
     */
    public function bayarDenganPoin(Request $request, $kode_transaksi)
    {
        $user = $request->user();

        $transaksi = Transaksi::where('kode_unik_pengambilan', $kode_transaksi)
            ->where('user_id', $user->user_id)
            ->first();

        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }
        if ($transaksi->status_pemesanan !== 'Pending') {
            return response()->json(['message' => 'Transaksi tidak dalam status Pending'], 400);
        }

        try {
            DB::transaction(function () use ($user, $transaksi) {
                $user = User::lockForUpdate()->find($user->user_id);

                if ($user->poin_reward < $transaksi->total_harga) {
                    throw new \Exception("Poin tidak cukup. Dibutuhkan {$transaksi->total_harga}, dimiliki {$user->poin_reward}.");
                }

                // Potong poin
                $user->decrement('poin_reward', $transaksi->total_harga);

                // Update transaksi: metode → Poin, status → Paid, hapus snap_token
                $transaksi->update([
                    'status_pemesanan'  => 'Paid',
                    'metode_pembayaran' => 'Poin',
                    'snap_token'        => null,
                ]);

                // Kurangi stok (sebelumnya belum dikurangi karena Midtrans pending)
                $details = \App\Models\DetailTransaksi::where('transaksi_id', $transaksi->transaksi_id)->get();
                foreach ($details as $detail) {
                    $produk = Produk::lockForUpdate()->find($detail->produk_id);
                    if ($produk) {
                        // CEK STOK SEBELUM DECREMENT (Mencegah Stok Minus / Overselling)
                        if ($produk->stok_tersisa < $detail->jumlah) {
                            throw new \Exception("Gagal: Stok '{$produk->nama_produk}' tidak mencukupi (sisa {$produk->stok_tersisa}). Silakan batalkan transaksi ini.");
                        }

                        $produk->decrement('stok_tersisa', $detail->jumlah);
                        if ($produk->fresh()->stok_tersisa <= 0) {
                            $produk->update(['status_produk' => 'Habis']);
                        }
                    }
                }
            });

            return response()->json([
                'message'         => 'Berhasil dibayar dengan poin',
                'status'          => 'Paid',
                'kode_transaksi'  => $transaksi->kode_unik_pengambilan,
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Membatalkan transaksi yang masih berstatus Pending
     */
    public function batalkanTransaksi(Request $request, $kode_transaksi)
    {
        $user = $request->user();

        $transaksi = Transaksi::where('kode_unik_pengambilan', $kode_transaksi)
            ->where('user_id', $user->user_id)
            ->first();

        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        if ($transaksi->status_pemesanan !== 'Pending') {
            return response()->json(['message' => 'Transaksi tidak dapat dibatalkan karena status sudah ' . $transaksi->status_pemesanan], 400);
        }

        try {
            DB::transaction(function () use ($transaksi) {
                // Update status menjadi Batal
                $transaksi->update([
                    'status_pemesanan' => 'Batal',
                ]);

                // Batalkan di Midtrans jika menggunakan Midtrans
                if ($transaksi->metode_pembayaran === 'Midtrans' && $transaksi->kode_pemesanan) {
                    try {
                        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
                        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
                        \Midtrans\Transaction::cancel($transaksi->kode_pemesanan);
                    } catch (\Exception $e) {
                        // Jika gagal cancel di Midtrans, tidak menggagalkan proses lokal.
                        // Mungkin transaksi sudah dibatalkan di Midtrans atau tidak ditemukan.
                    }
                }
            });

            return response()->json(['message' => 'Transaksi berhasil dibatalkan'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal membatalkan transaksi: ' . $e->getMessage()], 500);
        }
    }
}