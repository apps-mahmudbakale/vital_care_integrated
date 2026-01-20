<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vital_references', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('min_value')->nullable();
            $table->string('max_value')->nullable();
            $table->string('unit')->nullable();
            $table->timestamps();
        });

        // Seed default normal ranges
        DB::table('vital_references')->insert([
            ['name' => 'Temperature', 'min_value' => '36.5', 'max_value' => '37.5', 'unit' => '°C', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pulse', 'min_value' => '60', 'max_value' => '100', 'unit' => 'bpm', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Respiration', 'min_value' => '12', 'max_value' => '20', 'unit' => 'breaths/min', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Systolic BP', 'min_value' => '90', 'max_value' => '120', 'unit' => 'mmHg', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Diastolic BP', 'min_value' => '60', 'max_value' => '80', 'unit' => 'mmHg', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SpO2', 'min_value' => '95', 'max_value' => '100', 'unit' => '%', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vital_references');
    }
};
