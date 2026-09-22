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
        if (Schema::hasTable('product_purchases') && !Schema::hasColumn('product_purchases', 'id_seller')) {
            Schema::table('product_purchases', function (Blueprint $table) {
                $table->foreignId('id_seller')->nullable()->after('id_user')->constrained('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('product_purchases') && Schema::hasColumn('product_purchases', 'id_seller')) {
            Schema::table('product_purchases', function (Blueprint $table) {
                $table->dropConstrainedForeignId('id_seller');
            });
        }
    }
};
