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
        Schema::create('leads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('unique_id');
            $table->enum('generate_from', ['lead', 'query'])->default('lead');
            $table->enum('nationality_type', ['Indian', 'NRI'])->default('Indian');
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_mobile')->nullable();
            $table->string('country_code');
            $table->string('customer_whatsapp')->nullable();
            $table->unsignedBigInteger('travel_location');
            $table->unsignedBigInteger('hotel_category')->nullable();
            $table->string('travel_duration')->comment('calculate by nights');
            $table->integer('travel_in_days')->nullable();
            $table->integer('travel_in_nights')->nullable();
            $table->string('travel_duration_text')->nullable();
            $table->date('departure_date')->nullable();
            $table->date('arrival_date');
            $table->integer('number_of_adults');
            $table->integer('number_of_children')->default(0);
            $table->text('children_data')->nullable();
            $table->integer('number_of_travellor');
            $table->bigInteger('number_of_rooms')->nullable();
            $table->string('extra_mattress')->nullable();
            $table->string('meal_type')->nullable();
            $table->string('lead_type');
            $table->string('lead_source')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('team_lead_id')->nullable();
            $table->unsignedBigInteger('assigned_to_id')->nullable();
            $table->enum('package_type', ['Holiday Packages', 'Logistics', 'Hotels'])->default('Holiday Packages');
            $table->enum('status', [
                'Unattended',
                'Follow-up',
                'Potential Lead',
                'Confirmed Lead',
                'Package Executed',
                'Package Confirmed',
                'Cancelled',
                'Hold',
                'Close'
            ])->default('Unattended');
            $table->timestamps();

            // Foreign key constraints (uncomment & update with correct table names if needed)
            $table->foreign('travel_location')->references('id')->on('states')->onDelete('cascade');
            $table->foreign('hotel_category')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('team_lead_id')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('assigned_to_id')->references('id')->on('admins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
