<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CrmToWebsiteController;

Route::prefix('crm')->group(function () {
    Route::get('/active/country', [CrmToWebsiteController::class, 'countries']);
    Route::post('/lead/store', [CrmToWebsiteController::class, 'lead_store']);
    Route::post('/active/divisions', [CrmToWebsiteController::class, 'active_divisions']);
    Route::post('/active/division/banner', [CrmToWebsiteController::class, 'division_banner']);
    Route::get('/itinerary_divisions/{itinerary_id}', [CrmToWebsiteController::class, 'itinerary_divisions']);
    Route::get('/itinerary_inclusion_exclusion/{itinerary_id}', [CrmToWebsiteController::class, 'itinerary_inclusion_exclusion']);
    Route::get('/active/country/destinations/{country_id}', [CrmToWebsiteController::class, 'country_wise_active_destinations']);
    Route::get('/active/category/{category_id}', [CrmToWebsiteController::class, 'active_category']);
    Route::get('/active/destinations/{destination_id}/itinerary', [CrmToWebsiteController::class, 'active_destination_wise_itinerary']);
    Route::get('/active/destination-wise/activities/{destination_id}', [CrmToWebsiteController::class, 'destination_wise_active_activities']);
    Route::get('/active/division-wise/cabs/{division_id}', [CrmToWebsiteController::class, 'division_wise_active_cabs']);
    Route::get('/division-wise-non-service-hotels/{division_id}', [CrmToWebsiteController::class, 'division_wise_non_service_hotels']);
});
