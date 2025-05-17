<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('type')->default('system')->nullable();
            $table->string('session_id')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');

            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->onDelete('cascade');
            $table->foreignId('shipper_id')->nullable()->constrained('deliveries')->onDelete('cascade');
            $table->foreignId('shipping_vendor_id')->nullable()->constrained('shipping_vendors')->onDelete('cascade');

            $table->string('name')->nullable();
            $table->string('phone')->nullable();

            // address
            $table->string('flat')->nullable();
            $table->text('address')->nullable();
            $table->string('country')->nullable();
            $table->string('area')->nullable();
            $table->string('city')->nullable();

            // Sales From
            $table->string('source')->default('system');

            // Shipping
            $table->string('shipper_vendor')->nullable();

            // Prices
            $table->double('total')->default(0)->nullable();
            $table->double('discount')->default(0)->nullable();
            $table->double('shipping')->default(0)->nullable();
            $table->double('vat')->default(0)->nullable();

            // Status
            $table->string('status')->default('pending')->nullable();

            // Options
            $table->boolean('is_approved')->default(0)->nullable();
            $table->boolean('is_closed')->default(0)->nullable();
            $table->boolean('is_on_table')->default(0)->nullable();
            $table->string('table')->nullable();

            $table->text('notes')->nullable();

            $table->boolean('has_returns')->default(0)->nullable();
            $table->double('return_total')->default(0)->nullable();
            $table->string('reason')->nullable();

            // Payments
            $table->boolean('is_payed')->default(0)->nullable();
            $table->string('payment_method')->nullable();

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
