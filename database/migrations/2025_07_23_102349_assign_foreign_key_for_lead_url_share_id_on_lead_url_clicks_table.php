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
       Schema::table('lead_url_clicks', function (Blueprint $table) {
            $table->foreign('lead_url_share_id')->references('id')->on('lead_url_shares')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('lead_url_clicks', function (Blueprint $table) {
            $table->dropForeign(['lead_url_share_id']);
        });
    }
};
