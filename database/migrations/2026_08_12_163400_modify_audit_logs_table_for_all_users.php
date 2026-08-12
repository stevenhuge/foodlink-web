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
        Schema::table('audit_logs', function (Blueprint $table) {
            // Hapus foreign key dan kolom lama
            $table->dropForeign(['admin_id']);
            $table->dropColumn(['admin_id', 'admin_name']);

            // Tambah kolom baru
            $table->string('user_type')->nullable()->after('id'); // 'Admin', 'Mitra', 'User', 'Guest'
            $table->unsignedBigInteger('user_id')->nullable()->after('user_type');
            $table->string('user_name')->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropColumn(['user_type', 'user_id', 'user_name']);
            
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('admin_name')->nullable();
            $table->foreign('admin_id')->references('admin_id')->on('admins')->onDelete('set null');
        });
    }
};
