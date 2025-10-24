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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('transaksi_id');

            $table->foreignId('user_id')
                  ->constrained('users', 'user_id')
                  ->onDelete('cascade');

            $table->foreignId('mitra_id')
                  ->constrained('mitra', 'mitra_id')
                  ->onDelete('cascade');

            $table->string('kode_pemesanan')->unique();
            $table->decimal('total_harga', 10, 2);
            $table->integer('poin_didapat')->default(0);
            $table->string('metode_pembayaran', 50)->nullable();
            $table->enum('status_pemesanan', ['Pending', 'Paid', 'Siap Diambil', 'Selesai', 'Batal'])
                  ->default('Pending');
            $table->timestamp('waktu_pemesanan')->useCurrent();
            $table->timestamp('waktu_pengambilan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
