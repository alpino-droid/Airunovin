<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'unit')) {
            // 1. Normalisasi data lama agar kompatibel dengan enum baru
            DB::table('products')->where('unit', 'like', '%rifle%')->update(['unit' => 'rifle']);
            DB::table('products')->where(function ($q) {
                $q->where('unit', 'like', '%shootgun%')
                  ->orWhere('unit', 'like', '%shotgun%');
            })->update(['unit' => 'shootgun']);
            DB::table('products')->where(function ($q) {
                $q->where('unit', 'like', '%machine%')
                  ->orWhere('unit', 'like', '%macine%');
            })->update(['unit' => 'macinegun']);
            DB::table('products')->where('unit', 'like', '%sniper%')->update(['unit' => 'sniper']);
            DB::table('products')->where(function ($q) {
                $q->where('unit', 'like', '%handgun%')
                  ->orWhere('unit', 'like', '%pistol%');
            })->update(['unit' => 'handgun']);

            // Bersihkan nilai yang tidak cocok menjadi null
            DB::table('products')
                ->whereNotNull('unit')
                ->whereNotIn('unit', ['rifle', 'shootgun', 'macinegun', 'sniper', 'handgun'])
                ->update(['unit' => null]);

            // 2. Ubah tipe kolom unit menjadi enum
            DB::statement("ALTER TABLE `products` MODIFY COLUMN `unit` ENUM('rifle', 'shootgun', 'macinegun', 'sniper', 'handgun') NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'unit')) {
            DB::statement("ALTER TABLE `products` MODIFY COLUMN `unit` VARCHAR(255) NULL");
        }
    }
};
