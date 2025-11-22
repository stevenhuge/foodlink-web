<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penyanggahan_mitra', function (Blueprint $table) {
            $table->id('sanggahan_id');
            $table->unsignedBigInteger('mitra_id');
            $table->text('alasan_sanggah');
            $table->json('bukti_files')->nullable(); // Menyimpan path file (bisa banyak)
            $table->enum('status', ['Pending', 'Disetujui', 'Ditolak'])->default('Pending');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();

            $table->foreign('mitra_id')->references('mitra_id')->on('mitra')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyanggahan_mitra');
    }
};
