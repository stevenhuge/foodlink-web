<?php
// database/migrations/xxxx_add_status_akun_to_mitra_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('mitra', function (Blueprint $table) {
            // Tambahkan kolom setelah status_verifikasi
            $table->enum('status_akun', ['Aktif', 'Diblokir'])
                  ->default('Aktif') // Defaultnya aktif saat daftar
                  ->after('status_verifikasi');
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::table('mitra', function (Blueprint $table) {
            $table->dropColumn('status_akun');
        });
    }
};
