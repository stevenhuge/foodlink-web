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
        Schema::table('transaksi', function (Blueprint $table) {
            // Kolom ini akan mencatat rincian biaya
            $table->bigInteger('biaya_layanan_user')->default(0)->after('total_harga_poin');
            $table->bigInteger('potongan_pajak_mitra')->default(0)->after('biaya_layanan_user');
            $table->bigInteger('pendapatan_bersih_mitra')->default(0)->after('potongan_pajak_mitra');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            //
        });
    }
};
