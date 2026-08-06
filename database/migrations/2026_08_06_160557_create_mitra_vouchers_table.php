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
        Schema::create('mitra_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mitra_id')->constrained('mitra', 'mitra_id')->onDelete('cascade');
            $table->string('nama_voucher');
            $table->string('kode_voucher');
            $table->enum('tipe_potongan', ['persentase', 'nominal']);
            $table->decimal('nilai_potongan', 10, 2);
            $table->enum('alokasi', ['semua_menu', 'menu_tertentu']);
            $table->foreignId('produk_id')->nullable()->constrained('produk', 'produk_id')->onDelete('cascade');
            $table->integer('minimal_belanja')->default(0);
            $table->integer('kuota')->default(0);
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mitra_vouchers');
    }
};
