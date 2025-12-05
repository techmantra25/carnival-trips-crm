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
          Schema::create('hotels', function (Blueprint $table) {
           $table->bigIncrements('id');

            $table->string('name');
            $table->string('image')->nullable();

            // Foreign keys (nullable for SET NULL on delete)
            $table->unsignedBigInteger('destination')->nullable();      // states(id)
            $table->unsignedBigInteger('division')->nullable();         // cities(id)
            $table->unsignedBigInteger('hotel_category')->nullable();   // categories(id)

            $table->unsignedBigInteger('number_of_rooms')->nullable();
            $table->string('phone_code');
            $table->string('mobile_number');
            $table->string('whatsapp_number');
            $table->string('email1')->nullable();
            $table->string('email2')->nullable();

            $table->tinyInteger('status')->default(1)->comment('0:Inactive, 1:Active');
            $table->integer('priority')->default(1);
            $table->text('address')->nullable();
            $table->integer('release_trigger')->default(0);
            $table->string('policy', 250)->nullable();

            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('destination')->references('id')->on('states')->onDelete('set null');
            $table->foreign('division')->references('id')->on('cities')->onDelete('set null');
            $table->foreign('hotel_category')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
