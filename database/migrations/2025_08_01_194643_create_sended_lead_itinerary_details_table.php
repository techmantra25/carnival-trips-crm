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
        Schema::create('sended_lead_itinerary_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('sended_lead_itinerary_id')->comment('Foreign key referencing sended_lead_itineraries table');
            $table->unsignedBigInteger('route_service_summary_id')->nullable()->comment('Foreign key referencing route_service_summaries table');
            $table->unsignedBigInteger('hotel_id')->nullable()->comment('Foreign key referencing hotels table');
            $table->unsignedBigInteger('room_id')->nullable()->comment('Foreign key referencing rooms table');
            $table->unsignedBigInteger('cab_id')->nullable()->comment('Foreign key referencing cabs table');

            $table->string('header')->nullable();        // e.g. "Hotel", "Cab", "Ticket"
            $table->string('field')->nullable();         // e.g. "Deluxe Room", "Sedan"
            $table->string('value')->nullable();         // e.g. "2 Nights", "4 Pax"
            $table->string('value_quantity')->nullable(); // e.g. "2", "1"
            $table->decimal('price', 10, 2)->nullable();   // Unit Price
            $table->decimal('rate', 10, 2)->nullable();    // Total Rate
            $table->decimal('ticket_price', 10, 2)->nullable(); // Ticket specific

            $table->timestamps();

            $table->foreign('sended_lead_itinerary_id')->references('id')->on('sended_lead_itineraries')->onDelete('cascade');
            $table->foreign('route_service_summary_id')->references('id')->on('route_service_summaries')->onDelete('set null');
            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('set null');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('set null');
            $table->foreign('cab_id')->references('id')->on('cabs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sended_lead_itinerary_details');
    }
};
