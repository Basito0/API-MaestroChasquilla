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
        Schema::create('work_conversations', function (Blueprint $table) {
            $table->bigIncrements('conversation_id');

            $table->foreignId('work_id')
                ->constrained('works', 'work_id')
                ->onDelete('cascade');

            $table->foreignId('client_id')
                ->constrained('clients', 'client_id')
                ->onDelete('cascade');

            $table->foreignId('worker_id')
                ->constrained('workers', 'worker_id')
                ->onDelete('cascade');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_conversations');
    }
};
