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
        Schema::create('work_messages', function (Blueprint $table) {
            $table->bigIncrements('message_id');

            $table->foreignId('conversation_id')
                ->constrained('work_conversations', 'conversation_id')
                ->onDelete('cascade');

            // el usuario que envía puede ser cliente o maestro → almacenamos user_id
            $table->foreignId('sender_id')
                ->constrained('users', 'user_id')
                ->onDelete('cascade');

            $table->text('content');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_messages');
    }
};
