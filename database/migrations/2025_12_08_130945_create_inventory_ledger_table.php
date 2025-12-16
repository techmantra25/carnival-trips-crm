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
        Schema::create('inventory_ledger', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Link to inventories table
            $table->unsignedBigInteger('inventory_id')->comment('Reference to inventories table');

            // Hotel and room reference
            $table->unsignedBigInteger('hotel_id');
            $table->unsignedBigInteger('room_id');

            // Lead reference (for booking_sold)
            $table->unsignedBigInteger('lead_id')
                ->nullable()
                ->comment('Lead/Booking reference when entry_type = booking_sold');

            // For which date this ledger entry applies
            $table->date('inventory_date')->comment('Date of the affected inventory');

            // Ledger transaction type
            $table->enum('entry_type', [
                'opening',
                'incoming',
                'booking_sold',
                'manual_add',
                'manual_reduce',
                'auto_release',
                'system_adjust'
            ])->comment('Ledger transaction type');

            // Positive = add rooms, Negative = reduce rooms
            $table->integer('quantity')->comment('Positive=add rooms, Negative=reduce rooms');

            // Notes / extra info
            $table->text('description')->nullable();


            // Which user performed the action (null = cron/system)
            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();

            // Useful indexes
            $table->index(['inventory_id']);
            $table->index(['hotel_id', 'room_id', 'inventory_date']);

            /*
            |--------------------------------------------------------------------------
            | FOREIGN KEYS
            |--------------------------------------------------------------------------
            */

            $table->foreign('inventory_id')
                ->references('id')->on('inventories')
                ->onDelete('cascade');

            $table->foreign('hotel_id')
                ->references('id')->on('hotels')
                ->onDelete('cascade');

            $table->foreign('room_id')
                ->references('id')->on('rooms')
                ->onDelete('cascade');

            $table->foreign('lead_id')
                ->references('id')->on('leads')
                ->onDelete('set null');  // lead may get deleted but keep ledger history
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_ledger');
    }
};
