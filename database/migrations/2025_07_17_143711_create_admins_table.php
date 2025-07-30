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
        Schema::create('admins', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary(); // Manual primary key
            $table->string('name', 255);
            $table->unsignedBigInteger('designation_id')->nullable();
            $table->string('email', 255);
            $table->string('phone', 20)->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->unsignedBigInteger('team_lead')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->string('password', 255);
            $table->text('address')->nullable();
            $table->enum('role', ['super_admin', 'admin', 'team_lead', 'member'])->default('member');
            $table->string('remember_token', 100)->nullable();
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
        Schema::dropIfExists('admins');
    }
};
