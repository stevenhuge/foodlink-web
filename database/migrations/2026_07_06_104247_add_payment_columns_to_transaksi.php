<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // Kolom untuk Midtrans
            if (!Schema::hasColumn('transaksi', 'snap_token')) {
                $table->text('snap_token')->nullable()->after('status_pemesanan');
            }
            if (!Schema::hasColumn('transaksi', 'payment_type')) {
                $table->string('payment_type')->nullable()->after('snap_token');
            }
            if (!Schema::hasColumn('transaksi', 'va_number')) {
                $table->string('va_number')->nullable()->after('payment_type');
            }
            // Metode pembayaran (Poin / Midtrans)
            if (!Schema::hasColumn('transaksi', 'metode_pembayaran')) {
                $table->string('metode_pembayaran')->nullable()->after('va_number');
            }
            // kode_pemesanan (order_id Midtrans)
            if (!Schema::hasColumn('transaksi', 'kode_pemesanan')) {
                $table->string('kode_pemesanan')->nullable()->after('kode_unik_pengambilan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'payment_type', 'va_number', 'metode_pembayaran', 'kode_pemesanan']);
        });
    }
};