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
        Schema::create('hotel_price_chart_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('hotel_id'); // FK to hotels table
            $table->unsignedBigInteger('room_id');  // FK to rooms table
            $table->string('title');
            $table->decimal('rack_rate', 10, 2)->default(0.00);
            $table->decimal('gst', 10, 2)->default(0.00);
            $table->integer('type')->default(2)->comment('1:Actual Price, 2:Selling Price');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_price_chart_types');
    }
};
