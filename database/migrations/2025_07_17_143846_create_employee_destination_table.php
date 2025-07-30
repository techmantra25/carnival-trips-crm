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
        Schema::create('employee_destination', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary(); // Manual primary key
            $table->unsignedBigInteger('employee_id');   // Refers to `admins.id`
            $table->unsignedBigInteger('state_id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            // Optional: Add foreign key constraints (uncomment if needed)
            $table->foreign('employee_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_destination');
    }
};
