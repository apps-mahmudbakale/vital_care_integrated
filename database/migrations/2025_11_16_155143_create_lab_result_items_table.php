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
    Schema::create('lab_result_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('lab_result_id')->constrained()->cascadeOnDelete();
      $table->foreignId('lab_template_item_id')->constrained()->cascadeOnDelete();
      $table->string('value');
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('lab_result_items');
  }
};
