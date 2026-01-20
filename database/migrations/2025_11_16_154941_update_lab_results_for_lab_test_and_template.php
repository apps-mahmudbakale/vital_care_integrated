<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  public function up(): void
  {
    // 1. If lab_id exists, create lab_test_id and copy data
    if (Schema::hasColumn('lab_results', 'lab_id')) {

      Schema::table('lab_results', function (Blueprint $table) {
        // Create the new column (same type)
        $table->unsignedBigInteger('lab_test_id')
          ->nullable()
          ->after('lab_id');
      });

      // Copy old values into new column
      DB::statement("UPDATE lab_results SET lab_test_id = lab_id");

      // Drop old column
      Schema::table('lab_results', function (Blueprint $table) {
        $table->dropColumn('lab_id');
      });

      // Make lab_test_id NOT NULL (optional)
      Schema::table('lab_results', function (Blueprint $table) {
        $table->unsignedBigInteger('lab_test_id')->nullable(false)->change();
      });
    }

    // 2. Add lab_template_id
    Schema::table('lab_results', function (Blueprint $table) {
      if (!Schema::hasColumn('lab_results', 'lab_template_id')) {
        $table->foreignId('lab_template_id')
          ->after('lab_test_id')
          ->constrained('lab_templates')
          ->cascadeOnDelete();
      }
    });

    // 3. Add pathologist_comments
    Schema::table('lab_results', function (Blueprint $table) {
      if (!Schema::hasColumn('lab_results', 'pathologist_comments')) {
        $table->text('pathologist_comments')
          ->nullable()
          ->after('patient_id');
      }
    });

    // 4. Drop old result column (optional)
    if (Schema::hasColumn('lab_results', 'result')) {
      Schema::table('lab_results', function (Blueprint $table) {
        $table->dropColumn('result');
      });
    }
  }

  public function down(): void
  {
    Schema::table('lab_results', function (Blueprint $table) {

      if (Schema::hasColumn('lab_results', 'lab_template_id')) {
        $table->dropForeign(['lab_template_id']);
        $table->dropColumn('lab_template_id');
      }

      if (Schema::hasColumn('lab_results', 'pathologist_comments')) {
        $table->dropColumn('pathologist_comments');
      }
    });

    // Reverse rename: lab_test_id -> lab_id
    if (Schema::hasColumn('lab_results', 'lab_test_id')) {

      Schema::table('lab_results', function (Blueprint $table) {
        $table->unsignedBigInteger('lab_id')->nullable()->after('id');
      });

      DB::statement("UPDATE lab_results SET lab_id = lab_test_id");

      Schema::table('lab_results', function (Blueprint $table) {
        $table->dropColumn('lab_test_id');
      });

      Schema::table('lab_results', function (Blueprint $table) {
        $table->unsignedBigInteger('lab_id')->nullable(false)->change();
      });
    }
  }
};
