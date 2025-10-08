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
        Schema::create('lead_whatsapp_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id');
            $table->unsignedBigInteger('template_id')->nullable();
            $table->unsignedBigInteger('sent_by'); // admin_id
            $table->timestamp('sent_at')->nullable();
            $table->enum('status', ['success', 'failed'])->default('success');
            $table->timestamps();

            // Foreign keys (optional, if you have leads & admin tables)
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
            $table->foreign('template_id')->references('id')->on('whatsapp_message_templates')->onDelete('set null');
            $table->foreign('sent_by')->references('id')->on('admins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_whatsapp_messages');
    }
};
