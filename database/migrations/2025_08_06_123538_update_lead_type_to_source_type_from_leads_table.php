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
        Schema::table('leads', function (Blueprint $table) {
            // Drop old column
            $table->dropColumn('lead_type');
        });

        Schema::table('leads', function (Blueprint $table) {
            // Add new column
            $table->enum('source_type', ['Facebook', 'Instagram', 'Website'])
                  ->default('Website')
                  ->after('meal_type');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Drop new column
            $table->dropColumn('source_type');
        });

        Schema::table('leads', function (Blueprint $table) {
            // Re-add original column
            $table->string('lead_type')->nullable()->after('meal_type');
        });
    }
};
