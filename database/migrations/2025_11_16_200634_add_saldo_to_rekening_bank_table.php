<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_saldo_to_rekening_bank_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rekening_bank', function (Blueprint $table) {
            // Tambahkan kolom saldo setelah nama_pemilik, default 0
            $table->bigInteger('saldo')->default(0)->after('nama_pemilik');
        });
    }

    public function down(): void
    {
        Schema::table('rekening_bank', function (Blueprint $table) {
            $table->dropColumn('saldo');
        });
    }
};
