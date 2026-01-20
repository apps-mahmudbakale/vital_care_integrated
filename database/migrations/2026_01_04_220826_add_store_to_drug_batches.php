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
        Schema::table('drug_batches', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->after('id')->constrained('pharmacy_stores')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drug_batches', function (Blueprint $table) {
            //
        });
    }
};
