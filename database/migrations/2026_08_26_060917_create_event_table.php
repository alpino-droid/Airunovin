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
        Schema::create('event', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->string('nama');
            $table->date('tanggal');
            $table->string('penyelenggara');
            $table->string('lokasi')->nullable();
            $table->unsignedBigInteger('id_provinsi')->nullable();
            $table->string('kota');
            $table->string('sumber')->nullable();
            $table->integer('htm')->nullable();
            $table->text('deskripsi')->nullable();
            $table->json('poster')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event');
    }
};
