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
        Schema::create('penerima', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255);
            $table->string('nik', 16)->unique();
            $table->text('alamat');
            $table->string('kelurahan', 255);
            $table->string('kecamatan', 255);
            $table->string('jenis', 50);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerima');
    }
};
