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
        Schema::create('drug_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('drug_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->string('dosage')->nullable();
            $table->string('instruction')->nullable();
            $table->string('status')->default('pending'); // pending, dispensed, cancelled
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Requester
            $table->string('bill_ref')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drug_requests');
    }
};
