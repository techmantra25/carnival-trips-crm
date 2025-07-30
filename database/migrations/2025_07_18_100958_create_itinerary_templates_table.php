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
        Schema::create('itinerary_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('destination_id')->comment('Foreign key referencing states table');
            $table->timestamps();

            // Foreign Key Constraint
            $table->foreign('destination_id')->references('id')->on('states')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itinerary_templates');
    }
};
