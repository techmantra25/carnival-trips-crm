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
        Schema::create('itinerary_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('itinerary_id')->comment('Foreign key referencing itineraries table');
            $table->unsignedBigInteger('route_service_summary_id')->nullable()->comment('Foreign key referencing route_service_summaries table');
            $table->unsignedBigInteger('hotel_id')->nullable()->comment('Foreign key referencing hotels table');
            $table->unsignedBigInteger('room_id')->nullable()->comment('Foreign key referencing rooms table');
            $table->unsignedBigInteger('cab_id')->nullable()->comment('Foreign key referencing cabs table');

            $table->string('header');
            $table->string('field');
            $table->text('value');
            $table->integer('value_quantity')->default(1);
            $table->double('price', 10, 2)->nullable();
            $table->decimal('rate', 8, 2)->default(0.00);
            $table->decimal('ticket_price', 8, 2)->default(0.00);

            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('itinerary_id')->references('id')->on('itineraries')->onDelete('cascade');
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
        Schema::dropIfExists('itinerary_details');
    }
};
