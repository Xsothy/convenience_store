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
        Schema::create('types', function (Blueprint $table) {
            $table->id();
            $table->integer('order')->default(0);

            $table->foreignId('parent_id')->nullable()->references('id')->on('types')->onDelete('cascade');

            // Morph
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();

            // Types
            $table->string('for')->default('posts')->nullable();
            $table->string('type')->default('category')->nullable();

            $table->string('name');
            $table->string('key')->index();
            $table->text('description')->nullable();

            // Icon & Color
            $table->string('color')->nullable();
            $table->string('icon')->nullable();

            $table->boolean('is_activated')->default(true)->nullable();
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
        Schema::dropIfExists('types');
    }
};
