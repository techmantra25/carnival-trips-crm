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
         Schema::create('service_wise_cabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_summary_id')->comment('Foreign key referencing route_service_summaries');
            $table->unsignedBigInteger('division_wise_cab_id')->comment('Foreign key referencing division_wise_cabs');
            $table->decimal('cab_price', 8, 0)->nullable();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('service_summary_id')->references('id')->on('route_service_summaries')->onDelete('cascade');
            $table->foreign('division_wise_cab_id')->references('id')->on('division_wise_cabs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_wise_cabs');
    }
};
