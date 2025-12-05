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
        Schema::create('itinerary_template_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('itinerary_template_id')->comment('Foreign key referencing itinerary_templates table');
            $table->string('header');
            $table->string('field');
            $table->text('value');
            $table->text('description')->nullable();
            $table->timestamps();

            // Foreign Key Constraint
            $table->foreign('itinerary_template_id')
                  ->references('id')
                  ->on('itinerary_templates')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itinerary_template_details');
    }
};
