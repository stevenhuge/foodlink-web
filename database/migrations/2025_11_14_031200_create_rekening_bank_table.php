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
    // Tabel ini untuk menyimpan data rekening Mitra dan SuperAdmin
        Schema::create('rekening_bank', function (Blueprint $table) {
            $table->id('rekening_id');
            // Ini adalah 'Polymorphic Relationship'
            // 'rekeningable_id' bisa jadi 'mitra_id' ATAU 'admin_id'
            // 'rekeningable_type' akan berisi 'App\Models\Mitra' ATAU 'App\Models\Admin'
            $table->morphs('rekeningable');
            $table->string('nama_bank');
            $table->string('nomor_rekening');
            $table->string('nama_pemilik');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekening_bank');
    }
};
