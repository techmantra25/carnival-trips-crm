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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id(); // bigint(20) UNSIGNED NOT NULL
            $table->unsignedBigInteger('hotel_id'); // FK to hotels table
            $table->unsignedBigInteger('room_id')->nullable(); // FK to rooms table
            $table->date('date'); // inventory date
            $table->integer('total_sold')->default(0);
            $table->integer('total_unsold')->default(0);
            $table->integer('block_request_type')->default(1)->comment('1:Hard Block, 2: Soft Block');
            $table->timestamps();

            // Foreign keys (optional if hotels/rooms tables exist)
            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
