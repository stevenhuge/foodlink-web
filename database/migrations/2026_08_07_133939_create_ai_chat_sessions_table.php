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
        Schema::create('ai_chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('user_type')->nullable(); // 'user' or 'mitra'
            $table->unsignedBigInteger('user_id')->nullable(); 
            $table->string('title')->nullable(); // title for sidebar
            $table->timestamps();
            
            // Note: we don't use direct foreign key constraints here since user_id can point to users or mitras table depending on user_type.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_chat_sessions');
    }
};
