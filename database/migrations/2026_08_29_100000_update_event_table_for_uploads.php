<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('event')) {
            return;
        }

        DB::statement('ALTER TABLE event MODIFY tanggal DATE NULL');
        DB::statement('ALTER TABLE event MODIFY lokasi VARCHAR(255) NULL');
        DB::statement('ALTER TABLE event MODIFY sumber VARCHAR(255) NULL');
        DB::statement('ALTER TABLE event MODIFY htm INT NULL');
        DB::statement('ALTER TABLE event MODIFY deskripsi TEXT NULL');
        DB::statement('ALTER TABLE event MODIFY poster JSON NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('event')) {
            return;
        }

        DB::statement('ALTER TABLE event MODIFY tanggal VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE event MODIFY lokasi VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE event MODIFY sumber VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE event MODIFY htm INT NOT NULL');
        DB::statement('ALTER TABLE event MODIFY deskripsi VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE event MODIFY poster VARCHAR(255) NOT NULL');
    }
};
