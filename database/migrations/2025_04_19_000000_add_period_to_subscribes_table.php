<?php

use App\Enums\SubscriptionPeriod;
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
        Schema::table('subscribes', function (Blueprint $table) {
            $table->string('period')->after('plan_id')->default(SubscriptionPeriod::MONTHLY->value);
            $table->date('start_at')->nullable()->after('plan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscribes', function (Blueprint $table) {
            $table->dropColumn('period');
            $table->dropColumn('start_at');
        });
    }
};
