<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppsController;
use App\Http\Controllers\DashboardsController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\RouteManagementController;

// New Code
use App\Http\Controllers\CronController;
use App\Livewire\Website\{FrontGetPresetItinerary,TripPreferenceForm,FrontGetCustomizedItinerary};
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AdminAuthController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\{LeadManagementController,CommonController,HotelManagementController,EmployeeManagement};


    Route::get('/', function () {
        if (auth('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login');
    })->name('home');
    

    // Route::get('admin/login', [RegisteredUserController::class, 'admin_login']);

    Route::prefix('/admin')->name('admin.')->group(function () {
        Route::get('/', function () {
            if (auth('admin')->check()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('admin.login');
        })->name('home');
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        // Handle admin login
        Route::post('login', [AdminAuthController::class, 'login']);
    });

    // Route::get('dashboard', [DashboardsController::class, 'index'])->name('admin.dashboard');
    Route::prefix('admin')->middleware(['auth:admin', 'admin.maintenance'])->group(function () {
        // Admin dashboard (only accessible if the user is authenticated as an admin)
        Route::get('dashboard', [DashboardsController::class, 'index'])->name('admin.dashboard');

        // Admin logout route
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

            // Lead Management
        Route::prefix('leads')->group(function(){
            Route::get('/', [LeadManagementController::class, 'index'])->name('admin.leads.index');
            Route::get('/confirmed', [LeadManagementController::class, 'confirmed'])->name('admin.leads.confirmed.index');
            Route::get('/log-history/{lead_id}', [LeadManagementController::class, 'lead_log_history'])->name('admin.leads.log.history');
            Route::get('/shared-history/{lead_id}', [LeadManagementController::class, 'lead_shared_history'])->name('admin.leads.shared.itinerary.history');
            Route::get('/trip-preference-form/{lead_id}', [LeadManagementController::class, 'trip_preference_data'])->name('admin.leads.trip.preference.data');
            Route::get('/cnp', [LeadManagementController::class, 'index'])->name('admin.leads.cnp.list');
            Route::get('/create', [LeadManagementController::class, 'create'])->name('admin.leads.create');
            Route::post('/store', [LeadManagementController::class, 'store'])->name('admin.leads.store');
            Route::post('/update/status/{id}', [LeadManagementController::class, 'update_status'])->name('admin.leads.update_status');
            // Additional CRUD routes
        });
        // Whatsapp Campaign
        Route::prefix('whatsapp-campaign')->group(function(){
            Route::get('/', [LeadManagementController::class, 'whatsapp_campaign_index'])->name('admin.whatsapp.campaign.index');
            // Route::get('/create', [WhatsappCampaignController::class, 'create'])->name('admin.whatsapp.campaign.create');
            // Route::post('/store', [WhatsappCampaignController::class, 'store'])->name('admin.whatsapp.campaign.store');
            // Route::get('/edit/{id}', [WhatsappCampaignController::class, 'edit'])->name('admin.whatsapp.campaign.edit');
            // Route::post('/update/{id}', [WhatsappCampaignController::class, 'update'])->name('admin.whatsapp.campaign.update');
            // Route::delete('/destroy/{id}', [WhatsappCampaignController::class, 'destroy'])->name('admin.whatsapp.campaign.destroy');
        });
        Route::prefix('employee')->group(function (){
            Route::get('/', [EmployeeManagement::class, 'index'])->name('admin.employee.index');
            Route::get('/designations', [EmployeeManagement::class, 'designationIndex'])->name('admin.designation.index');
            Route::get('/hierarchy', [EmployeeManagement::class, 'employeeHierarchy'])->name('admin.employee-hierarchy');
        });
        Route::get('/profile', [EmployeeManagement::class, 'employee_profile'])->name('admin.employee.profile');
    
        // Hotel master
        Route::prefix('hotel')->group(function(){
            Route::get('/', [HotelManagementController::class,'index'])->name('admin.hotel.index');
            Route::get('/create', [HotelManagementController::class,'create'])->name('admin.hotel.create');
            Route::post('/store', [HotelManagementController::class,'store'])->name('admin.hotel.store');
            Route::get('/price-chart', [HotelManagementController::class,'price_chart'])->name('admin.hotel.price_chart');
            Route::get('/division-wise-hotel-priority', [HotelManagementController::class,'division_wise_hotel_priority'])->name('admin.hotel.division_wise_hotel_priority');
            Route::get('/update/{id}', [HotelManagementController::class,'updateHotel'])->name('admin.hotel.edit');
            // Route::post('/update/{id}', [HotelManagementController::class,'update'])->name('admin.hotel.update');
            Route::post('/room/price/store', [HotelManagementController::class,'room_price_store'])->name('admin.hotel.room.price.store');
            Route::get('/destroy/{id}', [HotelManagementController::class,'destroy'])->name('admin.hotel.destroy');
            // Route::post('/image/delete/{imageId}', [HotelManagementController::class, 'deleteImage'])->name('admin.hotel.image.delete');
            // Route::delete('/image/{imageId}', [HotelController::class, 'deleteImage'])->name('hotel.image.delete');
            Route::delete('/image/delete/{imageId}', [HotelManagementController::class, 'deleteImage'])->name('admin.hotel.image.delete');


            Route::get('/{id}/images', [HotelManagementController::class, 'showHotelImages'])->name('admin.hotels.images');
            Route::post('update-room-item-price', [HotelManagementController::class, 'UpdateRoomItemPrice']);
            
            // Hotel Seasion Plan  Master
            Route::prefix('seasion-plan')->group(function(){
                Route::get('/', [HotelManagementController::class,'hotel_seasion_plan'])->name('admin.hotel_seasion_plan');
                Route::post('/store', [HotelManagementController::class,'hotel_seasion_plan_store'])->name('admin.hotel_seasion_plan_store');
                Route::post('/update', [HotelManagementController::class,'hotel_seasion_plan_update'])->name('admin.hotel_seasion_plan_update');
                Route::get('/destroy/{id}', [HotelManagementController::class,'hotel_seasion_plan_destroy'])->name('admin.hotel_seasion_plan_destroy');
            // web.php (Route definition)
            Route::post('/update-order', [HotelManagementController::class, 'updateOrder'])->name('admin.hotel_seasion_plan_order');                              

            
            });

            // Category Master
            Route::prefix('category')->group(function(){
                Route::get('/', [CommonController::class,'category_index'])->name('admin.category.index');
                Route::post('/store', [CommonController::class,'category_store'])->name('admin.category.store');
                // Route::get('/edit', [CommonController::class,'category_edit'])->name('admin.category.edit');
                Route::post('/update', [CommonController::class,'category_update'])->name('admin.category.update');
                Route::get('/destroy/{id}', [CommonController::class,'category_destroy'])->name('admin.category.destroy');
            });
            // Ammenity Master
            Route::prefix('ammenity')->group(function(){
                Route::get('/', [CommonController::class,'ammenity_index'])->name('admin.ammenity.index');
                Route::post('/store', [CommonController::class,'ammenity_store'])->name('admin.ammenity.store');
                Route::get('/edit', [CommonController::class,'ammenity_edit'])->name('admin.ammenity.edit');
                Route::post('/update', [CommonController::class,'ammenity_update'])->name('admin.ammenity.update');
                Route::get('/destroy/{id}', [CommonController::class,'ammenity_destroy'])->name('admin.ammenity.destroy');
            });


                // Room Category Master
                Route::prefix('room-category')->group(function(){
                Route::get('/', [CommonController::class,'room_category_index'])->name('admin.room.category.index');
                Route::post('/store', [CommonController::class,'room_category_store'])->name('admin.room.category.store');
                Route::get('/edit', [CommonController::class,'room_category_edit'])->name('admin.room.category.edit');
                Route::post('/update', [CommonController::class,'room_category_update'])->name('admin.room.category.update');
                Route::get('/destroy/{id}', [CommonController::class,'room_category_destroy'])->name('admin.room.category.destroy');
            });

            // Non Services Hotel

            Route::prefix('non-service-hotels')->group(function(){
                Route::get('/',[HotelManagementController::class, 'nonServicesHotel'])->name('admin.non-service-hotels.index');
            });
        });

        
        Route::prefix('inventory')->group(function(){
            Route::get('/', [InventoryController::class, 'index'])->name('admin.inventory.index');
            Route::get('/hotel-booking-request', [InventoryController::class, 'hotel_booking_request'])->name('admin.inventory.hotel-booking-request');
            Route::get('/page1', [InventoryController::class, 'page1'])->name('admin.inventory.page1');
            Route::get('/page2', [InventoryController::class, 'page2'])->name('admin.inventory.page2');
            Route::get('get-divisions/{destination}', [InventoryController::class, 'getDivisions'])->name('admin.inventory.get.divisions');
        });

        Route::prefix('master')->group(function(){
            Route::prefix('country')->group(function(){
                Route::get('/', [CommonController::class, 'country_index'])->name('admin.country.index');
            });
            Route::prefix('cab')->group(function(){
                Route::get('/', [CommonController::class,'cab_index'])->name('admin.cab.index');
                Route::post('/store', [CommonController::class,'cab_store'])->name('admin.cab.store');
                Route::get('/edit', [CommonController::class,'cab_edit'])->name('admin.cab.edit');
                Route::post('/update', [CommonController::class,'cab_update'])->name('admin.cab.update');
                Route::get('/destroy/{id}', [CommonController::class,'cab_destroy'])->name('admin.cab.destroy');
            });
             // State Master
             Route::prefix('destination')->group(function(){
                Route::get('/', [CommonController::class,'state_index'])->name('admin.state.index');
                Route::post('/store', [CommonController::class,'state_store'])->name('admin.state.store');
                Route::get('/edit', [CommonController::class,'state_edit'])->name('admin.state.edit');
                Route::post('/update', [CommonController::class,'state_update'])->name('admin.state.update');
                Route::get('/destroy/{id}', [CommonController::class,'state_destroy'])->name('admin.state.destroy');
            });
             Route::prefix('destination-wise-season')->group(function(){
                Route::get('/', [CommonController::class,'destination_wise_season'])->name('admin.destination_wise_season');
            });

            // Division Master
            Route::prefix('division')->group(function(){
                Route::get('/', [CommonController::class,'division_index'])->name('admin.division.index');
                Route::post('/store', [CommonController::class,'division_store'])->name('admin.division.store');
                Route::get('/edit', [CommonController::class,'division_edit'])->name('admin.division.edit');
                Route::post('/update', [CommonController::class,'division_update'])->name('admin.division.update');
                Route::get('/destroy/{id}', [CommonController::class,'division_destroy'])->name('admin.division.destroy');
            });

            // Trip Preference Form
            Route::get('/trip-preference-form',[CommonController::class,'trip_preference_form'])->name('admin.master.trip_preference_form');
        });
        Route::prefix('route')->group(function(){
            Route::get('division-wise-cabs', [RouteManagementController::class, 'DivisionWiseCabList'])->name('admin.route.division_wise_cab_list');
            Route::get('division-wise-activities', [RouteManagementController::class, 'DivisionWiseActivityList'])->name('admin.route.division_wise_activity_list');
            Route::post('division-wise-activities/update/content', [RouteManagementController::class, 'DivisionWiseActivityUpdateContent'])->name('admin.route.division_wise_activity_update_content');
            Route::post('division-wise-sightseeing/update/content', [RouteManagementController::class, 'DivisionWiseSightseeingUpdateContent'])->name('admin.route.division_wise_sightseeing_update_content');
            Route::get('division-wise-sightseeings', [RouteManagementController::class, 'DivisionWiseSightseeingList'])->name('admin.route.division_wise_sightseeing_list');
            Route::get('destination-wise-route-list', [RouteManagementController::class, 'DestinationWiseRouteList'])->name('admin.route.destination_wise_route_list');
            Route::get('all-services', [RouteManagementController::class, 'AllServices'])->name('admin.route.all_services');
            Route::post('update-cab-service-price', [RouteManagementController::class, 'UpdateCabServicePrice']);
        });

        // Itinerary Management
        Route::prefix('itinerary')->group(function(){
            Route::prefix('division')->group(function(){
                Route::get('banners', [ItineraryController::class, 'banners'])->name('admin.itinerary.division.banners');
            });
            Route::prefix('preset')->group(function(){
                Route::get('/list/{lead_id?}', [ItineraryController::class, 'DestinationWisePresetItineraryList'])->name('admin.itinerary.preset.list');
            });
            Route::prefix('post-lead')->group(function(){
                Route::get('/list', [ItineraryController::class, 'DestinationWisePostLeadItineraryList'])->name('admin.itinerary.postlead.list');
            });
            Route::get('build/{encryptedId}', [ItineraryController::class, 'DestinationWisePresetItineraryBuilder'])->name('admin.itinerary.preset.build');
            Route::get('customization', [ItineraryController::class, 'LeadItineraryCustomization'])->name('admin.itinerary.lead.customization');
            Route::get('/template/list', [ItineraryController::class, 'DestinationWiseItineraryTemplate'])->name('admin.itinerary.template.list');
            Route::get('template/build/{encryptedId}', [ItineraryController::class, 'DestinationWiseItineraryTemplateBuilder'])->name('admin.itinerary.template.build');
        });

        // Cost Calculator
        Route::prefix('cost-calculator')->group(function(){
             Route::get('query/build/{encryptedId}', [ItineraryController::class, 'QueryItineraryCustomization'])->name('admin.itinerary.query.build');
            Route::get('query-list', [ItineraryController::class, 'CostCalculationQueries'])->name('admin.cost_calculator.query_list');
            Route::get('query-edit/{encryptedLeadId}', [ItineraryController::class, 'CostCalculationQueryEdit'])->name('admin.cost_calculator.query_edit');
            Route::get('query-pdf', [ItineraryController::class, 'CostCalculationPDF'])->name('admin.cost_calculator.make_pdf');
            Route::get('download-table-pdf', [ItineraryController::class, 'downloadTablePdf'])->name('admin.cost_calculator.download_pdf');
        });
    });

   

    Route::prefix('cron')->group(function(){
        Route::get('update-lead-status', [CronController::class, 'update_lead_status']);
    });

    Route::get('/trip-preference-form/{code}', TripPreferenceForm::class)->name('website.trip.preference.form');
    Route::get('customized/itinerary/{code}', FrontGetCustomizedItinerary::class)->name('website.lead.customized.itinerary');
    Route::get('{destination_slug}/{itinerary_slug}/{encryptedSharedLinkId}', FrontGetPresetItinerary::class)
    ->name('website.lead.destination.preset-itinerary');
    require __DIR__.'/auth.php';
