<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_potongan_pajak_to_penarikan_dana_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penarikan_dana', function (Blueprint $table) {
            // Tambahkan kolom ini setelah 'jumlah'
            $table->integer('potongan_pajak')->default(0)->after('jumlah');
        });
    }

    public function down(): void
    {
        Schema::table('penarikan_dana', function (Blueprint $table) {
            $table->dropColumn('potongan_pajak');
        });
    }
};
