<?php
// database/migrations/xxxx_add_status_to_transaksi_table.php

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
            // Tambahkan kolom 'status'
            // Kita gunakan enum karena nilainya sudah pasti
            $table->enum('status', ['dibayar', 'diambil', 'selesai', 'dibatalkan'])
                  ->default('dibayar')
                  ->after('kode_unik_pengambilan'); // Letakkan setelah kode unik
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // Hapus kolomnya jika di-rollback
            $table->dropColumn('status');
        });
    }
};
