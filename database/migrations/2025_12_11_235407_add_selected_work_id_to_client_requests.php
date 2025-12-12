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
        Schema::table('client_requests', function (Blueprint $table) {
            $table->foreignId('selected_work_id')
                ->nullable()
                ->constrained('works', 'work_id')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_requests', function (Blueprint $table) {
            // eliminar la FK y la columna
            $table->dropConstrainedForeignId('selected_work_id');
        });
    }
};

