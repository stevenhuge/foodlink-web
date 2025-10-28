<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_usaha', function (Blueprint $table) {
            $table->id('kategori_usaha_id');
            $table->string('nama_kategori')->unique(); // Category name must be unique
            // No timestamps needed for simple categories
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_usaha');
    }
};
