<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barter', function (Blueprint $table) {
            // Tambahkan kolom setelah produk_ditawarkan_id
            // Hanya relevan jika produk_ditawarkan_id diisi (Opsi 1)
            $table->integer('jumlah_ditawarkan')->unsigned()->nullable()->after('produk_ditawarkan_id');
        });
    }

    public function down(): void
    {
        Schema::table('barter', function (Blueprint $table) {
            $table->dropColumn('jumlah_ditawarkan');
        });
    }
};
