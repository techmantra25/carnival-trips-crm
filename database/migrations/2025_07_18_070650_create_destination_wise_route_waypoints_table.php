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
        Schema::create('destination_wise_route_waypoints', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('route_id')->comment('Foreign key referencing destination_wise_routes');
            $table->string('point_name')->nullable()->comment('Name of the waypoint');
            $table->unsignedBigInteger('division_id')->comment('Foreign key referencing cities');
            $table->integer('sequence')->comment('Order of the waypoint, e.g., 1 for first, 2 for second, etc.');
            $table->integer('positions', false, true)->default(1);
            $table->string('distance_from_previous_km', 250)->nullable()->comment('Distance from the previous waypoint');
            $table->string('travel_time_from_previous', 50)->nullable()->comment('Travel time from the previous waypoint');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('route_id')->references('id')->on('destination_wise_routes')->onDelete('cascade');
            $table->foreign('division_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destination_wise_route_waypoints');
    }
};
