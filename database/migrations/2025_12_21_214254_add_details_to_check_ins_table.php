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
        Schema::table('check_ins', function (Blueprint $table) {
            if (!Schema::hasColumn('check_ins', 'clinic_id')) {
                $table->foreignId('clinic_id')->nullable()->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('check_ins', 'appointment_type_id')) {
                $table->foreignId('appointment_type_id')->nullable()->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('check_ins', 'specialty_id')) {
                $table->foreignId('specialty_id')->nullable()->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('check_ins', 'presenting_complaint')) {
                $table->longText('presenting_complaint')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('check_ins', function (Blueprint $table) {
            $table->dropForeign(['clinic_id']);
            $table->dropForeign(['appointment_type_id']);
            $table->dropForeign(['specialty_id']);
            $table->dropColumn(['clinic_id', 'appointment_type_id', 'specialty_id', 'presenting_complaint']);
        });
    }
};
