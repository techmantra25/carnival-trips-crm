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
        Schema::create('sended_lead_itineraries', function (Blueprint $table) {
            $table->id();

            // Foreign key to leads table
            $table->unsignedBigInteger('lead_id');
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');

            // Unique code for each itinerary per lead
            $table->string('itinerary_code')->unique();

            // Sent cost or price
            $table->decimal('total_cost', 10, 2)->nullable();

            // Optional notes or content
            $table->text('remarks')->nullable();

            $table->enum('send_via', ['whatsapp', 'email', 'sms', 'others'])->default('whatsapp');
            
            // Track who sent the itinerary
            $table->unsignedBigInteger('sent_by_admin_id')->nullable();
            $table->foreign('sent_by_admin_id')->references('id')->on('admins')->nullOnDelete();

            // Sent date/time
            $table->timestamp('sent_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sended_lead_itineraries');
    }
};
