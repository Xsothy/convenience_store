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
            $table->dropColumn('period');
            $table->text('description')->nullable()->after('name');
            $table->json('features_list')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscribe_plans', function (Blueprint $table) {
            $table->integer('period')->nullable();
            $table->dropColumn(['description', 'features_list']);
        });
    }
};
