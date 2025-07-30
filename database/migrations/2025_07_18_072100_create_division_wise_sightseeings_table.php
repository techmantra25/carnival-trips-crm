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
        Schema::create('division_wise_sightseeings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('division_id');
            $table->string('name');
            $table->enum('type', ['PAID', 'UNPAID']);
            $table->longText('description')->nullable();
            $table->decimal('ticket_price', 8, 2)->default(0.00);
            $table->decimal('ticket_price_nri', 8, 2)->default(0.00);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('division_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('division_wise_sightseeings');
    }
};
