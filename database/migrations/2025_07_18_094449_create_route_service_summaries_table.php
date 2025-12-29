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
        Schema::create('route_service_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('service_type', ['Route Wise', 'Per Day']);
            $table->unsignedBigInteger('route_id')->nullable()->comment('Foreign key referencing destination_wise_routes');
            $table->unsignedBigInteger('destination_id')->comment('Foreign key referencing states');
            $table->unsignedBigInteger('division_id')->nullable()->comment('Foreign key referencing cities');
            $table->timestamps();

            // Optional foreign keys
            $table->foreign('route_id')->references('id')->on('destination_wise_routes')->onDelete('set null');
            $table->foreign('destination_id')->references('id')->on('states')->onDelete('cascade');
            $table->foreign('division_id')->references('id')->on('cities')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_service_summaries');
    }
};
