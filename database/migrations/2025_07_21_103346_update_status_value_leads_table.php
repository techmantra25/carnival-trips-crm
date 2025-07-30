<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Drop the existing `status` column
        Schema::table('leads', function ($table) {
            $table->dropColumn('status');
        });

        // Step 2: Add the new `status` column with updated ENUM values
        Schema::table('leads', function () {
            DB::statement("
                ALTER TABLE leads 
                ADD COLUMN status 
                ENUM(
                    'Verified Lead', 
                    'Link Generated', 
                    'Hot Leads', 
                    'Active Lead', 
                    'CNP', 
                    'High Intend Lead', 
                    'Pipeline', 
                    'Negotiation', 
                    'Confirmed', 
                    'Dead Lead', 
                    'Cancelled', 
                    'Closed', 
                    'Hold'
                ) 
                NOT NULL DEFAULT 'Verified Lead'
                 AFTER package_type
            ");
        });
    }

    public function down(): void
    {
        // Optional: restore the old version or drop again if rolling back
        Schema::table('leads', function ($table) {
            $table->dropColumn('status');
        });

        Schema::table('leads', function () {
            DB::statement("
                ALTER TABLE leads 
                ADD COLUMN status 
                ENUM(
                    'Unattended', 
                    'Follow-up', 
                    'Potential Lead', 
                    'Confirmed Lead', 
                    'Package Executed', 
                    'Package Confirmed', 
                    'Cancelled', 
                    'Hold', 
                    'Close'
                ) 
                NOT NULL DEFAULT 'Unattended'
                AFTER package_type
            ");
        });
    }
};
