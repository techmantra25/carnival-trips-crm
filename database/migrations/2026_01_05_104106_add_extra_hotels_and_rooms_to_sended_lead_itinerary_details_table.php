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
            $table->json('extra_hotels_and_rooms')
                  ->nullable()
                  ->after('ticket_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sended_lead_itinerary_details', function (Blueprint $table) {
            $table->dropColumn('extra_hotels_and_rooms');
        });
    }
};
