<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_penarikan_id_to_log_keuangan_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('log_keuangan', function (Blueprint $table) {
            // Tambahkan kolom ini setelah 'transaksi_id'
            $table->foreignId('penarikan_id')->nullable()->after('transaksi_id')
                  ->constrained('penarikan_dana', 'penarikan_id')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('log_keuangan', function (Blueprint $table) {
            $table->dropForeign(['penarikan_id']);
            $table->dropColumn('penarikan_id');
        });
    }
};
