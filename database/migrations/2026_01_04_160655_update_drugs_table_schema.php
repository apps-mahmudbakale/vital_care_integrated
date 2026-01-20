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
        Schema::table('drugs', function (Blueprint $table) {
            // Drop existing columns that are not needed
            if (Schema::hasColumn('drugs', 'generic_name')) {
                $table->dropColumn('generic_name');
            }
            
            // Add new columns if they don't exist
            if (!Schema::hasColumn('drugs', 'price')) {
                $table->string('price')->after('name');
            }
            
            if (!Schema::hasColumn('drugs', 'threshold')) {
                $table->string('threshold')->after('price');
            }

            if (!Schema::hasColumn('drugs', 'weight')) {
                $table->string('weight')->nullable()->after('threshold');
            }

            if (!Schema::hasColumn('drugs', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('weight');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drugs', function (Blueprint $table) {
            //
        });
    }
};
