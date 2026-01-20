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
        Schema::table('diagnoses', function (Blueprint $table) {
            $table->foreignId('icd_10_id')->nullable()->after('check_in_id')->constrained('icd10s')->onDelete('cascade');
            $table->string('status')->nullable()->after('notes');
            $table->dropColumn(['diagnosis_name', 'diagnosis_type']);
        });

        Schema::table('check_ins', function (Blueprint $table) {
            $table->dropColumn(['presenting_complaint', 'treatment_plan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('check_ins', function (Blueprint $table) {
            $table->longText('presenting_complaint')->nullable();
            $table->longText('treatment_plan')->nullable();
        });

        Schema::table('diagnoses', function (Blueprint $table) {
            $table->text('diagnosis_name')->nullable();
            $table->string('diagnosis_type')->default('provisional');
            $table->dropForeign(['icd_10_id']);
            $table->dropColumn(['icd_10_id', 'status']);
        });
    }
};
