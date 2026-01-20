<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds a nullable 'tag_id' column to the 'patients' table.
     */
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            if (!Schema::hasColumn('patients', 'tag_id')) {
                $table->integer('tag_id')->nullable();
                // Optional: Add foreign key constraint if 'tag_id' references a 'tags' table
                // $table->foreign('tag_id')->references('id')->on('tags')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     * Drops the 'tag_id' column from the 'patients' table.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            if (Schema::hasColumn('patients', 'tag_id')) {
                // Drop foreign key first if it was added
                // $table->dropForeign(['tag_id']);
                $table->dropColumn('tag_id');
            }
        });
    }
};
