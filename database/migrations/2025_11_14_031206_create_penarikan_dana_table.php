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
        // Tabel ini untuk mencatat request penarikan dana
        Schema::create('penarikan_dana', function (Blueprint $table) {
            $table->id('penarikan_id');
            $table->morphs('penarikanable'); // 'mitra_id' atau 'admin_id'
            $table->foreignId('rekening_bank_id')->constrained('rekening_bank', 'rekening_id');
            $table->bigInteger('jumlah');
            $table->enum('status', ['Pending', 'Diproses', 'Selesai', 'Ditolak'])->default('Pending');
            $table->text('catatan_admin')->nullable(); // Untuk alasan penolakan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penarikan_dana');
    }
};
