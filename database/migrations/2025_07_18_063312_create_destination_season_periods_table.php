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
       Schema::create('destination_season_periods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('destination_id')->nullable();
            $table->unsignedBigInteger('season_type_id')->nullable();
            $table->string('start_date')->nullable()->comment('Format: "MM-DD"');
            $table->string('end_date')->nullable()->comment('Format: "MM-DD"');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('destination_id')->references('id')->on('states')->onDelete('cascade');
            $table->foreign('season_type_id')->references('id')->on('seasion_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destination_season_periods');
    }
};
