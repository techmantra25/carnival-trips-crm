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
            $table->unsignedBigInteger('sended_lead_itinerary_id')
                ->after('itinerary_id')->nullable()
                ->comment('Foreign key referencing sended_lead_itineraries table');

            $table->foreign('sended_lead_itinerary_id')
                ->references('id')
                ->on('sended_lead_itineraries')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_url_shares', function (Blueprint $table) {
            $table->dropForeign(['sended_lead_itinerary_id']);
            $table->dropColumn('sended_lead_itinerary_id');
        });
    }
};
