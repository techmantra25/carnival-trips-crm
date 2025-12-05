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
        Schema::create('states', function (Blueprint $table) {
            $table->bigIncrements('id'); // matches bigint(20) UNSIGNED NOT NULL
            $table->unsignedBigInteger('country_code_id')->nullable(); // bigint(20) UNSIGNED DEFAULT NULL
            $table->string('name', 255);
            $table->tinyInteger('status')->default(1)->comment('1: Active, 0: Inactive');
            $table->dateTime('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
