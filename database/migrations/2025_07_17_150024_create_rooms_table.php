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
        Schema::create('rooms', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('hotel_id'); // FK to hotels

            $table->string('room_type');
            $table->string('room_category');
            $table->string('room_name');

            $table->unsignedInteger('no_of_rooms');
            $table->unsignedInteger('no_of_beds');
            $table->integer('extra_bed');
            $table->unsignedInteger('mattress')->default(0);
            $table->unsignedInteger('capacity');

            $table->text('ammenities')->nullable();
            $table->integer('positions')->default(1);
            $table->tinyInteger('is_priority')->default(0);

            $table->timestamps();

            // Foreign key constraint
            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
