<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom ENUM 'Laki-laki' dan 'Perempuan'
            // Kita buat nullable() dulu agar tidak error pada user lama
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])
                  ->nullable()
                  ->after('nama_lengkap');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('jenis_kelamin');
        });
    }
};
