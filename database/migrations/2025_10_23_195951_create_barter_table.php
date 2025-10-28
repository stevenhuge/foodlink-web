<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barter', function (Blueprint $table) {
            $table->id('barter_id');
            $table->enum('tipe_barter', ['User-Mitra', 'Mitra-Mitra']);
            $table->foreignId('pengaju_user_id')->nullable()->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('pengaju_mitra_id')->nullable()->constrained('mitra', 'mitra_id')->onDelete('cascade');
            $table->foreignId('penerima_mitra_id')->constrained('mitra', 'mitra_id')->onDelete('cascade');
            $table->foreignId('produk_diminta_id')->nullable()->constrained('produk', 'produk_id')->onDelete('set null');
            $table->text('barang_diminta')->nullable();
            $table->foreignId('produk_ditawarkan_id')->nullable()->constrained('produk', 'produk_id')->onDelete('set null');
            $table->string('nama_barang_manual')->nullable()->default(null); // <-- Default Null
            $table->text('deskripsi_barang_manual')->nullable()->default(null); // <-- Default Null
            $table->string('foto_barang_manual')->nullable()->default(null); // <-- Default Null
            $table->string('bukti_struk')->nullable()->default(null); // <-- Default Null
            $table->enum('status_barter', ['Diajukan', 'Diterima', 'Ditolak', 'Selesai', 'Dibatalkan'])->default('Diajukan');
            $table->timestamp('waktu_pengajuan')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barter');
    }
};
