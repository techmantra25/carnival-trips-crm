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
            // Flag to indicate if the itinerary has been confirmed by the customer
            $table->boolean('is_confirmed')
                ->default(false)
                ->comment('Whether the itinerary is confirmed by the customer')
                ->after('sent_at');

            // Employee who helped confirm the itinerary
            $table->unsignedBigInteger('confirmed_by')
                ->nullable()
                ->comment('Employee ID who confirmed the itinerary')
                ->after('is_confirmed');

            // Timestamp of when the itinerary was confirmed
            $table->timestamp('confirmed_at')
                ->nullable()
                ->comment('Datetime when the itinerary was confirmed')
                ->after('confirmed_by');

            // Foreign key constraint to employees table
            $table->foreign('confirmed_by')
                ->references('id')
                ->on('admins')
                ->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('sended_lead_itineraries', function (Blueprint $table) {
            // Drop foreign key constraint first before dropping the column
            $table->dropForeign(['confirmed_by']);

            // Drop the columns in reverse order of creation
            $table->dropColumn('confirmed_at');
            $table->dropColumn('confirmed_by');
            $table->dropColumn('is_confirmed');
        });
    }
};
