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
        Schema::create('works', function (Blueprint $table) {
            $table->bigIncrements('work_id');
            $table->foreignId('client_request_id')->nullable()->constrained('client_requests', 'client_request_id')->onDelete('cascade');
            $table->foreignId('client_id')->nullable()->constrained('clients', 'client_id')->onDelete('cascade');
            $table->foreignId('worker_id')->nullable()->constrained('workers', 'worker_id')->onDelete('cascade');
            $table->string('state');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("works");
    }
};
