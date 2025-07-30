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
       Schema::create('itinerary_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->unsignedBigInteger('destination_id')->nullable()->comment('states table id');
            $table->unsignedBigInteger('division_id')->nullable()->comment('cities table id');
            $table->string('image');
            $table->tinyInteger('status')->default(1)->comment('1:Active, 0:Inactive');
            $table->timestamps();

            // Optional foreign keys
            $table->foreign('destination_id')->references('id')->on('states')->onDelete('set null');
            $table->foreign('division_id')->references('id')->on('cities')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itinerary_banners');
    }
};
