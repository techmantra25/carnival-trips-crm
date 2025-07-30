<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Drop foreign key for employee_destination
        Schema::table('employee_destination', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });

        // Step 2: Modify tables
        $tables = [
            'admins',
            'ammenities',
            'cabs',
            'categories',
            'cities',
            'country_codes',
            'date_wise_hotel_prices',
            'designations',
            'destination_season_periods',
            'destination_wise_routes',
            'destination_wise_route_waypoints',
            'division_wise_activities',
            'division_wise_activity_images',
            'division_wise_cabs',
            'division_wise_sightseeings',
            'division_wise_sightseeing_images',
            // 'employee_destination', // Skip this, handled separately
            'hotels',
            'hotel_images',
            'hotel_policies',
            'hotel_price_charts',
            'hotel_price_chart_types',
            'hotel_seasion_times',
            'inventories',
            'itineraries',
            'itinerary_banners',
            'itinerary_details',
            'itinerary_templates',
            'itinerary_template_details',
            'leads',
            'leads_status',
            'non_services_hotel',
            'rooms',
            'room_categories',
            'route_service_summaries',
            'seasion_plans',
            'seasion_types',
            'service_wise_activities',
            'service_wise_cabs',
            'service_wise_sightseeings',
            'states',
            'users',
        ];

        foreach ($tables as $table) {
            try {
                DB::statement("ALTER TABLE `$table` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;");
            } catch (\Exception $e) {
                logger("Skipping table $table due to error: " . $e->getMessage());
            }
        }

        // Step 3: Modify admins.id again if needed (already done above)

        // Step 4: Recreate the foreign key
        Schema::table('employee_destination', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('admins')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Optional: reverse the above changes (not required in many dev cases)
    }
};

