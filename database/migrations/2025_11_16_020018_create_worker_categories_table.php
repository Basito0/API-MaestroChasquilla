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
        Schema::create('worker_categories', function (Blueprint $table) {
            $table->bigIncrements('worker_category_id');
            $table->foreignId('worker_id')->constrained('workers', 'worker_id')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories', 'category_id')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worker_categories');
    }
};
