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
        Schema::create('lokasis', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->integer('user_id')->nullable();
            $table->integer('ulasan_id')->nullable();

            $table->string('nama_tempat')->nullable();
            $table->string('alamat')->nullable();
            $table->string('created_by')->nullable();
            $table->double('lat')->nullable();
            $table->double('long')->nullable();
            $table->json('image_paths')->nullable(); // Kolom untuk menyimpan path gambar
            $table->string('rute')->nullable();
            $table->string('perlengkapan')->nullable();
            $table->string('umpan')->nullable();
            $table->string('jenis_ikan')->nullable();

         
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokasis');
    }
};
