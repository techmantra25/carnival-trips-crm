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
       // First, drop the unique index on the 'step' column
        Schema::table('trip_preference_questions', function (Blueprint $table) {
            $table->dropUnique(['step']); // Assumes the index is named automatically
        });

        // Then modify the column to set default value
        Schema::table('trip_preference_questions', function (Blueprint $table) {
            $table->unsignedInteger('step')->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         // Rollback: remove default, re-add unique constraint
        Schema::table('trip_preference_questions', function (Blueprint $table) {
            $table->unsignedInteger('step')->default(null)->change();
            $table->unique('step');
        });
    }
};
