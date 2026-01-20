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
    Schema::create('lab_template_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('lab_template_id')->constrained()->cascadeOnDelete();
      $table->foreignId('lab_parameter_id')->constrained()->cascadeOnDelete();
      $table->string('reference')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('lab_template_items');
  }
};
