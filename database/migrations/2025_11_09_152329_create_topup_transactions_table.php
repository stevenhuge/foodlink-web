<?php
// database/migrations/xxxx_create_topup_transactions_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('topup_transactions', function (Blueprint $table) {
            $table->string('topup_id')->primary(); // ID Unik, misal: "TOPUP-USER-1-TIMESTAMP"
            // Pastikan constrained() merujuk ke 'users' dan 'user_id'
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->unsignedBigInteger('amount'); // Jumlah uang (Rupiah)
            $table->integer('poin_didapat'); // Jumlah poin yang akan didapat
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->string('payment_gateway_ref')->nullable(); // ID dari Midtrans
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('topup_transactions'); }
};
