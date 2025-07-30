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
        Schema::table('lead_url_shares', function (Blueprint $table) {
            $table->unsignedBigInteger('itinerary_id')->nullable()->after('lead_id');

            $table->foreign('itinerary_id')->references('id')->on('itineraries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_url_shares', function (Blueprint $table) {
            $table->dropColumn('itinerary_id');
        });
    }
};
