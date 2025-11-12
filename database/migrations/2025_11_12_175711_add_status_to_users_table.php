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
    Schema::table('users', function (Blueprint $table) {
        // Menambahkan kolom status_akun setelah poin_reward
        $table->string('status_akun')->default('aktif')->after('poin_reward');
        // Menambahkan kolom untuk menyimpan alasan jika diblokir
        $table->string('alasan_blokir')->nullable()->after('status_akun');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
