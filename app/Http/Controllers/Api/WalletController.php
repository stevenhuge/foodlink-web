<?php
// app/Http/Controllers/Api/WalletController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TopupTransaction;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    // Konfigurasi Midtrans
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false); // Set true di produksi
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Fitur 5: Meminta Top-Up Poin (Dibayar pakai Midtrans)
     * Endpoint: POST /api/wallet/topup
     */
    public function requestTopup(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:10000', // Min topup 10rb
        ]);

        $user = $request->user();
        $amount = $request->input('amount');
        $poin_didapat = $amount; // Asumsi 1 Rupiah = 1 Poin
        $topup_id = 'TOPUP-' . $user->user_id . '-' . time() . '-' . Str::random(4);

        // === PERBAIKAN DI SINI ===
        // Ganti placeholder comment dengan array data yang sebenarnya
        $topup = TopupTransaction::create([
            'topup_id' => $topup_id,
            'user_id' => $user->user_id,
            'amount' => $amount,
            'poin_didapat' => $poin_didapat,
            'status' => 'pending',
        ]);
        // =========================

        // 2. Siapkan parameter untuk Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $topup_id, // Gunakan ID unik kita
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $user->nama_lengkap,
                'email' => $user->email,
                'phone' => $user->nomor_telepon,
            ],
            'item_details' => [[
                'id' => 'POIN',
                'price' => $amount,
                'quantity' => 1,
                'name' => 'Top Up Poin Foodlink (' . $poin_didapat . ' Poin)',
            ]],
        ];

        try {
            // 3. Minta token/URL pembayaran ke Midtrans
            $paymentToken = Snap::createTransaction($params)->token;

            // 4. Kembalikan token ke Android
            return response()->json([
                'message' => 'Token pembayaran berhasil dibuat',
                'payment_token' => $paymentToken
            ]);

        } catch (\Exception $e) {
            $topup->delete(); // Hapus transaksi pending jika gagal request ke midtrans
            return response()->json(['message' => 'Gagal membuat transaksi Midtrans: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Fitur 5: Midtrans Webhook (Mengisi saldo setelah bayar)
     * Endpoint: POST /api/payment/webhook
     */
    public function webhookHandler(Request $request)
    {
        // ... (Kode webhookHandler Anda sudah benar) ...
        $payload = $request->getContent();
        $notification = json_decode($payload);
        if ($notification === null) {
            return response()->json(['message' => 'Invalid JSON payload'], 400);
        }
        if (!isset($notification->order_id) || !isset($notification->status_code) || !isset($notification->gross_amount) || !isset($notification->signature_key)) {
             return response()->json(['message' => 'Invalid notification structure.'], 400);
        }
        $signature_key = hash('sha512', $notification->order_id . $notification->status_code . $notification->gross_amount . env('MIDTRANS_SERVER_KEY'));
        if ($notification->signature_key != $signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }
        $order_id = $notification->order_id;
        $transaction_status = $notification->transaction_status ?? null;
        $fraud_status = $notification->fraud_status ?? null;
        $transaction_id = $notification->transaction_id ?? null;
        $topup = TopupTransaction::find($order_id);
        if (!$topup) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }
        if ($topup->status === 'pending') {
            if (($transaction_status == 'capture' || $transaction_status == 'settlement') && $fraud_status == 'accept') {
                DB::transaction(function () use ($topup, $transaction_id) {
                    $topup->update([
                        'status' => 'success',
                        'payment_gateway_ref' => $transaction_id
                    ]);
                    $user = $topup->user;
                    $user->increment('poin_reward', $topup->poin_didapat);
                });
            } else if ($transaction_status == 'cancel' || $transaction_status == 'deny' || $transaction_status == 'expire') {
                $topup->update([
                    'status' => 'failed',
                    'payment_gateway_ref' => $transaction_id
                ]);
            }
        }
        return response()->json(['status' => 'ok']);
    }
}
