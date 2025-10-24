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
        Schema::create('voucher', function (Blueprint $table) {
            $table->id('voucher_id');
            $table->string('kode_voucher')->unique();
            $table->text('deskripsi');
            $table->enum('tipe_voucher', ['PotonganHarga', 'GratisProduk']);
            $table->decimal('nilai_potongan', 10, 2)->nullable();
            $table->integer('poin_dibutuhkan');
            $table->integer('stok_voucher');
            $table->date('berlaku_hingga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher');
    }
};
