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
        Schema::table('sended_lead_itineraries', function (Blueprint $table) {
            $table->unsignedBigInteger('destination_id')->nullable()->after('itinerary_code');
            $table->unsignedBigInteger('hotel_category')->nullable()->after('destination_id');
            $table->text('itinerary_syntax')->nullable()->after('hotel_category');
            $table->integer('total_days')->nullable()->after('itinerary_syntax');
            $table->integer('total_nights')->nullable()->after('total_days');
            $table->string('stay_by_journey')->nullable()->after('total_nights');
            $table->string('itinerary_journey')->nullable()->after('stay_by_journey');

            // Foreign key constraints (optional)
            $table->foreign('destination_id')->references('id')->on('states')->onDelete('set null');
            $table->foreign('hotel_category')->references('id')->on('categories')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('sended_lead_itineraries', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['destination_id']);
            $table->dropForeign(['hotel_category']);

            // Drop columns
            $table->dropColumn([
                'destination_id',
                'hotel_category',
                'itinerary_syntax',
                'total_days',
                'total_nights',
                'stay_by_journey',
                'itinerary_journey'
            ]);
        });
    }
};
