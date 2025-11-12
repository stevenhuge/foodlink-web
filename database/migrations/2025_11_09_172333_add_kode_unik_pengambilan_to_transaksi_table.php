<?php
// database/migrations/xxxx_add_kode_unik_pengambilan_to_transaksi_table.php

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
            // Tambahkan kolom 'kode_unik_pengambilan'
            // Pastikan 'unique()' agar kodenya tidak ada yang sama
            // Letakkan setelah 'total_harga_poin' (kolom yang baru kita buat)
            $table->string('kode_unik_pengambilan')->unique()->after('total_harga_poin');
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // Hapus kolomnya jika di-rollback
            $table->dropColumn('kode_unik_pengambilan');
        });
    }
};
