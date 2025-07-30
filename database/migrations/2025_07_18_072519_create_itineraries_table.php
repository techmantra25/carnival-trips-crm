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
        Schema::create('itineraries', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['preset', 'post_inquiry', 'query']);
            $table->unsignedBigInteger('lead_id')->nullable()->comment("leads table id");
            $table->unsignedBigInteger('destination_id')->comment("states table id");
            $table->unsignedBigInteger('hotel_category')->comment("categories table id");
            $table->string('itinerary_syntax', 250)->nullable();
            $table->integer('total_days');
            $table->integer('total_nights');
            $table->text('night_journey')->nullable();
            $table->text('divisions_journey')->nullable()->comment("Division id's, from cities table");
            $table->string('stay_by_journey', 255)->nullable()->comment("Division id's, from cities table");
            $table->text('itinerary_journey')->nullable();
            $table->string('valid_from', 255)->nullable()->comment('Format: "MM-DD"');
            $table->string('valid_to', 255)->nullable()->comment('Format: "MM-DD"');
            $table->timestamps();

            // Foreign key constraints (optional)
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('set null');
            $table->foreign('destination_id')->references('id')->on('states')->onDelete('cascade');
            $table->foreign('hotel_category')->references('id')->on('categories')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itineraries');
    }
};
