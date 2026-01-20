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
        Schema::create('hmo_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hmo_id')->references('id')->on('hmo_groups')->onDelete('cascade');
            $table->string('enrollment_amount')->nullable();
            $table->string('signup_amount')->nullable();
            $table->integer('max_no')->nullable();
            $table->boolean('is_insurance')->default(false);
            $table->string('logo')->nullable();
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
        Schema::dropIfExists('hmo_plans');
    }
};
