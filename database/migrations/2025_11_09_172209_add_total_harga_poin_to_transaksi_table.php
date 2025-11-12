<?php
// database/migrations/xxxx_add_total_harga_poin_to_transaksi_table.php

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
        // Kita gunakan Schema::table() untuk memodifikasi tabel yang sudah ada
        Schema::table('transaksi', function (Blueprint $table) {
            // Tambahkan kolom 'total_harga_poin'
            // Kita letakkan setelah 'mitra_id' agar rapi
            $table->integer('total_harga_poin')->default(0)->after('mitra_id');
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // Hapus kolomnya jika di-rollback
            $table->dropColumn('total_harga_poin');
        });
    }
};
