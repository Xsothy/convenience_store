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
        Schema::table('addresses', function (Blueprint $table) {
            // Add email column after phone
            $table->string('email')->nullable()->after('phone');
            $table->boolean('is_default')->default(false)->after('postal_code');
            // Add additional_info column at the end
            $table->text('additional_info')->nullable()->after('postal_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn(['email', 'additional_info', 'is_default']);
        });
    }
};
