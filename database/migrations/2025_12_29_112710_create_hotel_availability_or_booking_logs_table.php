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
        Schema::create('hotel_availability_or_booking_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('itinerary_id');
            $table->string('code')->comment('Unique code made by itineraryid_divisionid_hotelid_roomid');
            $table->enum('channel', ['Email','WhatsApp'])->nullable();
            $table->enum('type', ['availability', 'Confirm_booking'])->nullable();
            $table->unsignedBigInteger('sent_by');
            $table->timestamps();
            // Foreign keys
            $table->foreign('itinerary_id')
                  ->references('id')
                  ->on('sended_lead_itineraries')
                  ->onDelete('cascade');

            $table->foreign('sent_by')
                  ->references('id')
                  ->on('admins')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_availability_or_booking_logs');
    }
};
