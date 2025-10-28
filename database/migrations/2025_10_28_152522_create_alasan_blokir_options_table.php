<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('alasan_blokir_options', function (Blueprint $table) {
            $table->id('alasan_id');
            $table->string('alasan_text')->unique(); // Teks alasan harus unik
            // Tidak perlu timestamps
        });
    }
    public function down(): void {
        Schema::dropIfExists('alasan_blokir_options');
    }
};
