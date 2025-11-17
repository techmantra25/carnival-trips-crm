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
         Schema::create('division_wise_activity_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('destination_id')->nullable()->comment('states table id');
            $table->unsignedBigInteger('division_wise_activity_id')->comment('division_wise_activities table id');
            $table->string('file_path');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('destination_id')->references('id')->on('states')->onDelete('set null');
            $table->foreign('division_wise_activity_id')->references('id')->on('division_wise_activities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('division_wise_activity_images');
    }
};
