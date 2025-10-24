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
        Schema::create('barter', function (Blueprint $table) {
            $table->id('barter_id');
            $table->enum('tipe_barter', ['User-Mitra', 'Mitra-Mitra']);

            $table->foreignId('pengaju_user_id')
                  ->nullable()
                  ->constrained('users', 'user_id')
                  ->onDelete('cascade');

            $table->foreignId('pengaju_mitra_id')
                  ->nullable()
                  ->constrained('mitra', 'mitra_id')
                  ->onDelete('cascade');

            $table->foreignId('penerima_mitra_id')
                  ->constrained('mitra', 'mitra_id')
                  ->onDelete('cascade');

            $table->text('barang_ditawarkan');

            $table->foreignId('produk_diminta_id')
                  ->nullable()
                  ->constrained('produk', 'produk_id')
                  ->onDelete('set null');

            $table->text('barang_diminta')->nullable();

            $table->enum('status_barter', ['Diajukan', 'Diterima', 'Ditolak', 'Selesai'])
                  ->default('Diajukan');

            $table->timestamp('waktu_pengajuan')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barter');
    }
};
