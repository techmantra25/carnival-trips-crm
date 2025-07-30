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
        Schema::create('hotel_seasion_times', function (Blueprint $table) {
            $table->id();
            $table->string('seasion_type')->nullable(); // Note: raw string, for display or legacy compatibility
            $table->unsignedBigInteger('seasion_type_id');
            $table->unsignedBigInteger('hotel_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('seasion_type_id')->references('id')->on('seasion_types')->onDelete('cascade');
            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_seasion_times');
    }
};
