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
        Schema::create('bantuan_penerima', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bantuan_id')->constrained('bantuan')->onDelete('cascade');
            $table->foreignId('penerima_id')->constrained('penerima')->onDelete('cascade');
            $table->date('tanggal_diberikan')->nullable();
            $table->timestamps();
            
            // Prevent duplicate relationships
            $table->unique(['bantuan_id', 'penerima_id']);
            
            // Performance indexes
            $table->index('bantuan_id');
            $table->index('penerima_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bantuan_penerima');
    }
};
