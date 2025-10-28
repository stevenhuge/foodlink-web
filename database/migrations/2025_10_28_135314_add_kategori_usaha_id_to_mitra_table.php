<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mitra', function (Blueprint $table) {
            // Add the foreign key, make it nullable for existing mitra
            $table->foreignId('kategori_usaha_id')
                  ->nullable() // Allow null initially
                  ->after('deskripsi') // Place it after description column (optional)
                  ->constrained('kategori_usaha', 'kategori_usaha_id')
                  ->onDelete('set null'); // If category is deleted, set mitra's category to null
        });
    }

    public function down(): void
    {
        Schema::table('mitra', function (Blueprint $table) {
            $table->dropForeign(['kategori_usaha_id']);
            $table->dropColumn('kategori_usaha_id');
        });
    }
};
