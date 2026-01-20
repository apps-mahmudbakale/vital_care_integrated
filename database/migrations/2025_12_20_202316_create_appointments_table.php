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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_type_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('clinic_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('specialty_id')->nullable()->constrained()->onDelete('set null');
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->boolean('is_all_day')->default(false);
            $table->boolean('is_follow_up')->default(false);
            $table->string('status')->default('Scheduled');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
