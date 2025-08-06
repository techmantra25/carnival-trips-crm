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
        DB::table('leads')
            ->update(['lead_type' => 'Website']);

        Schema::table('leads', function (Blueprint $table) {
            $table->enum('lead_type', ['Facebook', 'Instagram', 'Website'])
                ->default('Website')
                ->after('meal_type')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('lead_type')->change();
        });
    }
};
