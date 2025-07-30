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
        Schema::create('hotel_price_charts', function (Blueprint $table) {
            $table->id(); // bigint(20) UNSIGNED NOT NULL
            
            $table->string('type')->default('2')->comment('1:Actual Price, 2:Selling Price');
            $table->unsignedBigInteger('price_chart_type_id');
            $table->unsignedBigInteger('hotel_id');
            $table->unsignedBigInteger('room_id');
            
            $table->string('plan_title');
            $table->enum('plan_type', ['main', 'addon'])->default('main');
            $table->string('plan_item');
            $table->decimal('item_price', 10, 2)->default(0.00);

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('price_chart_type_id')->references('id')->on('hotel_price_chart_types')->onDelete('cascade');
            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_price_charts');
    }
};
