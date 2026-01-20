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
        // Add unit to lab_template_items
        if (Schema::hasTable('lab_template_items')) {
            Schema::table('lab_template_items', function (Blueprint $table) {
                if (!Schema::hasColumn('lab_template_items', 'unit')) {
                    $table->string('unit')->nullable()->after('reference');
                }
            });
        }

        // Add template_id to lab_tests
        if (Schema::hasTable('lab_tests')) {
            Schema::table('lab_tests', function (Blueprint $table) {
                if (!Schema::hasColumn('lab_tests', 'template_id')) {
                    $table->foreignId('template_id')->nullable()->constrained('lab_templates')->onDelete('set null');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('lab_template_items')) {
            Schema::table('lab_template_items', function (Blueprint $table) {
                $table->dropColumn('unit');
            });
        }

        if (Schema::hasTable('lab_tests')) {
            Schema::table('lab_tests', function (Blueprint $table) {
                $table->dropForeign(['template_id']);
                $table->dropColumn('template_id');
            });
        }
    }
};
