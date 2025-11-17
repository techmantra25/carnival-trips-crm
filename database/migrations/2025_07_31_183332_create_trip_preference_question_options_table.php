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
        Schema::create('trip_preference_question_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('trip_question_id'); // FK column
            $table->string('option_text');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('trip_question_id', 'tpq_option_fk')
                ->references('id')
                ->on('trip_preference_questions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_preference_question_options');
    }
};
