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
        Schema::create('produk', function (Blueprint $table) {
            $table->id('produk_id');

            $table->foreignId('mitra_id')
                  ->constrained('mitra', 'mitra_id')
                  ->onDelete('cascade');

            $table->foreignId('kategori_id')
                  ->constrained('kategori_produk', 'kategori_id')
                  ->onDelete('restrict');

            $table->string('nama_produk');
            $table->text('deskripsi')->nullable();
            $table->string('foto_produk')->nullable();
            $table->decimal('harga_normal', 10, 2)->default(0);
            $table->decimal('harga_diskon', 10, 2)->default(0);
            $table->enum('tipe_penawaran', ['Jual-Cepat', 'Donasi']);
            $table->integer('stok_awal');
            $table->integer('stok_tersisa');
            $table->dateTime('waktu_kadaluarsa');
            $table->dateTime('waktu_ambil_mulai');
            $table->dateTime('waktu_ambil_selesai');
            $table->enum('status_produk', ['Tersedia', 'Habis', 'Ditarik'])->default('Tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
