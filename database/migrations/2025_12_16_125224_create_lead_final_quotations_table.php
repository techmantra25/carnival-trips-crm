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
        Schema::create('lead_final_quotations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('lead_id');
            $table->unsignedBigInteger('sended_lead_itinerary_id');

            $table->unsignedBigInteger('shared_by')->nullable();
            $table->string('channel')->nullable(); // whatsapp, email, etc

            $table->timestamps();

            /* ================= Foreign Keys ================= */
            $table->foreign('lead_id')
                  ->references('id')->on('leads')
                  ->onDelete('cascade');

            $table->foreign('sended_lead_itinerary_id')
                  ->references('id')->on('sended_lead_itineraries')
                  ->onDelete('cascade');

            $table->foreign('shared_by')
                  ->references('id')->on('admins')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_final_quotations');
    }
};
