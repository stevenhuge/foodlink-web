<?php
// database/migrations/xxxx_add_alasan_blokir_to_mitra_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mitra', function (Blueprint $table) {
            // Tambahkan kolom setelah status_akun, boleh kosong (nullable)
            $table->text('alasan_blokir')->nullable()->after('status_akun');
        });
    }
    public function down(): void
    {
        Schema::table('mitra', function (Blueprint $table) {
            $table->dropColumn('alasan_blokir');
        });
    }
};
