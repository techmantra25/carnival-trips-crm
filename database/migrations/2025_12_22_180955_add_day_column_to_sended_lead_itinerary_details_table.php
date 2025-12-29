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
        Schema::table('sended_lead_itinerary_details', function (Blueprint $table) {
            $table->string('day')
                  ->nullable()
                  ->after('cab_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sended_lead_itinerary_details', function (Blueprint $table) {
            $table->dropColumn('day');
        });
    }
};
