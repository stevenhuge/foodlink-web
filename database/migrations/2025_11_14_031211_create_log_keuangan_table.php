<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabel ini untuk statistik (mencatat setiap sen yang masuk)
        Schema::create('log_keuangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->nullable()->constrained('transaksi', 'transaksi_id');
            $table->morphs('penerima'); // 'mitra_id' atau 'admin_id'
            $table->string('tipe'); // 'penjualan', 'pajak_mitra', 'biaya_layanan'
            $table->bigInteger('jumlah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_keuangan');
    }
};
