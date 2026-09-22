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
        $tables = ['clubs', 'event', 'products', 'marketplaces'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'status')) {
                // 1. Expand enum to include both 'diterima' and 'terimakasih'
                DB::statement("ALTER TABLE `{$table}` MODIFY COLUMN `status` ENUM('panding', 'tolak', 'diterima', 'terimakasih') NOT NULL DEFAULT 'panding'");

                // 2. Convert any existing 'terimakasih' records to 'diterima'
                DB::table($table)->where('status', 'terimakasih')->update(['status' => 'diterima']);

                // 3. Set strict enum to ['panding', 'tolak', 'diterima']
                DB::statement("ALTER TABLE `{$table}` MODIFY COLUMN `status` ENUM('panding', 'tolak', 'diterima') NOT NULL DEFAULT 'panding'");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['clubs', 'event', 'products', 'marketplaces'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'status')) {
                DB::statement("ALTER TABLE `{$table}` MODIFY COLUMN `status` ENUM('panding', 'tolak', 'terimakasih') NOT NULL DEFAULT 'panding'");
                DB::table($table)->where('status', 'diterima')->update(['status' => 'terimakasih']);
            }
        }
    }
};
