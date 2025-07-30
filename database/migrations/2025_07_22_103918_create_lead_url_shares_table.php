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
        Schema::create('lead_url_shares', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id');
            $table->unsignedBigInteger('shared_by')->nullable()->comment('null = cron job, else CRM admin');
            $table->string('channel', 50)->default('whatsapp')->comment('e.g. whatsapp, email, sms');
            $table->boolean('is_cron')->default(false);
            $table->text('remarks')->nullable();
            $table->timestamp('shared_at')->useCurrent();
            $table->timestamps();

            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
            $table->foreign('shared_by')->references('id')->on('admins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_url_shares');
    }
};
