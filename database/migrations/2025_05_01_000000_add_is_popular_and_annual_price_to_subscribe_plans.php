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
        Schema::table('subscribe_plans', function (Blueprint $table) {
            $table->boolean('is_popular')->default(false)->after('is_active');
            $table->decimal('annual_price', 10, 2)->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscribe_plans', function (Blueprint $table) {
            $table->dropColumn(['is_popular', 'annual_price']);
        });
    }
};
