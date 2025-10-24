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
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->id('detail_id');

            $table->foreignId('transaksi_id')
                  ->constrained('transaksi', 'transaksi_id')
                  ->onDelete('cascade');

            $table->foreignId('produk_id')
                  ->constrained('produk', 'produk_id')
                  ->onDelete('cascade');

            $table->integer('jumlah');
            $table->decimal('harga_saat_transaksi', 10, 2);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi');
    }
};
