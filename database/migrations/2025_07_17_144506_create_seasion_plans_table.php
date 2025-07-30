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
        Schema::create('seasion_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', ['main', 'addon'])->default('addon');
            $table->string('title');
            $table->text('plan_item');
            $table->integer('position')->default(1);
            $table->integer('order')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1: Active, 0: Inactive');
            $table->string('positions')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seasion_plans');
    }
};
