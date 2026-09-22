<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->string('nama');
            $table->unsignedBigInteger('harga');
            $table->string('merk');
            $table->enum('unit', ['rifle', 'shootgun', 'macinegun', 'sniper', 'handgun'])->nullable();
            $table->string('sparepart')->nullable();
            $table->string('aksesoris')->nullable();
            $table->string('jenis')->nullable();
            $table->string('kondisi');
            $table->unsignedInteger('stok')->default(1);
            $table->string('lokasi');
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->enum('status', ['panding', 'tolak', 'diterima'])->default('panding');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
