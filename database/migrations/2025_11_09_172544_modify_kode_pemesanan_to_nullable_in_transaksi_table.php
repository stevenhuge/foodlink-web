<?php
// database/migrations/xxxx_modify_kode_pemesanan_to_nullable_in_transaksi_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // Ubah kolom 'kode_pemesanan' agar boleh NULL
            // Kita perlu menambahkan ->change()
            $table->string('kode_pemesanan')->nullable()->change();
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // Kembalikan seperti semula (asumsi sebelumnya not null)
            $table->string('kode_pemesanan')->nullable(false)->change();
        });
    }
};
