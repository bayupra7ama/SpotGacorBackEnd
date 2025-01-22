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
        Schema::create('tabel_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lokasi_id'); // Relasi dengan tabel Lokasi
            $table->string('url'); // Path gambar yang disimpan

            $table->timestamps();
            $table->foreign('lokasi_id')->references('id')->on('lokasis')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabel_photos');
    }
};
