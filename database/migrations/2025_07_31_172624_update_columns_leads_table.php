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
       Schema::table('leads', function (Blueprint $table) {
            // Example: Make 'lead_type', 'source', and 'remarks' nullable
            $table->string('lead_type')->nullable()->change();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('lead_type')->nullable(false)->change();
        });
    }
};
