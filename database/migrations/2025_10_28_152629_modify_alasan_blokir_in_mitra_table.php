<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('mitra', function (Blueprint $table) {
            // Hapus kolom teks lama jika sudah terlanjur dibuat
            if (Schema::hasColumn('mitra', 'alasan_blokir')) {
                 $table->dropColumn('alasan_blokir');
            }
            // Tambah foreign key baru
            $table->foreignId('alasan_blokir_option_id')
                  ->nullable()
                  ->after('status_akun')
                  ->constrained('alasan_blokir_options', 'alasan_id')
                  ->onDelete('set null'); // Jika alasan dihapus, set null di mitra
        });
    }
    public function down(): void {
        Schema::table('mitra', function (Blueprint $table) {
            $table->dropForeign(['alasan_blokir_option_id']);
            $table->dropColumn('alasan_blokir_option_id');
            // Tambahkan kembali kolom teks jika rollback (opsional)
            // $table->text('alasan_blokir')->nullable()->after('status_akun');
        });
    }
};
