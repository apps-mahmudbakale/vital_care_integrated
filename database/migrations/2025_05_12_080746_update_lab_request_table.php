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
      Schema::table('lab_requests', function (Blueprint $table) {
        $table->string('request_ref')->nullable()->after('request_note');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('lab_requests', function (Blueprint $table) {
        if (Schema::hasColumn('lab_requests', 'request_ref')) {
          $table->dropColumn('request_ref');
        }
      });
    }
};
