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
        if (!Schema::hasTable('product_purchases')) {
            Schema::create('product_purchases', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
                $table->foreignId('id_product')->nullable()->constrained('products')->onDelete('set null');
                $table->unsignedBigInteger('id_marketplace')->nullable();
                $table->string('order_code', 50)->unique();
                $table->string('nama_produk');
                $table->string('gambar_produk')->nullable();
                $table->string('nama_penjual')->nullable();
                $table->unsignedBigInteger('harga_satuan')->default(0);
                $table->integer('jumlah')->default(1);
                $table->unsignedBigInteger('biaya_pengiriman')->default(0);
                $table->unsignedBigInteger('total_harga')->default(0);
                $table->string('metode_pengiriman')->nullable();
                $table->string('metode_pembayaran')->nullable();
                $table->string('nama_pembeli');
                $table->string('no_wa_pembeli');
                $table->string('status', 50)->default('Selesai');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_purchases');
    }
};
