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
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('message_id');

            // Relación con conversación
            $table->foreignId('conversation_id')
                  ->constrained('conversations', 'conversation_id')
                  ->onDelete('cascade');

            // Relación con usuario que envía
            $table->foreignId('sender_id')
                  ->constrained('users', 'user_id')
                  ->onDelete('cascade');

            // Contenido del mensaje
            $table->text('content');

            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
