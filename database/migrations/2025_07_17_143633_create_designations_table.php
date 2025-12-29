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
        Schema::create('designations', function (Blueprint $table) {
            $table->bigIncrements('id'); // bigint(20) UNSIGNED NOT NULL
            $table->string('name', 255); // varchar(255) NOT NULL
            $table->tinyInteger('status')->default(1); // tinyint(1) NOT NULL DEFAULT 1
            $table->timestamp('created_at')->nullable(); // timestamp NULL DEFAULT NULL
            $table->timestamp('updated_at')->nullable(); // timestamp NULL DEFAULT NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('designations');
    }
};
