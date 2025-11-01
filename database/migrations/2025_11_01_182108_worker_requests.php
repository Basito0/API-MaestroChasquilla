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
        Schema::create('worker_requests', function (Blueprint $table) {
            $table->bigIncrements('worker_request_id');
            $table->foreignId('worker_id')->nullable()->constrained('workers', 'worker_id')->onDelete('cascade');
            $table->string('title');
            $table->string('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("worker_requests");
    }
};
