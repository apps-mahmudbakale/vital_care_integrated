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
            $table->dropColumn('generic_name');
            $table->foreignId('generic_id')->nullable()->constrained('drug_generics')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drug_requests', function (Blueprint $table) {
            $table->dropForeign(['generic_id']);
            $table->dropColumn('generic_id');
            $table->string('generic_name')->nullable();
        });
    }
};
