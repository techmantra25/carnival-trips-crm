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
        Schema::create('destination_wise_routes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('route_name')->comment('e.g., Guwahati to Shillong to Cherrapunjee');
            $table->unsignedBigInteger('destination_id')->comment('e.g., Meghalaya');
            $table->string('total_distance_km', 250)->nullable()->comment('Total route distance in kilometers');
            $table->string('total_travel_time', 50)->nullable()->comment('Total estimated travel time');
            $table->timestamps();

            // Foreign key (uncomment and update if the `destination_id` references a `states` or `destinations` table)
            $table->foreign('destination_id')->references('id')->on('states')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destination_wise_routes');
    }
};
