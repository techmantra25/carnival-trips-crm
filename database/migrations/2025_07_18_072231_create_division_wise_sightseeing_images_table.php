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
        Schema::create('division_wise_sightseeing_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('destination_id')->nullable()->comment('states table id');
            $table->unsignedBigInteger('sightseeing_id')->comment('division_wise_sightseeings table id');
            $table->string('file_path');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('destination_id')->references('id')->on('states')->onDelete('set null');
            $table->foreign('sightseeing_id')->references('id')->on('division_wise_sightseeings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('division_wise_sightseeing_images');
    }
};
