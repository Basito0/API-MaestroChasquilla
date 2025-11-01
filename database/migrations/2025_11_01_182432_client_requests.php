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
        Schema::create('client_requests', function (Blueprint $table) {
            $table->bigIncrements('client_request_id');
            $table->foreignId('client_id')->nullable()->constrained('clients', 'client_id')->onDelete('cascade');
            $table->string('title');
            $table->string('description');
            $table->integer('budget')->default(0);
            $table->string('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("client_requests");
    }
};
