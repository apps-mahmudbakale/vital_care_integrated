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
        Schema::table('drug_requests', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->constrained('pharmacy_stores')->onDelete('set null');
            $table->string('frequency')->nullable();
            $table->integer('duration')->nullable();
            $table->string('duration_unit')->nullable();
            $table->text('notes')->nullable();
            $table->string('generic_name')->nullable();
            $table->foreignId('drug_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drug_requests', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->dropColumn(['store_id', 'frequency', 'duration', 'duration_unit', 'notes', 'generic_name']);
            $table->foreignId('drug_id')->nullable(false)->change();
        });
    }
};
