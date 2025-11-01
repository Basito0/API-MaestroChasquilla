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
        Schema::create('reviews', function (Blueprint $table) {
            $table->bigIncrements('review_id');
            $table->foreignId('reviewer_id')->nullable()->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('reviewed_id')->nullable()->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('title');
            $table->string('description');
            $table->integer('score')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
