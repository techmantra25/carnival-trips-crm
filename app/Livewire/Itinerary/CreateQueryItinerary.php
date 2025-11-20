<?php

namespace App\Livewire\Itinerary;

use Livewire\Component;
use App\Models\State;
use App\Models\City;
use App\Models\Room;
use App\Models\Lead;
use App\Models\SeasionPlan;
use App\Models\HotelPriceChart;
use App\Models\Hotel;
use App\Models\Category;
use App\Models\EmailTemplate;
use App\Models\DivisionWiseSightseeingImage;
use App\Models\DivisionWiseActivityImage;
use App\Models\DivisionWiseActivity;
use App\Models\Itinerary;
use App\Models\RouteServiceSummary;
use App\Models\ItineraryDetail;
use App\Models\DestinationWiseRoute;
use App\Models\ItineraryTemplate;
use App\Models\ItineraryTemplateDetail;
use App\Models\HotelPriceChartType;
use App\Models\DivisionWiseSightseeing;
use App\Models\ServiceWiseSightseeing;
use App\Models\ServiceWiseActivity;
use App\Models\SendedLeadItinerary;
use App\Models\LeadUrlShare;
use App\Models\SendedLeadItineraryDetail;
use App\Models\LeadActivityLog;
use App\Helpers\CustomHelper;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Services\MailTemplateService;
use App\Services\WhatsAppService;

use App\Models\ItineraryBanner;

class CreateQueryItinerary extends Component
{
    public $isRelatedModalOpen = false;

    public $activeTab = [];
    public $activeInExTab = 'inclusion'; // or 'exclusion'

    use WithFileUploads;
    public $inclusions = [];
    public $exclusions = [];
    public $stay_by_journey = [];
    public $related_hotels = [];
    public $destinationId;
    public $itineraryType;
    public $itinerary_id;
    public $itineraryData;
    public $active_assign_route_modal;
    public $selectedDivision;
    public $categoryId;
    public $mainBanner = [];
    public $selectedInclusions = [];
    public $selectedExclusions = [];
    public $selectedTriphighlight = [];
    public $triphighlight = [];
    public $hotel_room_price = [];
    public $destinationName;
    public $categoryName;
    public $itinerary_syntax;
    public $itinerary_journey = [];
    public $night;
    public $uploadDayImages = [];
    public $day_by_divisions = [];

    public $selected_rooms = [];
    public $selected_room_plan = [];

    
    public $destinationImages = [];
    public $dayImages = [];
    public $dayHotels = [];
    public $dayExtraHotels = [];
    public $day_texts = []; 
    public $selected_day_wise_itinerary = [];
    
    public $selected_about_desc_banners = [];
    public $trip_highlights = []; // Array to store trip highlights

    public $day_wise_amount_data=[];
    public $errorRoom = [];
    public $errorHotel = [];
    public $errorRoute = [];
    public $errorNewRoute = [];
    public $errorActivity = [];
    public $errorSightSeeing = [];
    public $errorCab = [];
    public $errorPerDayCab = [];
    public $leadData;
    public $itemQuantity = [];
    public $selected_night_halt;
    public $total_amount = 0;
    public $selected_rooms_count = 0;
    public $allCategories = [];
    public $selected_division;

    public $active_new_route_modal,$new_route_name,$service_summary_id;
    public $active_new_sightseeing_modal = 0;
    public $active_route_new_sightseeing_modal = 0;
    public $new_sightseeings = [];
    public $new_activities = [];
    public $active_new_activity_modal = 0;
    public $active_route_new_activity_modal = 0;
    public $package_budget = 'max';
    public $budgetSortOrder = 'DESC';
    public $day_hotel_category = [];
    public $loadDayHotelsTime = 1;
    public $send_whatsapp = false;
    public $send_email = false;
    public $send_sms = false;

    protected $whatsapp;
    public function __construct(){
        $this->whatsapp = app(WhatsAppService::class);
    }

    public function mount($encryptedId){

        $itineraryExists = Itinerary::with('lead')->find($encryptedId);
        $this->itinerary_id =$encryptedId;
        $this->itineraryData =$itineraryExists;
        $this->stay_by_journey =$itineraryExists->stay_by_journey;
        $this->leadData = $itineraryExists->lead;
        $this->selected_night_halt = $itineraryExists->itinerary_journey;
    
        $this->itineraryType =$itineraryExists->type;
        $categoryExists = Category::where('id', $itineraryExists->hotel_category)->first();

        $this->destinationName = $itineraryExists->destination->name;
        $this->categoryName = $categoryExists->name;
        $this->destinationId = $itineraryExists->destination_id;
        $this->categoryId = $itineraryExists->hotel_category;
        $this->itinerary_syntax = $itineraryExists->itinerary_syntax;
        $city = City::where('state_id', $itineraryExists->destination_id)->first();

        if($city){
            $this->selectedDivision = $city->id;
        }

        // Get divisions
        $stay_by_journey = explode(',',$this->stay_by_journey);
        if(count($stay_by_journey)>0){
            $days_journey = [];
            foreach($stay_by_journey as $k=>$journey){
                $this->day_hotel_category[$k+1] = $this->categoryId;
                $this->allCategories[$k+1] = Category::where('status', 1)
                    ->whereHas('hotel', function ($query) use ($itineraryExists,$journey) {
                        $query->where('destination', $itineraryExists->destination_id);
                        $query->where('division', $journey);
                    })
                    ->orderBy('name', 'ASC')
                    ->get();
            }
        }
        $this->filterHotelData();
        
        $this->getInclusionExclusion();
        $this->getTriphighlight();
        $this->loadSelectedInclusions();
        $this->loadSelectedExclusions();
        
    }

    protected function filterHotelData(){
         $stay_by_journey = explode(',',$this->stay_by_journey);
        if(count($stay_by_journey)>0){
            $days_journey = [];
            foreach($stay_by_journey as $k=>$journey){
                // Set Itinerary Wise Data 
                $day = $k+1;
                $this->SetHotel($day, $journey);
               
                $destination_route = RouteServiceSummary::with('route')->where('destination_id', $this->destinationId)->where('service_type', 'Route Wise')->get()->toArray();
                $city = City::find($journey);
                $days_journey[$k+1]['division_id'] = $journey;
                $days_journey[$k+1]['division_name'] = $city?$city->name:"N/A";
                // $days_journey[$k+1]['division_hotels'] =$hotels;
                $days_journey[$k+1]['division_routes'] =$destination_route;
                $days_journey[$k+1]['day_hotel'] =$this->loadDayHotels($k+1);
                $days_journey[$k+1]['day_route'] =$this->loadDayRoutes($k+1);
                $days_journey[$k+1]['per_day_cab'] =$this->loadDayCabs($k+1);
                $this->loadDayImages($k+1);
            }
            $this->day_by_divisions = $days_journey;
        }
    }
    public function getCapacity(){
        return ceil($this->leadData->number_of_travellor / max($this->leadData->number_of_rooms, 1));
    }
    public function SetHotel($index, $division){
        DB::beginTransaction();
        try {
            $city = City::find($division);
            
            $travelDate = Carbon::parse($this->leadData->travel_date);
            $date = $travelDate->copy()->addDay($index-1);
            $season = CustomHelper::GetSeason($this->leadData->travel_location, $date->format('m-d'));

            // Fetch Season
            if (!$season) {
                $this->reset(['day_by_divisions','dayExtraHotels']);
                session()->flash('error', 'No season period has been defined for the selected destination. Please add the season details first.');
                return false;
            }

            // Get Hotel
            $meal_type = $this->leadData->meal_type;
            $capacity = $this->getCapacity();

            if($index<$this->leadData->travel_in_days){

                $selectedDayHotelsId = [];
                
                if(isset($this->day_by_divisions[$index]['day_hotel'])){
                   foreach ($this->day_by_divisions[$index]['day_hotel'] as $day_hotel_index => $day_hotel) {
                        $selectedDayHotelsId[$day_hotel_index] = isset($day_hotel['hotel_id']) && $day_hotel['hotel_id']
                            ? $day_hotel['hotel_id']
                            : $day_hotel['id'];
                    }
                }

                $selected_hotel = Hotel::with('priority_rooms', 'rooms')
                    ->join('hotel_price_charts', 'hotels.id', '=', 'hotel_price_charts.hotel_id')
                    ->when($this->loadDayHotelsTime == 2, function ($query) use ($selectedDayHotelsId) {
                        $query->whereIn('hotels.id', $selectedDayHotelsId);
                    })
                    ->where('hotels.division', $division)
                    ->where('hotels.hotel_category', $this->day_hotel_category[$index])
                    ->where('hotel_price_charts.plan_title', ucwords($season))
                    ->where('hotel_price_charts.plan_item', $meal_type)
                    ->where('hotel_price_charts.item_price', '>', 0)
                    ->whereHas('rooms', function ($query) use ($capacity) {
                        $query->where('capacity', '>=', $capacity);
                    })
                     ->orderBy('hotel_price_charts.item_price', $this->budgetSortOrder)
                    ->orderBy('hotels.priority', 'ASC')
                    ->select('hotels.*')
                    ->first();
                    // dd($this->budgetSortOrder);
                // Fallback: without hotel_category
                if (!$selected_hotel) {
                    $selected_hotel = Hotel::with('priority_rooms', 'rooms')
                        ->join('hotel_price_charts', 'hotels.id', '=', 'hotel_price_charts.hotel_id')
                        ->where('hotels.division', $division)
                        ->where('hotel_price_charts.plan_title', ucwords($season))
                        ->where('hotel_price_charts.plan_item', $meal_type)
                        ->where('hotel_price_charts.item_price', '>', 0)
                        ->whereHas('rooms', function ($query) use ($capacity) {
                            $query->where('capacity', '>=', $capacity);
                        })
                        ->orderBy('hotel_price_charts.item_price', $this->budgetSortOrder)
                        ->orderBy('hotels.priority', 'ASC')
                        ->select('hotels.*')
                        ->first();
                }
                if($selected_hotel){
                    $this->generateHotelData($index,$selected_hotel->id,$season);
                }else{
                    $existing_hotel_data = ItineraryDetail::where('itinerary_id', $this->itinerary_id)->where('header', 'day_' . $index)->whereNotNull('hotel_id')->get();
                    if (count($existing_hotel_data)>0) {
                        $existing_hotel_data->each(function ($detail) {
                            $detail->delete();
                        });
                    }
                }
            }
            // Fetch Per day Cab
            $total_member = $this->leadData->number_of_travellor;
            $RouteServiceSummary = RouteServiceSummary::with('cabs')->where('destination_id', $this->destinationId)->where('service_type', 'Per Day')->first();
            $existing_cabs = [];
            if($RouteServiceSummary){
                // // Existing Cabs
                foreach($RouteServiceSummary->cabs as $cab_index =>$cab_item){
                    $cab = optional(optional($cab_item->divisionCab)->cab);
                    $existing_cabs[] = [
                        'name'     => $cab->title ? $cab->title . ' (' . $cab->capacity . 'S)' : 'N/A',
                        'capacity' => $cab->capacity ?? null,
                        'id'       => $cab->id ?? '',
                        'price'    => $cab_item->cab_price ?? 0,
                    ];
                }
                // Filter cabs where capacity >= total_member
                $filtered_cabs = array_filter($existing_cabs, function ($cab) use ($total_member) {
                    return $cab['capacity'] >= $total_member;
                });
            
                // Sort by capacity ASC (to get smallest that fits)
                usort($filtered_cabs, function ($a, $b) {
                    return $a['capacity'] <=> $b['capacity'];
                });
                $best_match_cab = $filtered_cabs[0] ?? null;
                if($best_match_cab){
                    $totalPrice = round((float) $best_match_cab['price']);
                    // Update or create itinerary detail
                    ItineraryDetail::updateOrCreate(
                        [
                            'itinerary_id' => $this->itinerary_id,
                            'header' => "day_$index", // Using a dynamic day header
                            'field' => 'per_day_cab',
                            'value' => $best_match_cab['name'],
                        ],
                        [
                            'value' => $best_match_cab['name'], // Store the activity name or ID
                            'price' => $totalPrice, // Store calculated price
                            'value_quantity' => 1,
                            'cab_id' => $best_match_cab['id'],
                        ]
                    );
                }
            }
            
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            session()->flash('error', 'Deletion failed: ' . $e->getMessage());
        }
        
    }
    // Check Existing Itinerary Details

    public function generateHotelData($index,$hotel_id,$season){
        DB::beginTransaction();
        try {
            // Update or create itinerary detail
            $selected_hotel = Hotel::find($hotel_id);
            ItineraryDetail::updateOrCreate(
                [
                    'itinerary_id' => $this->itinerary_id,
                    'header' => "day_$index", // Assuming you meant to use 'day_{index}'
                    'field' => 'day_hotel',
                ],
                [
                    'value' => $selected_hotel->name, // Store the hotel ID
                    'hotel_id' => $selected_hotel->id,
                ]
            );


            // Room Add
            // Fetch room by lowest price
            $capacity = $this->getCapacity();
            $lowest_price = HotelPriceChart::where('hotel_id', $selected_hotel->id)
                ->where('type', 2)
                ->where('plan_title', $season)
                ->where('plan_item', $this->leadData->meal_type)
                ->where('item_price', '>', 0)
                ->whereHas('room', function ($query) use ($capacity) {
                    $query->where('capacity', '>=', $capacity);
                })
                ->orderBy('item_price', $this->budgetSortOrder)
                ->first();

            $priority_room = $selected_hotel->priority_rooms()
                ->where('capacity', '>=', $capacity)
                ->orderBy('positions', 'ASC')
                ->first();
            $selected_room = $lowest_price ? $lowest_price->room : $priority_room;
            // dd($selected_room);
            if ($selected_room) {
                $room_data = ItineraryDetail::where('itinerary_id', $this->itinerary_id)->where('header', 'day_' . $index)->whereNotNull('room_id')->get();
                if (count($room_data)>0) {
                    $room_data->each(function ($detail) {
                        $detail->delete();
                    });
                }
                // Update or Create Room
                ItineraryDetail::updateOrCreate(
                    [
                        'itinerary_id' => $this->itinerary_id,
                        'header' => "day_$index", // Assuming you meant to use 'day_{index}'
                        'hotel_id' => $selected_hotel->id,
                        'field' => 'day_room',
                    ],
                    [
                        'value' => $selected_room->room_name, // Store the hotel ID
                        'room_id' => $selected_room->id,
                    ]
                );

                // Get Main Plan
                $room_single_price = HotelPriceChart::where('hotel_id', $selected_hotel->id)->where('room_id',$selected_room->id)
                ->where('type', 2)
                ->where('plan_title', $season)
                ->where('plan_item', $this->leadData->meal_type)
                ->where('item_price', '>', 0)
                ->whereHas('room', function ($query) use ($capacity) {
                    $query->where('capacity', '>=', $capacity);
                })
                ->orderBy('item_price', 'ASC')
                ->value('item_price');

            
                $room_single_price = $room_single_price?$room_single_price:0;
                
                // $room_value = $this->leadData->number_of_adults / $selected_room->no_of_beds;
                // $room_quantity = $room_value < 0 ? floor($room_value) : ceil($room_value);
            
                $capacity = $this->getCapacity();
                // Update or Create Room Main Plan
                $day_room_main_plan = ItineraryDetail::updateOrCreate(
                    [
                        'itinerary_id' => $this->itinerary_id,
                        'header' => "day_$index", // Assuming you meant to use 'day_{index}'
                        'hotel_id' => $selected_hotel->id,
                        'room_id' => $selected_room->id,
                        'field' => 'day_room_main_plan',
                    ],
                    [
                        'value' => $this->leadData->meal_type,
                        'value_quantity' => $this->leadData->number_of_adults,
                        'price' => $room_single_price*$this->leadData->number_of_rooms,
                    ]
                );

                $this->setNewroomItem($index, $selected_hotel->id,$selected_room->id);

            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Action failed: ' . $e->getMessage());
        }
    }

    protected function setNewroomItem($index, $hotel_id,$room_id){
        $selected_hotel = Hotel::find($hotel_id);
        $selected_room = Room::find($room_id);

        if($this->leadData->number_of_children>0){
            $children_data = json_decode($this->leadData->children_data);
            foreach($children_data as $child_index=>$child_item){
            
                $room_addon_cwm = HotelPriceChart::where('hotel_id', $selected_hotel->id)
                ->where('type', 2)
                ->where('room_id', $selected_room->id)
                ->where('plan_title', 'CWM')
                ->where('plan_item', $child_item->age)
                // ->where('item_price', '>', 0)
                ->orderBy('item_price', 'ASC')
                ->first();

                if($room_addon_cwm){
                    // Child with Mattress
                    $field_data = "day_room_addon_plan_cwm";
                    ItineraryDetail::updateOrCreate(
                        [
                            'itinerary_id' => $this->itinerary_id,
                            'hotel_id' => $selected_hotel->id,
                            'room_id' => $selected_room->id,
                            'header' => "day_$index", // Using a dynamic day header
                            'field' => $field_data, // Fixing field name
                            'value' => $child_item->age, // Store the activity name or ID
                        ],
                        [
                            'value_quantity' => $child_item->quantity, // Store calculated price
                            'price' => $room_addon_cwm->item_price*$child_item->quantity, // Store calculated price
                        ]
                    );
                }
            }
        }
        if(isset($this->leadData->extra_mattress) && $this->leadData->extra_mattress>0){
            $extra_mattress = 0;
            $number_of_extra_mattress = $this->leadData->extra_mattress;
            if($selected_room->mattress>0){
                $extra_mattress+= $this->leadData->number_of_rooms*$selected_room->mattress;
                $need_quantity = 0;
                if($number_of_extra_mattress>$extra_mattress){
                    $need_quantity = $number_of_extra_mattress-$extra_mattress;
                    $available_quantity = $extra_mattress;
                }else{
                    $available_quantity = $number_of_extra_mattress;
                }

                $room_addon_mattress = HotelPriceChart::where('hotel_id', $selected_hotel->id)
                ->where('type', 2)
                ->where('room_id', $selected_room->id)
                ->where('plan_title', 'Mattress')
                ->where('item_price', '>', 0)
                ->orderBy('item_price', 'ASC')
                ->first();
                if($room_addon_mattress){
                    // Child with Mattress
                    $field_data = "day_room_addon_plan_mattress";
                    ItineraryDetail::updateOrCreate(
                        [
                            'itinerary_id' => $this->itinerary_id,
                            'hotel_id' => $selected_hotel->id,
                            'room_id' => $selected_room->id,
                            'header' => "day_$index", // Using a dynamic day header
                            'field' => $field_data, // Fixing field name
                            'value' => $room_addon_mattress->plan_item, // Store the activity name or ID
                        ],
                        [
                            'value_quantity' => $available_quantity, // Store calculated price
                            'price' => $room_addon_mattress->item_price*$available_quantity, // Store calculated price
                        ]
                    );
                }
                
            }
        }
    }
    public function addAboutDescHighlight()
    {
        $this->trip_highlights[] = ''; // Add an empty input field
    }

    public function removeAboutDescHighlight($index)
    {
        unset($this->trip_highlights[$index]); // Remove highlight
        $this->trip_highlights = array_values($this->trip_highlights); // Re-index array
        $this->deleteHighlightFromDB($index);
    }
    public function deleteHighlightFromDB($index)
    {
        $fieldName = 'trip_highlights_' . $index;
        ItineraryDetail::where('itinerary_id', $this->itinerary_id)
            ->where('header', 'about_destination')
            ->where('field', $fieldName)
            ->delete();
    }

    public function loadDayImages($index){
        $this->dayImages[$index] = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
            ->where('header', 'day_' . $index)
            ->where('field', 'day_image')
            ->pluck('value')
            ->toArray();
    }
    public function loadDayHotels($index){
        if($index<$this->leadData->travel_in_days){
            // Fetch itinerary detail with hotel relation
            $data = ItineraryDetail::with('hotel')
            ->where('itinerary_id', $this->itinerary_id)
            ->where('header', 'day_' . $index)
            ->where('field', 'day_hotel')
            ->get();

            // Ensure hotel data exists before assigning
            $results = [];
            $capacity = $this->getCapacity();
            // Loop through each itinerary detail and extract hotel data
            $dayExtraHotels[$index] = [];
            foreach ($data as $hotel_index=> $item) {
                if ($hotel_index==0 && $item->hotel) {
                    $room = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
                    ->where('hotel_id', $item->hotel_id)
                    ->where('header', 'day_' . $index)
                    ->where('field', 'day_room')
                    ->first();
                    $selected_room_main_plan = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
                    ->where('hotel_id', $item->hotel_id)
                    ->where('header', 'day_' . $index)
                    ->where('field', 'day_room_main_plan')
                    ->first();

                    if($room){
                        $travelDate = Carbon::parse($this->leadData->travel_date);
                        $date = $travelDate->copy()->addDay($index-1);
                        $season = CustomHelper::GetSeason($this->leadData->travel_location, $date->format('m-d'));
                        $this->selected_rooms[$index] = $room->room_id;
                        $this->FetchRoomPlan($index, $room->room_id, $season);
                    }

                    $results[] = [
                        'hotel_id'      => optional($item->hotel)->id,
                        'hotel_name'    => optional($item->hotel)->name,
                        'hotel_category_name' => optional($item->hotel?->CategoryData)->name,
                        'hotel_image'    => optional($item->hotel)->image,
                        'hotel_address' => optional($item->hotel)->address,
                        'hotel_rooms'   => optional($item->hotel)->rooms->where('capacity', '>=', $capacity),
                        'selected_room'   => $room?$room->room_id:null,
                        'selected_room_name' => $room?->room?->room_name ?? null,
                        'selected_room_main_plan' => $selected_room_main_plan?$selected_room_main_plan:null,
                    ];
                    // dd($results);
                    $season = CustomHelper::GetSeason($this->leadData->travel_location, $travelDate->format('m-d'));
                    $meal_type = $this->leadData->meal_type;
                    // Similar Extra Hotel
                    $selectedDayHotelsId = [];
                    
                    if(isset($this->day_by_divisions[$index]['day_hotel'])){
                        foreach ($this->day_by_divisions[$index]['day_hotel'] as $day_hotel_index => $day_hotel) {
                            $selectedDayHotelsId[$day_hotel_index] = isset($day_hotel['hotel_id']) && $day_hotel['hotel_id']
                                ? $day_hotel['hotel_id']
                                : $day_hotel['id'];
                        }
                    }

                    $extraHotels = Hotel::with([
                            'priority_rooms',
                            'rooms' => function ($query) use ($capacity) {
                                $query->where('capacity', '>=', $capacity);
                            },
                            'price_chart' => function ($query) use ($season, $meal_type) {
                                $query->where('plan_title', ucwords($season))
                                    ->where('plan_item', $meal_type)
                                    ->where('item_price', '>', 0);
                            }
                        ])
                        ->when($this->loadDayHotelsTime == 2, function ($query) use ($selectedDayHotelsId) {
                            $query->whereIn('id', $selectedDayHotelsId);
                        })
                        ->where('division', optional($item->hotel)->division)
                        ->where('hotel_category', optional($item->hotel)->hotel_category)
                        ->whereHas('rooms', function ($query) use ($capacity) {
                            $query->where('capacity', '>=', $capacity);
                        })
                        ->whereHas('price_chart', function ($query) use ($season, $meal_type) {
                            $query->where('plan_title', ucwords($season))
                                ->where('plan_item', $meal_type)
                                ->where('item_price', '>', 0);
                        })
                        ->where('id', '!=', $item->hotel_id)
                        ->get();

                    // Sort by item_price (min or max) using price_chart
                    $dayExtraHotels[$index] = $extraHotels
                        ->sortBy(function ($hotel) {
                            return $hotel->price_chart->first()->item_price ?? PHP_INT_MAX;
                        }, SORT_REGULAR, $this->package_budget === 'max')
                        ->values()
                        ->take(2) // apply limit AFTER sorting
                        ->toArray();
                }
            }
            $this->activeTab[$index] = 0;
            $this->dayExtraHotels[$index] = $dayExtraHotels[$index];
            $merged = array_merge($results, $dayExtraHotels[$index]);
            return $merged;
            // Merge and assign the updated values
        }
        
    }
    public function ReloadDayHotels($index){
        if($index<$this->leadData->travel_in_days){
            // Fetch itinerary detail with hotel relation
            $data = ItineraryDetail::with('hotel')
            ->where('itinerary_id', $this->itinerary_id)
            ->where('header', 'day_' . $index)
            ->where('field', 'day_hotel')
            ->get();
            // Ensure hotel data exists before assigning
            $results = [];
            $capacity = $this->getCapacity();
            // Loop through each itinerary detail and extract hotel data
            foreach ($data as $item_index=>$item) {
                if ($item_index==0 && $item->hotel) {
                    $room = ItineraryDetail::with('hotel')
                    ->where('itinerary_id', $this->itinerary_id)
                    ->where('hotel_id', $item->hotel_id)
                    ->where('header', 'day_' . $index)
                    ->where('field', 'day_room')
                    ->first();
                    $selected_room_main_plan = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
                    ->where('hotel_id', $item->hotel_id)
                    ->where('header', 'day_' . $index)
                    ->where('field', 'day_room_main_plan')
                    ->first();


                    if($room){
                        $travelDate = Carbon::parse($this->leadData->travel_date);
                        $date = $travelDate->copy()->addDay($index-1);
                        $season = CustomHelper::GetSeason($this->leadData->travel_location, $date->format('m-d'));

                        $this->selected_rooms[$index] = $room->room_id;
                        $this->FetchRoomPlan($index, $room->room_id,$season);
                    }
                    
                    $results[] = [
                        'hotel_id'      => optional($item->hotel)->id,
                        'hotel_division'      => optional($item->hotel)->division,
                        'hotel_category'      => optional($item->hotel)->hotel_category,
                        'hotel_name'    => optional($item->hotel)->name,
                        'hotel_category_name' => optional($item->hotel?->CategoryData)->name,
                        'hotel_image'    => optional($item->hotel)->image,
                        'hotel_address' => optional($item->hotel)->address,
                        'hotel_rooms'   => optional($item->hotel)->rooms->where('capacity', '>=', $capacity),
                        'selected_room'   => $room?$room->room_id:null,
                        'selected_room_name' => $room?->room?->room_name ?? null,
                        'selected_room_main_plan'   => $selected_room_main_plan?$selected_room_main_plan:null,
                    ];

                    foreach ($this->day_by_divisions[$index]['day_hotel'] as $k => $day_hotel) {
                        if (isset($day_hotel['id']) && $day_hotel['id'] === optional($item->hotel)->id) {
                            $this->day_by_divisions[$index]['day_hotel'][$k] = $results[0];
                        }else{
                            $hotelId = isset($day_hotel['id']) ? $day_hotel['id'] : $day_hotel['hotel_id'];
                            $hotel = Hotel::with([
                                    'priority_rooms',
                                    'rooms',
                                    'price_chart'
                                ])
                                ->where('id', $hotelId)
                                ->first();

                            $hotels = $hotel ? $hotel->toArray() : [];
                            
                            if($hotelId === optional($item->hotel)->id){
                                $this->day_by_divisions[$index]['day_hotel'][$k] = $results[0];
                            }else{
                                $this->dayExtraHotels[$index][$k] = $hotels;
                                $this->day_by_divisions[$index]['day_hotel'][$k] = $hotels;
                            }
                            
                        }
                    }
                    // dd($this->day_by_divisions[$index]['day_hotel']);

                }
            }
        }
    }
    public function ReloadDayHotelsByCategory($index){
        if($index<$this->leadData->travel_in_days){
            // Fetch itinerary detail with hotel relation
            $data = ItineraryDetail::with('hotel')
            ->where('itinerary_id', $this->itinerary_id)
            ->where('header', 'day_' . $index)
            ->where('field', 'day_hotel')
            ->get();
            // dd($data);
            // Ensure hotel data exists before assigning
            $results = [];
            $capacity = $this->getCapacity();
            // Loop through each itinerary detail and extract hotel data
            foreach ($data as $item_index=>$item) {
                if ($item_index==0 && $item->hotel) {
                    $room = ItineraryDetail::with('hotel')
                    ->where('itinerary_id', $this->itinerary_id)
                    ->where('hotel_id', $item->hotel_id)
                    ->where('header', 'day_' . $index)
                    ->where('field', 'day_room')
                    ->first();
                    $selected_room_main_plan = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
                    ->where('hotel_id', $item->hotel_id)
                    ->where('header', 'day_' . $index)
                    ->where('field', 'day_room_main_plan')
                    ->first();


                    if($room){
                        $travelDate = Carbon::parse($this->leadData->travel_date);
                        $date = $travelDate->copy()->addDay($index-1);
                        $season = CustomHelper::GetSeason($this->leadData->travel_location, $date->format('m-d'));

                        $this->selected_rooms[$index] = $room->room_id;
                        $this->FetchRoomPlan($index, $room->room_id,$season);
                    }
                    
                    $results[] = [
                        'hotel_id'      => optional($item->hotel)->id,
                        'hotel_division'      => optional($item->hotel)->division,
                        'hotel_category'      => optional($item->hotel)->hotel_category,
                        'hotel_name'    => optional($item->hotel)->name,
                        'hotel_category_name' => optional($item->hotel?->CategoryData)->name,
                        'hotel_image'    => optional($item->hotel)->image,
                        'hotel_address' => optional($item->hotel)->address,
                        'hotel_rooms'   => optional($item->hotel)->rooms->where('capacity', '>=', $capacity),
                        'selected_room'   => $room?$room->room_id:null,
                        'selected_room_name' => $room?->room?->room_name ?? null,
                        'selected_room_main_plan'   => $selected_room_main_plan?$selected_room_main_plan:null,
                    ];
                }
            }

            $this->day_by_divisions[$index]['day_hotel'] = $results;
            
            if(count($results)>0){
                // Similar Extra Hotel
                $meal_type = $this->leadData->meal_type;
               
                $travelDate = Carbon::parse($this->leadData->travel_date);
                $date = $travelDate->copy()->addDay($index-1);
                $season = CustomHelper::GetSeason($this->leadData->travel_location, $date->format('m-d'));
                $dayExtraHotels[$index] = [];
                $hotels = Hotel::with([
                        'priority_rooms',
                        'rooms' => function ($query) use ($capacity) {
                            $query->where('capacity', '>=', $capacity);
                        },
                        'price_chart' => function ($query) use ($season, $meal_type) {
                            $query->where('plan_title', ucwords($season))
                                ->where('plan_item', $meal_type)
                                ->where('item_price', '>', 0);
                        }
                    ])
                    ->where('division', $results[0]['hotel_division'])
                    ->where('hotel_category', $results[0]['hotel_category'])
                    ->whereHas('rooms', function ($query) use ($capacity) {
                        $query->where('capacity', '>=', $capacity);
                    })
                    ->whereHas('price_chart', function ($query) use ($season, $meal_type) {
                        $query->where('plan_title', ucwords($season))
                            ->where('plan_item', $meal_type)
                            ->where('item_price', '>', 0);
                    })
                    ->where('id', '!=', $results[0]['hotel_id'])
                    ->get();

                // Apply budget-based price sort in memory and limit to 2
                $dayExtraHotels[$index] = $hotels
                    ->sortBy(function ($hotel) {
                        return $hotel->price_chart->first()->item_price ?? PHP_INT_MAX;
                    }, SORT_REGULAR, $this->package_budget === 'max')
                    ->values()
                    ->take(2)
                    ->toArray();
                    
                $this->dayExtraHotels[$index] = $dayExtraHotels[$index];
                
                $merged = array_merge(
                    $this->day_by_divisions[$index]['day_hotel'] ?? [],
                    $dayExtraHotels[$index] ?? [] 
                );

               $activeIndex = $this->activeTab[$index];
                $newMergedArray = $merged;

                // Check if active index exists and is not 0
                if (isset($merged[$activeIndex]) && $activeIndex !== 0) {
                    // Swap index 0 and active index
                    $temp = $newMergedArray[0];
                    $newMergedArray[0] = $newMergedArray[$activeIndex];
                    $newMergedArray[$activeIndex] = $temp;
                }

                // Re-index to 0,1,2...
                $newMergedArray = array_values($newMergedArray);

                $this->day_by_divisions[$index]['day_hotel'] = $newMergedArray;
            }
        }
    }

    public function changeDayHotelCategory($category_id, $index,$division)
    {
        // Reset the index first
        $this->loadDayHotelsTime = 1;
        $this->day_hotel_category[$index] = null;

        // Now assign the new category_id
        $this->day_hotel_category[$index] = (int) $category_id;
        $this->SetHotel($index, $division);
        $this->ReloadDayHotelsByCategory($index);
    }
    public function loadDayRoutes($index){
        // Fetch itinerary detail with hotel relation
        $data = ItineraryDetail::with('route_service')
        ->where('itinerary_id', $this->itinerary_id)
        ->where('header', 'day_' . $index)
        ->where('field', 'day_route')
        ->get();

        // Ensure hotel data exists before assigning
        $results = [];
       // Loop through each itinerary detail and extract hotel data
        foreach ($data as $item) {

            $day_activity = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
            ->where('route_service_summary_id', $item->route_service_summary_id)
            ->where('header', 'day_' . $index)
            ->where('field', 'day_activity')
            ->get()->toArray();

            $day_sightseing = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
            ->where('route_service_summary_id', $item->route_service_summary_id)
            ->where('header', 'day_' . $index)
            ->where('field', 'day_sightseeing')
            ->get()->toArray();

            $day_cab = ItineraryDetail::with('cab')->where('itinerary_id', $this->itinerary_id)
            ->where('route_service_summary_id', $item->route_service_summary_id)
            ->where('header', 'day_' . $index)
            ->where('field', 'day_cab')
            ->get()->toArray();

            if ($item->route_service) {
                $RouteServiceSummary = RouteServiceSummary::with('activities', 'sightseeings', 'cabs')->find($item->route_service_summary_id);
                $existing_activities = [];
                $existing_sightseeings = [];
                $existing_cabs = [];
                if($RouteServiceSummary){
                    // Existing Activities
                    foreach($RouteServiceSummary->activities as $act_index =>$act_item){
                        $existing_activities[] = [
                            'name'         => optional($act_item->activity)->name,
                            'type'         => optional($act_item->activity)->type,
                            'price'        => optional($act_item->activity)->price,
                            'ticket_price' => $this->leadData->nationality_type=="Indian"?optional($act_item->activity)->ticket_price:optional($act_item->activity)->ticket_price_nri,
                        ];
                    }

                    // // Existing Sightsiings
                    foreach($RouteServiceSummary->sightseeings as $sight_index =>$sight_item){
                        $existing_sightseeings[] = [
                            'name'=> optional($sight_item->sightseeing)->name,
                            'type'         => optional($sight_item->sightseeing)->type,
                            'ticket_price' => $this->leadData->nationality_type=="Indian"?optional($sight_item->sightseeing)->ticket_price:optional($sight_item->sightseeing)->ticket_price_nri,
                        ];
                    }

                    // // Existing Cabs
                    foreach($RouteServiceSummary->cabs as $cab_index =>$cab_item){
                        $cab = optional(optional($cab_item->divisionCab)->cab);
                        $existing_cabs[]=[
                            'name'=> $cab->title ? $cab->title . ' (' . $cab->capacity . 'S)' : 'N/A',
                            'id'=> $cab->id ? $cab->id:"",
                            'price'=>$cab_item->cab_price ?? 0,
                        ];
                    }
                }
                $results[] = [
                    'route_service_summary_id'=> optional($item->route_service)->id,
                    'route_name' => $item->value,
                    'route_way_points' => optional(optional($item->route_service)->route)->waypoints?->toArray() ?? [],
                    'day_activity' => $day_activity,
                    'day_sightseing' => $day_sightseing,
                    'day_cab' => $day_cab,
                    'existing_activities' => $existing_activities,
                    'existing_sightseeings' => $existing_sightseeings,
                    'existing_cabs' => $existing_cabs,
                ];
            }
        }
        // dd($results);
        return $results;
        // Merge and assign the updated values
    }
    public function ReloadDayRoute($index){
        // Fetch itinerary detail with hotel relation
        $data = ItineraryDetail::with('hotel')
        ->where('itinerary_id', $this->itinerary_id)
        ->where('header', 'day_' . $index)
        ->where('field', 'day_route')
        ->get();

        // Ensure hotel data exists before assigning
        $results = [];
       // Loop through each itinerary detail and extract hotel data
        foreach ($data as $item) {
            $day_activity = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
            ->where('route_service_summary_id', $item->route_service_summary_id)
            ->where('header', 'day_' . $index)
            ->where('field', 'day_activity')
            ->get()->toArray();

            $day_sightseing = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
            ->where('route_service_summary_id', $item->route_service_summary_id)
            ->where('header', 'day_' . $index)
            ->where('field', 'day_sightseeing')
            ->get()->toArray();

            $day_cab = ItineraryDetail::with('cab')->where('itinerary_id', $this->itinerary_id)
            ->where('route_service_summary_id', $item->route_service_summary_id)
            ->where('header', 'day_' . $index)
            ->where('field', 'day_cab')
            ->get()->toArray();

            if ($item->route_service) {
                $RouteServiceSummary = RouteServiceSummary::with('activities', 'sightseeings', 'cabs')->find($item->route_service_summary_id);
                $existing_activities = [];
                $existing_sightseeings = [];
                $existing_cabs = [];

                if($RouteServiceSummary){
                    // Existing Activities
                    foreach($RouteServiceSummary->activities as $act_index =>$act_item){
                        $existing_activities[] = [
                            'name'         => optional($act_item->activity)->name,
                            'type'         => optional($act_item->activity)->type,
                            'price'        => optional($act_item->activity)->price,
                            'ticket_price' => $this->leadData->nationality_type=="Indian"?optional($act_item->activity)->ticket_price:optional($act_item->activity)->ticket_price_nri,
                        ];
                    }

                    // // Existing Sightsiings
                    foreach($RouteServiceSummary->sightseeings as $sight_index =>$sight_item){
                        $existing_sightseeings[] = [
                            'name'=> optional($sight_item->sightseeing)->name,
                            'type'         => optional($sight_item->sightseeing)->type,
                            'ticket_price' => $this->leadData->nationality_type=="Indian"?optional($sight_item->sightseeing)->ticket_price:optional($sight_item->sightseeing)->ticket_price_nri,
                        ];
                    }

                    // // Existing Cabs
                    foreach($RouteServiceSummary->cabs as $cab_index =>$cab_item){
                        $cab = optional(optional($cab_item->divisionCab)->cab);
                        $existing_cabs[]=[
                            'name'=> $cab->title ? $cab->title . ' (' . $cab->capacity . 'S)' : 'N/A',
                            'id'=> $cab->id ? $cab->id : '',
                            'price'=>$cab_item->cab_price ?? 0,
                        ];
                    }
                }
                $results[] = [
                    'route_service_summary_id'=> optional($item->route_service)->id,
                    'route_name' => $item->value,
                    'route_way_points' => optional(optional($item->route_service)->route)->waypoints?->toArray() ?? [],
                    'day_activity' => $day_activity,
                    'day_sightseing' => $day_sightseing,
                    'day_cab' => $day_cab,
                    'existing_activities' => $existing_activities,
                    'existing_sightseeings' => $existing_sightseeings,
                    'existing_cabs' => $existing_cabs,
                ];
            }
        }

        $this->day_by_divisions[$index]['day_route'] = $results;
        // Merge and assign the updated values
    }

    public function loadDayCabs($index){
       // Ensure hotel data exists before assigning
       $results = [];

           $day_cab = ItineraryDetail::with('cab')->where('itinerary_id', $this->itinerary_id)
           ->where('header', 'day_' . $index)
           ->where('field', 'per_day_cab')
           ->get()->toArray();

            $RouteServiceSummary = RouteServiceSummary::with('cabs')->where('destination_id', $this->destinationId)->where('service_type', 'Per Day')->first();
            $existing_cabs = [];
            if($RouteServiceSummary){
                // // Existing Cabs
                foreach($RouteServiceSummary->cabs as $cab_index =>$cab_item){
                    $cab = optional(optional($cab_item->divisionCab)->cab);
                    $existing_cabs[]=[
                        'name'=> $cab->title ? $cab->title . ' (' . $cab->capacity . 'S)' : 'N/A',
                        'id'=> $cab->id ? $cab->id:"",
                        'price'=>$cab_item->cab_price ?? 0,
                    ];
                }
            }
            $results[] = [
                'day_cab' => $day_cab,
                'per_day_existing_cabs' => $existing_cabs,
            ];
       return $results;
    }
    public function ReloadDayCabs($index){
       // Ensure hotel data exists before assigning
       $results = [];

           $day_cab = ItineraryDetail::with('cab')->where('itinerary_id', $this->itinerary_id)
           ->where('header', 'day_' . $index)
           ->where('field', 'per_day_cab')
           ->get()->toArray();

            $RouteServiceSummary = RouteServiceSummary::with('cabs')->where('destination_id', $this->destinationId)->where('service_type', 'Per Day')->first();
            $existing_cabs = [];
            if($RouteServiceSummary){
                // // Existing Cabs
                foreach($RouteServiceSummary->cabs as $cab_index =>$cab_item){
                    $cab = optional(optional($cab_item->divisionCab)->cab);
                    $existing_cabs[]=[
                        'name'=> $cab->title ? $cab->title . ' (' . $cab->capacity . 'S)' : 'N/A',
                        'id'=> $cab->id ? $cab->id:"",
                        'price'=>$cab_item->cab_price ?? 0,
                    ];
                }
            }
            $results[] = [
                'day_cab' => $day_cab,
                'per_day_existing_cabs' => $existing_cabs,
            ];
        $this->day_by_divisions[$index]['per_day_cab'] = $results;
    }


    // public function ReloadDayActivity($index, $route_index, $route_service_summary_id){
    //     // Fetch itinerary detail with hotel relation
    //     $day_activity = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
    //     ->where('route_service_summary_id', $route_service_summary_id)
    //     ->where('header', 'day_' . $index)
    //     ->where('field', 'day_activity')
    //     ->get()->toArray();

    //     $this->day_by_divisions[$index]['day_route'][$route_index]['day_activity'] = $day_activity;
        
    // }
   

    public function RemoveDayHotel($index, $field, $hotel_id){
        DB::beginTransaction();
        try {
            $item = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
            ->where('header', 'day_' . $index)
            ->where('field', $field)
            ->where('hotel_id', $hotel_id)
            ->first();
            $hotel_details = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
            ->where('header', 'day_' . $index)
            ->where('hotel_id', $hotel_id)
            ->get();
    
            if ($item) {
                // Delete from database
                $item->delete();
                if ($hotel_details) {
                    $hotel_details->each(function ($detail) {
                        $detail->delete();
                    });
                }
                DB::commit();
                session()->flash('success', 'Hotel deleted successfully!');
            } else {
                session()->flash('error', 'Hotel not found in the database!');
            }
            $this->ReloadDayHotels($index);
    
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Deletion failed: ' . $e->getMessage());
        }
    }
    public function RemoveDayRoute($index, $field, $route_service_summary_id){
        DB::beginTransaction();
        try {
            $item = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
            ->where('header', 'day_' . $index)
            ->where('field', $field)
            ->where('route_service_summary_id', $route_service_summary_id)
            ->first();

            $sub_items = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
            ->where('header', 'day_' . $index)
            ->where('route_service_summary_id', $route_service_summary_id)
            ->whereIn('field', ['day_cab', 'day_sightseeing', 'day_activity'])
            ->get();

            if ($item) {
                // Delete from database
                $item->delete();
                // Delete multiple records
                $sub_items->each(function ($sub_item) {
                    $sub_item->delete();
                });
                DB::commit();
                session()->flash('success', 'Route deleted successfully!');
            } else {
                session()->flash('error', 'Route not found in the database!');
            }
            $this->ReloadDayRoute($index);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Deletion failed: ' . $e->getMessage());
        }
    }

    public function RemoveDayRouteItem($index, $id){
        DB::beginTransaction();
        try {
            $item = ItineraryDetail::find($id);
            
            if ($item) {
                $item->delete();
                DB::commit();
                session()->flash('success', 'Item deleted successfully.');
            } else {
                DB::rollBack();
                session()->flash('error', 'Item not found.');
            }
            $this->ReloadDayRoute($index);
            if(isset($item->room_id)){
                $this->ReloadDayHotels($index);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Deletion failed: ' . $e->getMessage());
        }
    }
    public function RemovePerDayCabItem($index, $id){
        DB::beginTransaction();
        try {
            $item = ItineraryDetail::find($id);
            
            if ($item) {
                $item->delete();
                DB::commit();
                session()->flash('success', 'Item deleted successfully.');
            } else {
                DB::rollBack();
                session()->flash('error', 'Item not found.');
            }
            $this->ReloadDayCabs($index);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Deletion failed: ' . $e->getMessage());
        }
    }
   
    public function deleteDayImage($imgPath, $index){
        DB::beginTransaction();
    
        try {
            $item = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
            ->where('header', 'day_' . $index)
            ->where('field', 'day_image')
            ->where('value', $imgPath)
            ->first();
    
            if ($item) {
                // Delete file from storage
                $filePath = $item->value; // Example: "storage/itinerary/west-bangal-5280-20250312093144.jpg"

                // Check if file exists using correct path
                if (Storage::exists(str_replace('storage/', 'public/', $filePath))) {
                    Storage::delete(str_replace('storage/', 'public/', $filePath));
                }
    
                // Delete from database
                $item->delete();
    
                 // Refresh images list
                $this->loadDayImages($index);
    
                DB::commit();
                session()->flash('success', 'Image deleted successfully!');
            } else {
                session()->flash('error', 'Image not found in the database!');
            }
    
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Deletion failed: ' . $e->getMessage());
        }
    }


    // public function getHotel($index,$id){
    //     try {
    //         if (!$id) {
    //             throw new \Exception("Invalid hotel ID");
    //         }
    
    //         // Find the hotel
    //         $hotel = Hotel::find($id);
    //         if (!$hotel) {
    //             throw new \Exception("Hotel not found");
    //         }
    
    //         // Delete Existing Another Hotel Details
    //         $hotel_details = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
    //         ->where('header', 'day_' . $index)
    //         ->whereNotNull('hotel_id')
    //         ->get();
    //         if ($hotel_details) {
    //             $hotel_details->each(function ($detail) {
    //                 $detail->delete();
    //             });
    //         }
    //         // Start database transaction
    //         DB::beginTransaction();
    
    //         // Update or create itinerary detail
    //         ItineraryDetail::updateOrCreate(
    //             [
    //                 'itinerary_id' => $this->itinerary_id,
    //                 'header' => "day_$index", // Assuming you meant to use 'day_{index}'
    //                 'field' => 'day_hotel',
    //             ],
    //             [
    //                 'value' => $hotel->name, // Store the hotel ID
    //                 'hotel_id' => $hotel->id,
    //             ]
    //         );
    
    //         // Commit transaction

    //         DB::commit();
    //         $this->ReloadDayHotels($index);
    //         $this->errorHotel[$index] = '';
    //     } catch (\Exception $e) {
    //         // Rollback transaction if there's an error
    //         DB::rollBack();
    
    //         // Store error message for Livewire validation
    //         $this->errorHotel[$index] = $e->getMessage();
    //     }
    // }
    public function getRoute($index,$id){
        try {
            if (!$id) {
                throw new \Exception("Invalid hotel ID");
            }
    
            // Find the hotel
            $summary = RouteServiceSummary::find($id);
            if (!$summary) {
                throw new \Exception("Route Service not found");
            }
    
            // Start database transaction
            DB::beginTransaction();
    
            // Update or create itinerary detail
            ItineraryDetail::updateOrCreate(
                [
                    'itinerary_id' => $this->itinerary_id,
                    'route_service_summary_id' => $summary->id,
                    'header' => "day_$index", // Assuming you meant to use 'day_{index}'
                    'field' => 'day_route',
                ],
                [
                    'value' => $summary->route?$summary->route->route_name:"N/A", // Store the hotel ID
                ]
            );
    
            // sightseeings
            foreach($summary->sightseeings as $sight_index =>$sight_item){

                // Get ticket price based on nationality
                $ticket_price = (float)($this->leadData->nationality_type=="Indian"?(optional($sight_item->sightseeing)->ticket_price??0):(optional($sight_item->sightseeing)->ticket_price_nri??0));

                // Quantity logic: If ticket exists, use number of travelers, else default to 1
                $value_quantity = $ticket_price > 0 ? $this->leadData->number_of_travellor : 1;

                // Optional base sightseeing price (if exists)
                $base_price = (float)(optional($sight_item->sightseeing)->price ?? 0);

                // Calculate total ticket cost
                $total_ticket_price = $ticket_price > 0 ? $ticket_price * $this->leadData->number_of_travellor : 0;

                // Total combined price
                $totalPrice = round($base_price + $total_ticket_price);

                ItineraryDetail::updateOrCreate(
                    [
                        'itinerary_id' => $this->itinerary_id,
                        'route_service_summary_id' => $sight_item->service_summary_id,
                        'header' => "day_$index", // Using a dynamic day header
                        'field' => "day_sightseeing",
                        'value' => optional($sight_item->sightseeing)->name,
                    ],
                    [
                        'value' => optional($sight_item->sightseeing)->name, // Store the activity name or ID
                        'value_quantity' => $value_quantity,
                        'price' => $totalPrice,
                        'ticket_price' => round((float) $ticket_price * $this->leadData->number_of_travellor),
                    ]
                );
            }
            // activities
            foreach($summary->activities as $act_index =>$act_item){

                $ticket_price = (float) ($this->leadData->nationality_type == "Indian" 
                        ? (optional($act_item->activity)->ticket_price ?? 0) 
                        : (optional($act_item->activity)->ticket_price_nri ?? 0));

                $total_ticket_price = $ticket_price>0?$ticket_price*$this->leadData->number_of_travellor:0;
                $value_quantity = $ticket_price>0?$this->leadData->number_of_travellor:1;
                $totalPrice = round(
                    (float) (optional($act_item->activity)->price ?? 0) +$total_ticket_price
                );

                ItineraryDetail::updateOrCreate(
                    [
                        'itinerary_id' => $this->itinerary_id,
                        'route_service_summary_id' => $act_item->service_summary_id,
                        'header' => "day_$index", // Using a dynamic day header
                        'field' => "day_activity",
                        'value' => optional($act_item->activity)->name,
                    ],
                    [
                        'value' => optional($act_item->activity)->name, // Store the activity name or ID
                        'value_quantity' => $value_quantity,
                        'price' => $totalPrice,
                        'rate' => (float) (optional($act_item->activity)->price ?? 0),
                        'ticket_price' => round((float) $ticket_price * $this->leadData->number_of_travellor),
                    ]
                );
            }
            // cabs
            // foreach($summary->cabs as $cab_index =>$cab_item){
            //     $cab = optional(optional($cab_item->divisionCab)->cab);

            //     $totalPrice = (float) ($cab_item->cab_price ?? 0);
            //     ItineraryDetail::updateOrCreate(
            //         [
            //             'itinerary_id' => $this->itinerary_id,
            //             'route_service_summary_id' => $cab_item->service_summary_id,
            //             'header' => "day_$index", // Using a dynamic day header
            //             'field' => "day_cab",
            //             'value' => $cab->title ? $cab->title . ' (' . $cab->capacity . 'S)' : 'N/A',
            //         ],
            //         [
            //             'value' => $cab->title ? $cab->title . ' (' . $cab->capacity . 'S)' : 'N/A', // Store the activity name or ID
            //             'price' => $totalPrice,
            //             'cab_id' => $cab->id,
            //         ]
            //     );
            // }

            // Commit transaction
            DB::commit();
          
            $this->errorRoute[$index] = '';
            $this->ReloadDayRoute($index);
            $this->active_assign_route_modal = 0;
        } catch (\Exception $e) {
            // Rollback transaction if there's an error
            DB::rollBack();
            // dd($e->getMessage());
            // Store error message for Livewire validation
            $this->errorRoute[$index] = $e->getMessage();
        }
    }
    public function getActivityOrSightseeing($field, $index, $route_index, $route_service_summary_id, $value, $price, $ticket_price,$cab_id){
        try {
            // Start database transaction
            DB::beginTransaction();
            // Ensure price and ticket price are numeric
            $number_of_travellers = (int) ($this->leadData->number_of_travellor ?? 0);

            if ($field == 'activity') {
                
                $totalPrice = round((float) $price + ((float) $ticket_price * $number_of_travellers), 2);
            }
            if($field=='sightseeing'){
                $totalPrice = round((float) $ticket_price * $number_of_travellers);
            }
            if($field=='cab'){
                $totalPrice = round((float) $price * $number_of_travellers);
            }
           
            // Update or create itinerary detail
            ItineraryDetail::updateOrCreate(
                [
                    'itinerary_id' => $this->itinerary_id,
                    'route_service_summary_id' => $route_service_summary_id,
                    'header' => "day_$index", // Using a dynamic day header
                    'field' => "day_$field",
                    'value' => $value,
                ],
                [
                    'value' => $value, // Store the activity name or ID
                    'value_quantity' => $ticket_price>0?$this->leadData->number_of_travellor:1,
                    'price' => $totalPrice, // Store calculated price
                    'rate' => (float) $price,
                    'ticket_price' => round((float) $ticket_price * $number_of_travellers),
                    'cab_id' => $cab_id?$cab_id:NULL,
                ]
            );
    
            // Commit transaction
            DB::commit();
    
            // Clear any previous error message for this index
            if($field=='activity'){
                if (isset($this->errorActivity[$index][$route_index])) {
                    $this->errorActivity[$index][$route_index] = '';
                }
            }
            if($field=='sightseeing'){
                if (isset($this->errorSightSeeing[$index][$route_index])) {
                    $this->errorSightSeeing[$index][$route_index] = '';
                }
            }
            if($field=='cab'){
                if (isset($this->errorCab[$index][$route_index])) {
                    $this->errorCab[$index][$route_index] = '';
                }
            }
        
            // Reload day route after update
            $this->ReloadDayRoute($index);
        } catch (\Exception $e) {
            // Rollback transaction if there's an error
            DB::rollBack();
            // Store error message for Livewire validation
            // dd($e->getMessage());
            if($field=='activity'){
                $this->errorActivity[$index][$route_index] = $e->getMessage();
            }
            if($field=='sightseeing'){
                $this->errorSightSeeing[$index][$route_index] = $e->getMessage();
            }
            if($field=='cab'){
                $this->errorCab[$index][$route_index] = $e->getMessage();
            }
        }
    }
    public function confirm_for_all_day_cab($field, $index, $value, $price, $cab_id){
           $this->dispatch('ConfirmForAllDayCab', ['field' => $field, 'index'=>$index, 'value'=>$value, 'price'=>$price, 'cab_id'=>$cab_id]);
    }
    public function getPerDayCab($field, $index, $value, $price, $cab_id, $type){
        try {
            // Start database transaction
            DB::beginTransaction();

            if($field=='per_day_cab'){
                $totalPrice = round((float) $price);
            }

            if($type=="YES"){
                for($i=1;$i<=$this->leadData->travel_in_days;$i++){
                    // Update or create itinerary detail
                    ItineraryDetail::updateOrCreate(
                        [
                            'itinerary_id' => $this->itinerary_id,
                            'header' => "day_$i", // Using a dynamic day header
                            'field' => $field,
                        ],
                        [
                            'value' => $value, // Store the activity name or ID
                            'price' => $totalPrice, // Store calculated price
                            'cab_id' => $cab_id?$cab_id:NULL,
                        ]
                    );
            
                    // Commit transaction
                    DB::commit();
            
                    if (isset($this->errorPerDayCab[$i])) {
                        $this->errorPerDayCab[$i] = '';
                    }
                
                    // Reload day route after update
                    $this->ReloadDayCabs($i);
                }
            }else{
                // Update or create itinerary detail
                ItineraryDetail::updateOrCreate(
                    [
                        'itinerary_id' => $this->itinerary_id,
                        'header' => "day_$index", // Using a dynamic day header
                        'field' => $field,
                        'value' => $value,
                    ],
                    [
                        'value' => $value, // Store the activity name or ID
                        'price' => $totalPrice, // Store calculated price
                        'cab_id' => $cab_id?$cab_id:NULL,
                    ]
                );
        
                // Commit transaction
                DB::commit();
        
                if (isset($this->errorPerDayCab[$index])) {
                    $this->errorPerDayCab[$index] = '';
                }
            
                // Reload day route after update
                $this->ReloadDayCabs($index);
            }
            
           
            

        } catch (\Exception $e) {
            // Rollback transaction if there's an error
            DB::rollBack();
            // Store error message for Livewire validation
            // dd($e->getMessage());
            $this->errorPerDayCab[$index] = $e->getMessage();
        }
    }
    public function GetRoomAddonPlan($field, $index, $hotel_id, $room_id, $value, $price){
        try {
            // Start database transaction
            DB::beginTransaction();
            // Update or create itinerary detail
            $field_data = "day_room_addon_plan_" . str_replace(' ', '_', strtolower($field));
            if($field_data == 'day_room_addon_plan_mattress'){
                if($this->leadData->extra_mattress ==null){
                    $this->errorRoom[$index] = 'Sorry! you have not selected any extra mattress.';
                    return false;
                }
                $quantity = $this->leadData->extra_mattress;
                ItineraryDetail::updateOrCreate(
                    [
                        'itinerary_id' => $this->itinerary_id,
                        'hotel_id' => $hotel_id,
                        'room_id' => $room_id,
                        'header' => "day_$index", // Using a dynamic day header
                        'field' => $field_data, // Fixing field name
                        'value' => $value, // Store the activity name or ID
                    ],
                    [
                        'price'          => ($price && $quantity > 0) ? $price * $quantity : 0,
                        'value_quantity' => $quantity,
                    ]
                );

            }else{
                ItineraryDetail::updateOrCreate(
                    [
                        'itinerary_id' => $this->itinerary_id,
                        'hotel_id' => $hotel_id,
                        'room_id' => $room_id,
                        'header' => "day_$index", // Using a dynamic day header
                        'field' => $field_data, // Fixing field name
                        'value' => $value, // Store the activity name or ID
                    ],
                    [
                        'price' => $price?$price:0, // Store calculated price
                        'value_quantity' => 1, // Store calculated price
                    ]
                );
            }
            
    
            // Commit transaction
            DB::commit();
    
            // Reload day route after update
            $this->ReloadDayHotels($index);
            $this->errorRoom[$index] = "";
        } catch (\Exception $e) {
            // Rollback transaction if there's an error
            DB::rollBack();
            $this->errorRoom[$index] = $e->getMessage();
        }
    }
   
    public function OpenAssignRouteModal($index){
        $this->active_new_route_modal = 0;
        $this->active_assign_route_modal = $index;
    }

    public function OpenNewRouteModal($index){
        $this->active_assign_route_modal = 0;
        $this->active_new_route_modal = $index;
    }

    public function CreateNewRoute($index,$division_id){
        $this->errorNewRoute[$index] = '';

        try {
        // Start database transaction
        DB::beginTransaction();
            $checkExisting = DestinationWiseRoute::where('route_name', $this->new_route_name)
            ->where('destination_id', $this->destinationId)
            ->first();
        
            if(!$this->new_route_name){
                $this->errorNewRoute[$index] = 'Please enter route name.';
                return false;
            }
            if ($checkExisting) {
                $this->errorNewRoute[$index] = 'The route name "' . $this->new_route_name . '" already exists for this destination. Please choose another route name.';
                return false;
            }

            // Making New Route
            $RouteRecord = DestinationWiseRoute::create([
                'route_name' => $this->new_route_name, // Ensure this key exists
                'destination_id' => $this->destinationId, // Save 0 if empty
            ]);

            // Assign route on Service
            $summary = RouteServiceSummary::updateOrCreate(
                [
                    'route_id' => $RouteRecord->id,
                    'destination_id' => (int)$this->destinationId,
                ],
                [
                    'service_type' => "Route Wise",
                    'division_id' => $division_id,
                ]
            );

             ItineraryDetail::updateOrCreate(
                [
                    'itinerary_id' => $this->itinerary_id,
                    'route_service_summary_id' => $summary->id,
                    'header' => "day_$index", // Assuming you meant to use 'day_{index}'
                    'field' => 'day_route',
                ],
                [
                    'value' => $summary->route?$summary->route->route_name:"N/A", // Store the hotel ID
                ]
            );
            DB::commit();
            $this->ReloadDayRoute($index);
            $this->reset(['new_route_name']);
            $this->dispatch('refreshComponent');
            $this->active_new_route_modal = 0;

        } catch (\Exception $e) {
            // Rollback transaction if there's an error
            DB::rollBack();
        $this->reset(['new_route_name']);
            $this->errorNewRoute[$index] = $e->getMessage();
        }

    }

    // For New Sightsieeing
  public function OpenNewSightseeingModal($index, $service_summary_id,$division_id){
         // Toggle modal index (open/close)
        $this->active_new_sightseeing_modal = ($this->active_new_sightseeing_modal == $index) ? 0 : $index;
        $this->active_route_new_sightseeing_modal = ($this->active_route_new_sightseeing_modal == $service_summary_id) ? 0 : $service_summary_id;

        // Toggle service summary ID (open/clear)
        $this->service_summary_id = ($this->service_summary_id == $service_summary_id) ? "" : $service_summary_id;

        if ($this->active_new_sightseeing_modal) {
            $this->new_sightseeings = []; // Reset all sightseeings by setting the array to empty

            // If you need to initialize some fields with an empty template, you can add default values like this
            $this->new_sightseeings[] = [
                'name' => '',
                'ticket_price' => '',
                'ticket_price_nri' => '',
            ];
            unset($this->new_sightseeings[0]);
        }
    }
    public function addSightseeing(){
        $this->new_sightseeings[] = ['name' => '', 'ticket_price' => '','ticket_price_nri'=>'', 'description'=>''];
        $modal_id = $this->active_route_new_sightseeing_modal;
        $this->dispatch('load_ck_editor',['item_index' => max(count($this->new_sightseeings) - 1, 0),'item_type'=>'sightseeing','modal_id'=>$modal_id]);
    }
    public function removeSightSeing($index)
    {
        unset($this->new_sightseeings[$index]);
        $modal_id = $this->active_route_new_sightseeing_modal;
        $this->dispatch('load_ck_editor',['item_index' => max(count($this->new_sightseeings) - 1, 0),'item_type'=>'sightseeing','modal_id'=>$modal_id]);
    }
    public function syncDescriptionToLivewire($data){
        $index = $data['index'];
        $value = $data['value'];

        if (isset($this->new_sightseeings[$index])) {
            $this->new_sightseeings[$index]['description'] = $value;
        }
    }
    public function submitNewSightSeeingForm($index,$route_service_summary_id,$division_id)
    {
        $this->validate([
            'new_sightseeings.*.name' => 'required|string|max:255',
            'new_sightseeings.*.ticket_price' => 'nullable|numeric',
            'new_sightseeings.*.ticket_price_nri' => 'nullable|numeric',
        ], [
            'new_sightseeings.*.name.required' => 'This field is required.',
            // 'sightseeings.*.ticket_price.required' => 'This field is required',
        ]);
        try {
            DB::beginTransaction(); // Start transaction

            $RouteServiceSummary = RouteServiceSummary::find($route_service_summary_id);
            if(!$RouteServiceSummary){
                session()->flash('new-sightseeing-error', 'Sorry existing route not fetching, please reload the page!');
                return; // Stop further execution
            }
            if (!$division_id) {
                session()->flash('new-sightseeing-error', 'Please Choose a Division!');
                return; // Stop further execution
            }
            if (count($this->new_sightseeings)==0) {
                session()->flash('new-sightseeing-error', 'Please Choose atleast one sightseeing point!');
                return; // Stop further execution
            }
            // Loop through sightseeings and save them to the database
       
            foreach ($this->new_sightseeings as $new_sightseeings_index => $sightseeing) {
                // Save the activity record
                $sightseeing_data = DivisionWiseSightseeing::create([
                    'name' => $sightseeing['name'], // Ensure this key exists
                    'ticket_price' => !empty($sightseeing['ticket_price']) ? $sightseeing['ticket_price'] : 0, // Save 0 if empty
                    'ticket_price_nri' => !empty($sightseeing['ticket_price_nri']) ? $sightseeing['ticket_price_nri'] : 0, // Save 0 if empty
                    'description' => $sightseeing['description'],
                    'division_id' => $division_id, // Validate this is set
                ]);

                // Assign sightseeing On this route
                ServiceWiseSightseeing::updateOrCreate([
                    'service_summary_id'=>$route_service_summary_id,
                    'sightseeing_id'=>(int)$sightseeing_data->id,
                ]);

                // Assign on this Itinerary
                if($this->leadData->nationality_type=="Indian"){
                    $ticket_price = (float) ($sightseeing['ticket_price'] ?? 0);
                    $value_quantity = $ticket_price>0?$this->leadData->number_of_travellor:1;
                }else{
                    $ticket_price = (float) ($sightseeing['ticket_price_nri'] ?? 0);
                    $value_quantity = $ticket_price>0?$this->leadData->number_of_travellor:1;
                }
                
               
                 ItineraryDetail::updateOrCreate(
                    [
                        'itinerary_id' => $this->itinerary_id,
                        'route_service_summary_id' => $route_service_summary_id,
                        'header' => "day_$index", // Using a dynamic day header
                        'field' => "day_sightseeing",
                        'value' => $sightseeing['name'],
                    ],
                    [
                        'value' => $sightseeing['name'], // Store the activity name or ID
                        'price' => round((float) $ticket_price * $this->leadData->number_of_travellor),
                        'value_quantity' => $value_quantity,
                        'ticket_price' => round((float) $ticket_price * $this->leadData->number_of_travellor),
                    ]
                );
            }
              
            
            DB::commit(); // Commit transaction
            $this->ReloadDayRoute($index);
            $this->new_sightseeings = []; // Reset all sightseeings by setting the array to empty

            // If you need to initialize some fields with an empty template, you can add default values like this
            $this->new_sightseeings[] = [
                'name' => '',
                'ticket_price' => '',
                'ticket_price_nri' => '',
            ];
            unset($this->new_sightseeings[0]);
            $this->active_new_sightseeing_modal = 0;
            $this->active_route_new_sightseeing_modal = 0;

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            session()->flash('new-sightseeing-error', 'Error: ' . $e->getMessage());
            return; // Stop further execution
        }

        // Success message
        session()->flash('success', 'Sightseeing point saved successfully!');
        $this->active_assign_new_modal = 0;
        $this->sightseeings = []; // Reset all sightseeings by setting the array to empty
        // If you need to initialize some fields with an empty template, you can add default values like this
        $this->sightseeings[] = [
            'name' => '',
            'ticket_price' => '',
            'files' => []
        ];
    }

    // New Activity
    public function OpenNewActivityModal($index, $service_summary_id,$division_id){
         // Toggle modal index (open/close)
        $this->active_new_activity_modal = ($this->active_new_activity_modal == $index) ? 0 : $index;
        $this->active_route_new_activity_modal = ($this->active_route_new_activity_modal == $service_summary_id) ? 0 : $service_summary_id;

        // Toggle service summary ID (open/clear)
        $this->service_summary_id = ($this->service_summary_id == $service_summary_id) ? "" : $service_summary_id;

        if ($this->active_new_activity_modal) {
            $this->new_activities = []; // Reset all activities by setting the array to empty

            // If you need to initialize some fields with an empty template, you can add default values like this
            $this->new_activities[] = [
                'name' => '',
                'type' => 'PAID',
                'price' => '',
                'ticket_price' => '',
                'ticket_price_nri' => '',
            ];
            unset($this->new_activities[0]);
        }
    }
    public function addActivity(){
        $this->new_activities[] = ['name' => '', 'type' => 'PAID', 'price' => '', 'ticket_price' => '', 'ticket_price_nri' => '', 'description'=>''];
         $modal_id = $this->active_route_new_activity_modal;
         $this->dispatch('load_ck_editor',['item_index' => max(count($this->new_activities) - 1, 0),'item_type'=>'activity','modal_id'=>$modal_id]);
    }
    public function removeActivity($index)
    {
        unset($this->new_activities[$index]);
        $modal_id = $this->active_route_new_activity_modal;
        $this->dispatch('load_ck_editor',['item_index' => max(count($this->new_activities) - 1, 0), 'item_type'=>'activity','modal_id'=>$modal_id]);
    }
    public function updateType($index, $type){
        // Update the 'type' field for the specific activity
        $this->new_activities[$index]['type'] = $type;
    }

    public function syncActivityDescriptionToLivewire($data){
        $index = $data['index'];
        $value = $data['value'];

        if (isset($this->new_activities[$index])) {
            $this->new_activities[$index]['description'] = $value;
        }
    }
    public function submitNewActivityForm($index,$route_service_summary_id,$division_id){
        $this->validate([
            'new_activities.*.name' => 'required|string|max:255',
            'new_activities.*.type' => 'required|in:PAID,UNPAID',
            'new_activities.*.price' => 'required_if:new_activities.*.type,PAID|nullable|numeric',
            // 'new_activities.*.ticket_price' => 'required_if:new_activities.*.type,PAID|nullable|numeric',
        ], [
            'new_activities.*.name.required' => 'This field is required.',
            'new_activities.*.type.required' => 'This field is required.',
            'new_activities.*.price.required_if' => 'This field is required',
            // 'new_activities.*.ticket_price.required_if' => 'This field is required',
        ]);

        try {
            DB::beginTransaction(); // Start transaction
          
            $RouteServiceSummary = RouteServiceSummary::find($route_service_summary_id);
            if(!$RouteServiceSummary){
                session()->flash('new-activity-error', 'Sorry existing route not fetching, please reload the page!');
                return; // Stop further execution
            }
            if (!$division_id) {
                session()->flash('new-activity-error', 'Please Choose a Division!');
                return; // Stop further execution
            }
            if (count($this->new_activities)==0) {
                session()->flash('new-activity-error', 'Please Choose atleast one activity!');
                return; // Stop further execution
            }
        // Loop through activities and save them to the database
       
            foreach ($this->new_activities as $activity_index => $activity) {
                // Save the activity record
                $price = !empty($activity['price']) ? $activity['price'] : 0;
                $activityRecord = DivisionWiseActivity::create([
                    'name' => $activity['name'], // Ensure this key exists
                    'type' => $activity['type'], // Ensure this key exists and is 'PAID' or 'UNPAID'
                    'description' => $activity['description'],
                    'price' => $price, // Save 0 if empty
                    'ticket_price' => !empty($activity['ticket_price']) ? $activity['ticket_price'] : 0, // Save 0 if empty
                    'ticket_price_nri' => !empty($activity['ticket_price_nri']) ? $activity['ticket_price_nri'] : 0, // Save 0 if empty
                    'division_id' => $division_id, // Validate this is set
                ]);
                // Assign activity On this route
                ServiceWiseActivity::updateOrCreate([
                    'service_summary_id'=>$route_service_summary_id,
                    'activity_id'=>(int)$activityRecord->id,
                ]);

                // Assign on this Itinerary
                if($this->leadData->nationality_type=="Indian"){
                    $ticket_price = (float) ($activity['ticket_price'] ?? 0);
                    $value_quantity = $ticket_price>0?$this->leadData->number_of_travellor:1;
                }else{
                    $ticket_price = (float) ($activity['ticket_price_nri'] ?? 0);
                    $value_quantity = $ticket_price>0?$this->leadData->number_of_travellor:1;
                }
                
                $traveller_count = (int) $this->leadData->number_of_travellor;
                $ticket_total = round((float) $ticket_price * $traveller_count);
                $total_price = round((float) $price + $ticket_total);

                $ItineraryDetailId=ItineraryDetail::updateOrCreate(
                    [
                        'itinerary_id' => $this->itinerary_id,
                        'route_service_summary_id' => $route_service_summary_id,
                        'header' => "day_$index", // Using a dynamic day header
                        'field' => "day_activity",
                        'value' => $activity['name'],
                    ],
                    [
                        'value' => $activity['name'], // Store the activity name or ID
                        'price' => $total_price,
                        'rate' => $price,
                        'value_quantity' => $value_quantity,
                        'ticket_price' => round((float) $ticket_price * $this->leadData->number_of_travellor),
                    ]
                );
            }
            
            DB::commit(); // Commit transaction
            $this->ReloadDayRoute($index);
            $this->new_activities = []; // Reset all activities by setting the array to empty

            // If you need to initialize some fields with an empty template, you can add default values like this
            $this->new_activities[] = [
                'name' => '',
                'type' => 'PAID',
                'price' => '',
                'ticket_price' => '',
                'ticket_price_nri' => '',
            ];
            unset($this->new_activities[0]);
            $this->active_new_activity_modal = 0;
            $this->active_route_new_activity_modal = 0;
            
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            // dd($e->getMessage());
            session()->flash('new-activity-error', 'Error: ' . $e->getMessage());
            return; // Stop further execution
        }

    }

    public function submitForm(){
        // dd($this->all());
    }

    // Update Itinerary Data
    public function UpdateByKeyUp($header, $field, $value){
        ItineraryDetail::updateOrCreate(
            [
                'itinerary_id' => $this->itinerary_id,
                'header' => $header,
                'field' => $field,
            ],
            [
                'value' => $value,
            ]
        );
    }

    public function updateSelectedRoom($hotel_id, $index, $roomId)
    {
        // Store selected room details dynamically
        $this->selected_rooms[$index] = $roomId;
        try {
            // Start database transaction
            DB::beginTransaction();
            $room_data = ItineraryDetail::where('itinerary_id', $this->itinerary_id)->where('hotel_id', $hotel_id)->where('header', 'day_' . $index)->whereNotNull('room_id')->get();
            if (count($room_data)>0) {
                $room_data->each(function ($detail) {
                    $detail->delete();
                });
            }
            $room = Room::find($roomId);
            // Update or create itinerary detail
            ItineraryDetail::updateOrCreate(
                [
                    'itinerary_id' => $this->itinerary_id,
                    'header' => "day_$index", // Assuming you meant to use 'day_{index}'
                    'hotel_id' => $hotel_id,
                    'field' => 'day_room',
                ],
                [
                    'value' => $room->room_name, // Store the hotel ID
                    'room_id' => $roomId,
                ]
            );


            $travelDate = Carbon::parse($this->leadData->travel_date);
            $date = $travelDate->copy()->addDay($index-1);
            $season = CustomHelper::GetSeason($this->leadData->travel_location, $date->format('m-d'));
            $capacity = $this->getCapacity();
            $room_single_price = HotelPriceChart::where('hotel_id', $hotel_id)->where('room_id',$roomId)
                ->where('type', 2)
                ->where('plan_title', $season)
                ->where('plan_item', $this->leadData->meal_type)
                ->where('item_price', '>', 0)
                ->whereHas('room', function ($query) use ($capacity) {
                    $query->where('capacity', '>=', $capacity);
                })
                ->orderBy('item_price', 'ASC')
                ->value('item_price');

            $room_single_price = $room_single_price?$room_single_price:0;

            $day_room_main_plan = ItineraryDetail::updateOrCreate(
                [
                    'itinerary_id' => $this->itinerary_id,
                    'header' => "day_$index", // Assuming you meant to use 'day_{index}'
                    'hotel_id' => $hotel_id,
                    'room_id' => $roomId,
                    'field' => 'day_room_main_plan',
                ],
                [
                    'value' => $this->leadData->meal_type,
                    'value_quantity' => $this->leadData->number_of_adults,
                    'price' => $room_single_price*$this->leadData->number_of_rooms,
                ]
            );

              $this->setNewroomItem($index, $hotel_id,$roomId);
            // Commit transaction
            
            DB::commit();

            $this->ReloadDayHotels($index);
            $this->errorRoom[$index] = '';
        } catch (\Exception $e) {
            // Rollback transaction if there's an error
            DB::rollBack();
            // dd($e->getMessage());
            // Store error message for Livewire validation
            $this->errorRoom[$index] = $e->getMessage();
        }
    }

    public function FetchRoomPlan($index, $roomId, $season){
        // Main Plan
       
        $MainSeasionPlan = SeasionPlan::where('type', 'main')
        ->where('title', $season)
        ->get()
        ->toArray();
       
        if(count($MainSeasionPlan)>0){
            $main_plans = [];
            foreach ($MainSeasionPlan as $item) {
                $plan_types = explode(', ', $item['plan_item']); // Split plan types

                // Fetch all prices at once (avoiding N+1 queries)
                $prices = HotelPriceChart::where('room_id', $roomId)
                ->whereHas('priceChartType', function ($query){
                    $query->where('type',2);
                })
                ->where('plan_title', $item['title'])
                ->whereIn('plan_item', $plan_types) // Get all matching plan_items
                ->pluck('item_price', 'plan_item') // Fetch as key-value pair (plan_item => item_price)
                ->toArray();

                
                // Build the response array
                $main_plans[] = [
                    'title' => $item['title'],
                    'plan_type' => array_map(function ($plan_type) use ($prices) {
                        return [
                        'name' => $plan_type,
                        'price' => $prices[$plan_type] ?? null // Assign price if found, else null
                        ];
                    }, $plan_types),
                ];
            }
        }else{
            session()->flash('error', 'Season period is missing for '.$this->destinationName.' destinations. Please add it.');
            return redirect()->route('admin.destination_wise_season');
        }

        // Addon Plan
        $AddonSeasionPlan = SeasionPlan::where('type', 'addon')->orderBy('position', 'ASC')
        ->get()
        ->toArray();

        if(count($AddonSeasionPlan)>0){
            $addon_plans = [];
            foreach ($AddonSeasionPlan as $item) {
                $plan_types = explode(', ', $item['plan_item']); // Split plan types

                // Fetch all prices at once (avoiding N+1 queries)
                $prices = HotelPriceChart::where('room_id', $roomId)
                ->whereHas('priceChartType', function ($query){
                    $query->where('type',2);
                })
                ->where('plan_title', $item['title'])
                ->whereIn('plan_item', $plan_types) // Get all matching plan_items
                ->pluck('item_price', 'plan_item') // Fetch as key-value pair (plan_item => item_price)
                ->toArray();


                
                $field_data = "day_room_addon_plan_" . str_replace(' ', '_', strtolower($item['title']));

                $selected_addon_plan = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
                ->where('header', 'day_' . $index)
                ->where('room_id', $roomId)
                ->where('field', $field_data)
                ->get()->toArray();

                // Build the response array
                $addon_plans[] = [
                'selected_addon_plan' => $selected_addon_plan,
                'title' => $item['title'],
                    'plan_type' => array_map(function ($plan_type) use ($prices) {
                        return [
                        'name' => $plan_type,
                        'price' => $prices[$plan_type] ?? null // Assign price if found, else null
                        ];
                    }, $plan_types),
                ];
            }
        }

        

        $this->hotel_room_price [$index]= [
            'room_details' =>Room::find($roomId),
            'main_seasion_plan' =>$main_plans,
            'addon_seasion_plan' =>$addon_plans,
        ];
    }

    public function updateSelectedRoomPlan($hotel_id, $roomId, $index, $plan_name, $price)
    {
        // Store selected room details dynamically
        $this->selected_room_plan[$index] = $plan_name;
        try {
            // Start database transaction
            DB::beginTransaction();
            // Update or create itinerary detail
            $itineraryDetail = ItineraryDetail::updateOrCreate(
                [
                    'itinerary_id' => $this->itinerary_id,
                    'header' => "day_$index", // Assuming you meant to use 'day_{index}'
                    'hotel_id' => $hotel_id,
                    'room_id' => $roomId,
                    'field' => 'day_room_main_plan',
                ],
                [
                    'value' => $plan_name, // Store the hotel ID
                    'value_quantity' => (int)$this->leadData->number_of_adults,
                    'price' => (int)$price*$this->leadData->number_of_rooms,
                ]
            );

            DB::commit();
            
            $this->ReloadDayHotels($index);
            $this->errorRoom[$index] = '';
        } catch (\Exception $e) {
            // Rollback transaction if there's an error
            DB::rollBack();
            // Store error message for Livewire validation
            $this->errorRoom[$index] = $e->getMessage();
        }
    }

    public function decreaseQuantity($index,$id){
        $ItineraryDetail = ItineraryDetail::find($id);

        if (!$ItineraryDetail) {
            return; // Exit if the item is not found
        }

        // Ensure value_quantity is at least 1 to avoid division by zero
        $value_quantity = ($ItineraryDetail->value_quantity == "" || $ItineraryDetail->value_quantity == 0) ? 1 : $ItineraryDetail->value_quantity;

        $updated_quantity = $value_quantity - 1;

        if ($updated_quantity >= 1) {
            if($ItineraryDetail->field=="day_activity" || $ItineraryDetail->field=="day_sightseeing"){
                // Calculate piece price
                $piece_price = $ItineraryDetail->ticket_price / $value_quantity;
                
                // Calculate new price
                $ItineraryDetail->price = $ItineraryDetail->rate + ($piece_price * $updated_quantity);
                // Update rate and ticket price based on the updated quantity
                $ItineraryDetail->ticket_price = $piece_price * $updated_quantity;
            }else{
                $piece_price = $ItineraryDetail->price / $value_quantity; // Ensure no division by zero
                $ItineraryDetail->price = $piece_price * $updated_quantity;
            }
            $ItineraryDetail->value_quantity = $updated_quantity;
            $ItineraryDetail->save();
        }

        // Reload related data
        $this->ReloadDayRoute($index);
        $this->ReloadDayCabs($index);

        if (isset($ItineraryDetail->room_id)) {
            $this->ReloadDayHotels($index);
        }
    }
    public function increaseQuantity($index, $id)
    {
        $ItineraryDetail = ItineraryDetail::find($id);

        if (!$ItineraryDetail) {
            return; // Exit if the item is not found
        }

        // Ensure value_quantity is at least 1 to avoid division by zero
        $value_quantity = ($ItineraryDetail->value_quantity == "" || $ItineraryDetail->value_quantity == 0) ? 1 : $ItineraryDetail->value_quantity;

        $updated_quantity = $value_quantity + 1;

        if ($updated_quantity >= 1) {
           
            $ItineraryDetail->value_quantity = $updated_quantity;

            if($ItineraryDetail->field=="day_activity" || $ItineraryDetail->field=="day_sightseeing"){

                // Calculate piece price
                $piece_price = $ItineraryDetail->ticket_price / $value_quantity;
               
                // Calculate new price
                $ItineraryDetail->price = $ItineraryDetail->rate + ($piece_price * $updated_quantity);

                // Update rate and ticket price based on the updated quantity
                $ItineraryDetail->ticket_price = $piece_price * $updated_quantity;
            }else{
                $piece_price = $ItineraryDetail->price / $value_quantity; // Ensure no division by zero
                $ItineraryDetail->price = $piece_price * $updated_quantity;
            }
            $ItineraryDetail->save();
        }

        // Reload related data
        $this->ReloadDayRoute($index);
        $this->ReloadDayCabs($index);

        if (isset($ItineraryDetail->room_id)) {
            $this->ReloadDayHotels($index);
        }
    }

    public function updateQuantity($index, $id){
        $ItineraryDetail = ItineraryDetail::find($id);
        if (!$ItineraryDetail) {
            return; // Exit if the item is not found
        }

        // Ensure the quantity is set, otherwise use default (1)
        $updated_quantity = $this->itemQuantity[$index][$id] ?? 0;

        // Calculate new price based on updated quantity
        if($ItineraryDetail->field=="day_activity" || $ItineraryDetail->field=="day_sightseeing"){
            $piece_price = $ItineraryDetail->ticket_price / max($ItineraryDetail->value_quantity, 1);
            if ($updated_quantity === "" || $updated_quantity == 0) {
                    $updated_quantity = 0;
                    $ItineraryDetail->price = $ItineraryDetail->rate+($piece_price*1);
                     $ItineraryDetail->ticket_price = $piece_price*1;
                }else{
                    // Calculate new price
                    $ItineraryDetail->price = $ItineraryDetail->rate + ($piece_price * $updated_quantity);
                    // Update rate and ticket price based on the updated quantity
                    $ItineraryDetail->ticket_price = $piece_price * $updated_quantity;
                }
        }else{
            $piece_price = $ItineraryDetail->price / max($ItineraryDetail->value_quantity, 1);
            if ($updated_quantity === "" || $updated_quantity == 0) {
                $updated_quantity = 0;
                $ItineraryDetail->price = $piece_price*1;
            }else{
                $ItineraryDetail->price = $piece_price * $updated_quantity;
            }
        }

        $ItineraryDetail->value_quantity = $updated_quantity;
        $ItineraryDetail->save();

   
        $this->ReloadDayRoute($index);
        $this->ReloadDayCabs($index);
       
        if (isset($ItineraryDetail->room_id)) {
            $this->ReloadDayHotels($index);
        }
    }

    public function ResetItinerary(){
        // $ItineraryDetail = ItineraryDetail::where('itinerary_id', $this->itinerary_id)->get();
        // if (count($ItineraryDetail)>0) {
        //     $ItineraryDetail->each(function ($detail) {
        //         $detail->delete();
        //     });
        // }
        $itinerary_id = Crypt::encrypt($this->itinerary_id);
        return redirect()->route('admin.itinerary.query.build', $itinerary_id);
    }

    public function GetAllQuantity(){
        $stay_by_journey = explode(',',$this->stay_by_journey);
        if(count($stay_by_journey)>0){
            foreach($stay_by_journey as $k=>$journey){
              
                $this->day_wise_amount_data[$k+1] = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
                ->where('header', 'day_' . ($k + 1))
                ->whereNotNull('price')
                ->select(
                    'field',
                    \DB::raw('SUM(price) as total_price'),
                    \DB::raw('SUM(value_quantity) as total_quantity'),
                    \DB::raw('MIN(id) as min_id')
                )
                ->groupBy('field')
                ->orderBy('min_id', 'asc')
                ->get();
                // Get All quantity data
                $all_quantity_data =ItineraryDetail::where('itinerary_id', $this->itinerary_id)->whereNotNull('value_quantity')->get();
                foreach($all_quantity_data as $q_key=>$q_item){
                    $this->itemQuantity[$k+1][$q_item->id] = $q_item->value_quantity; // Set default value here
                    $value_quantity = $q_item->value_quantity==0?"":$q_item->value_quantity;
                    $this->dispatch('resetInputQuantity', ['id' => $q_item->id, 'quantity'=>$value_quantity]);
                }
            }
        }

    }
    public function existingHotelDetails($index){
        return ItineraryDetail::where('itinerary_id', $this->itinerary_id)->whereNotNull('hotel_id')->where('header', 'day_' . $index)->whereIn('field', ['day_room_main_plan', 'day_room_addon_plan_cwm'])->get()->toArray();
    }

    public function ChangeSimilarHotel($index, $division, $hotel_id, $room_id, $room_name, $meal_type, $season){
        // dd($hotel_id, $room_id, $room_name, $meal_type, $season);
        foreach ($this->day_by_divisions[$index]['day_hotel'] as $k => $day_hotel) {
            $ExtraIds[]  = isset($day_hotel['id']) ? $day_hotel['id'] : $day_hotel['hotel_id'];
        }
        // $ExtraIds = collect($this->dayExtraHotels[$index])->pluck('id')->toArray();
        
        $ExtraIds = array_unique($ExtraIds);
        $room_capacity = Room::find($room_id)?->capacity;
        $this->selected_division = City::find($division)->name ?? null;
        $hotels = Hotel::with([
                'rooms' => function ($query) use ($room_name, $room_capacity) {
                    $query->where('room_name', $room_name)
                        ->where('capacity', '>=', $room_capacity);
                },
                'priority_rooms',
                'price_chart' => function ($query) use ($season, $meal_type) {
                    $query->where('plan_title', ucwords($season))
                        ->where('plan_item', $meal_type)
                        ->where('item_price', '>', 0);
                }
            ])
            ->where('division', $division)
            ->where('hotel_category', $this->day_hotel_category[$index])
            ->whereHas('rooms', function ($query) use ($room_name, $room_capacity) {
                $query->where('room_name', $room_name)
                    ->where('capacity', '>=', $room_capacity);
            })
            ->whereHas('price_chart', function ($query) use ($season, $meal_type) {
                $query->where('plan_title', ucwords($season))
                    ->where('plan_item', $meal_type)
                    ->where('item_price', '>', 0);
            })
            ->whereNotIn('id', $ExtraIds)
            ->get();
        // Sort hotels by item_price (related price_chart) based on budget direction
        $getRelatedHotels = $hotels->sortBy(function ($hotel) {
                return $hotel->price_chart->first()->item_price ?? PHP_INT_MAX;
            }, SORT_REGULAR, $this->package_budget === 'max')
            ->values() // re-index the collection
            ->toArray();
        // dd($getRelatedHotels);
        $existing_hotel_details = $this->existingHotelDetails($index);

        $current_hotel = Hotel::find($hotel_id);

        if ($current_hotel) {
            $existing_hotel = $current_hotel->toArray();
            $existing_hotel['image'] = $current_hotel->image?$current_hotel->image:'build/assets/images/logo/hotel.jpg';
            $existing_hotel['selected_room_id'] = $room_id;
            $existing_hotel['selected_room_name'] = $room_name;
        } else {
            $existing_hotel = null; // or handle not found case
        }
        $this->related_hotels[0]=$existing_hotel;
        
        $sl = 1;
        foreach($getRelatedHotels as $key=>$item){
            $existing_amount = 0;
            $new_amount = 0;
            $room = Room::where('hotel_id', $item['id'])->where('room_name', $room_name)->first();

            foreach($existing_hotel_details as $hotel_index=> $hotel_details){
                
                // if($hotel_index==1){
                    $lastPart = substr(strrchr($hotel_details['field'], "_"), 1);

                    $existing_amount+=(int)$hotel_details['price'];
                        $query = HotelPriceChart::where('hotel_id', $item['id'])
                            ->where('type', 2)
                            ->where('room_id', $room->id)
                            ->where('plan_item', $hotel_details['value'])
                            ->where('item_price', '>', 0);

                        // Conditional clause
                        if ($hotel_details['field'] === "day_room_main_plan") {
                            $query->where('plan_title', $season);
                        } else {
                            $query->where('plan_title', $lastPart); // same condition here, you can simplify this
                        }

                        $room_single_price = $query->value('item_price');
                        if(!$room_single_price){
                            ItineraryDetail::where('id', $hotel_details['id'])->delete();
                        }else{
                            $value_quantity = $hotel_details['field']=="day_room_main_plan"?$this->leadData->number_of_rooms:$hotel_details['value_quantity'];
                            $new_amount+=(int)$room_single_price*$value_quantity;
                        }
                // }
                
            }
            $item['image'] = $item['image']?$item['image']:'build/assets/images/logo/hotel.jpg';
            $item['existing_amount'] = $existing_amount;
            $item['new_amount'] = $new_amount;
            $item['selected_room_id'] = $room->id;
            $item['selected_room_name'] = $room_name;
            $item['selected_season'] = $season;
            $item['selected_day'] = $index;
            $item['selected_hotel_id'] = $hotel_id;
            // dd($new_amount, $existing_amount);
            $difference = $new_amount - $existing_amount;
            if($difference >= 0){
                $item['sign'] = '+';
            }else{
                $item['sign'] = '-';
            }
            $formatted_difference = abs((int) $difference);
            $item['difference'] = $formatted_difference;
            $this->related_hotels[$sl]=$item;
            $sl++;
        }
        $this->isRelatedModalOpen = true;
    }
    public function closeRelatedModal(){
        $this->isRelatedModalOpen = false;
        $this->reset(['related_hotels']);
    }

    public function updateDayHotel($index, $old_hotel_id,$new_hotel_id,$room_name,$season){

        DB::beginTransaction();
        try {
            $existing_hotel_details = $this->existingHotelDetails($index);
            // $new_hotel = Hotel::find($new_hotel_id);
            $new_hotel = Hotel::with([
                                    'priority_rooms',
                                    'rooms',
                                    'price_chart'
                                ])
                                ->where('id', $new_hotel_id)
                                ->first();

            $new_hotel_result = $new_hotel ? $new_hotel->toArray() : [];
            foreach ($this->day_by_divisions[$index]['day_hotel'] as $k => $day_hotel) {
                $hotelId = isset($day_hotel['id']) ? $day_hotel['id'] : $day_hotel['hotel_id'];
                 if($hotelId === $old_hotel_id){
                    $this->day_by_divisions[$index]['day_hotel'][$k] = $new_hotel_result;
                }
            }

            if($new_hotel){
                $room = Room::where('hotel_id', $new_hotel_id)->where('room_name', $room_name)->first();

                ItineraryDetail::updateOrCreate(
                    [
                        'itinerary_id' => $this->itinerary_id,
                        'header' => "day_$index", // Assuming you meant to use 'day_{index}'
                        'field' => 'day_hotel',
                    ],
                    [
                        'value' => $new_hotel->name, // Store the hotel ID
                        'hotel_id' => $new_hotel->id,
                    ]
                );

                // Update or Create Room
                ItineraryDetail::updateOrCreate(
                    [
                        'itinerary_id' => $this->itinerary_id,
                        'header' => "day_$index", // Assuming you meant to use 'day_{index}'
                        'hotel_id' => $new_hotel->id,
                        'field' => 'day_room',
                    ],
                    [
                        'value' => $room->room_name, // Store the hotel ID
                        'room_id' => $room->id,
                    ]
                );
    
                foreach($existing_hotel_details as $hotel_details){
                    $price = 0;
                    if($hotel_details['field']==="day_room_main_plan"){

                       $room_single_price = HotelPriceChart::where('hotel_id', $new_hotel_id)
                            ->where('type', 2) // 2 = Selling Price
                            ->where('room_id', $room->id)
                            ->where('plan_title', $season)
                            ->where('plan_item', $hotel_details['value'])
                            ->where('item_price', '>', 0)
                            ->value('item_price');
                            $price = (int)$room_single_price*$this->leadData->number_of_rooms;
    
                    }elseif($hotel_details['field']==="day_room_addon_plan_cwm"){
                        $room_single_price = HotelPriceChart::where('hotel_id', $new_hotel_id)
                            ->where('type', 2) // 2 = Selling Price
                            ->where('room_id', $room->id)
                            ->where('plan_title', "CWM")
                            ->where('plan_item', $hotel_details['value'])
                            ->where('item_price', '>', 0)
                            ->value('item_price');
    
                            $price = (int)$room_single_price*$hotel_details['value_quantity'];
                    }elseif($hotel_details['field']==="day_room_addon_plan_mattress"){
                        $room_single_price = HotelPriceChart::where('hotel_id', $new_hotel_id)
                            ->where('type', 2) // 2 = Selling Price
                            ->where('room_id', $room->id)
                            ->where('plan_title', "Mattress")
                            ->where('plan_item', $hotel_details['value'])
                            ->where('item_price', '>', 0)
                            ->value('item_price');
    
                            $price = (int)$room_single_price*$hotel_details['value_quantity'];
                    }

                        $newEntry = new ItineraryDetail;
                        $newEntry->itinerary_id = $this->itinerary_id;
                        $newEntry->route_service_summary_id = $hotel_details['route_service_summary_id'];
                        $newEntry->hotel_id = $new_hotel_id;
                        $newEntry->room_id = $room->id;
                        $newEntry->header = $hotel_details['header'];
                        $newEntry->field = $hotel_details['field'];
                        $newEntry->value = $hotel_details['value'];
                        $newEntry->value_quantity = $hotel_details['field']=="day_room_main_plan"?$this->leadData->number_of_adults:$hotel_details['value_quantity'];
                        $newEntry->price = $price;
                        $newEntry->save();
    
                      
                }
    
                // delete the old record after inserting the new one
                ItineraryDetail::where('itinerary_id', $this->itinerary_id)->where('hotel_id', $old_hotel_id)->where('header', 'day_' . $index)->delete();
                DB::commit();
                
                $this->closeRelatedModal();
                session()->flash('success', 'Hotel has beed updated successfully!');
                $this->ReloadDayHotels($index);
            }else{
                session()->flash('related_hotel_error', 'Sorry! hotel not found');
            }
            
            

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('related_hotel_error', $e->getMessage());
        }
       
    }

    public function ChangeHotel($index,$hotel_id, $division,$tab){

        $get_existing_hotel = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
        ->whereNotNull('hotel_id')
        ->where('header', 'day_' . $index)
        ->value('hotel_id');
        if ($get_existing_hotel) {
            ItineraryDetail::where('itinerary_id', $this->itinerary_id)
                ->where('hotel_id', $get_existing_hotel)
                ->where('header', 'day_' . $index)
                ->delete();

            $travelDate = Carbon::parse($this->leadData->travel_date);
            $date = $travelDate->copy()->addDay($index-1);
            $season = CustomHelper::GetSeason($this->leadData->travel_location, $date->format('m-d'));

            // Fetch Season
            if (!$season) {
                session()->flash('error', 'No season period has been defined for the selected destination. Please add the season details first.');
                return false;
            }

            $this->activeTab[$index] = $tab;
            $this->generateHotelData($index,$hotel_id,$season);

            $this->ReloadDayHotels($index);

            session()->flash('success', 'Hotel has beed updated successfully!');
        }
    }

    public function getInclusionExclusion(){
        $template= ItineraryTemplate::where('destination_id', $this->destinationId)->first();

        if($template){
            $this->inclusions = ItineraryTemplateDetail::where('itinerary_template_id', $template->id)->where('header', 'inclusion_exclusions')->where('field', 'like', 'inclusions_%')->pluck('value')->toArray();

            $not_exist_inclusion = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
            ->where('header', 'inclusion_exclusions')
            ->where('field', 'like', 'inclusions_%')
            ->whereNotIn('value', $this->inclusions)->delete();

            $this->exclusions = ItineraryTemplateDetail::where('itinerary_template_id', $template->id)->where('header', 'inclusion_exclusions')->where('field', 'like', 'exclusions_%')->pluck('value')->toArray();

            $not_exist_exclusion = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
            ->where('header', 'inclusion_exclusions')
            ->where('field', 'like', 'exclusions_%')
            ->whereNotIn('value', $this->exclusions)->delete();
        }
    }
    public function loadSelectedInclusions()
    {
        $this->selectedInclusions = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
            ->where('header', 'inclusion_exclusions')
            ->where('field', 'like', 'inclusions_%')
            ->pluck('value')
            ->toArray();

    }
    public function loadSelectedExclusions()
    {
        $this->selectedExclusions = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
            ->where('header', 'inclusion_exclusions')
            ->where('field', 'like', 'exclusions_%')
            ->pluck('value')
            ->toArray();
    }
    public function toggleInclusion($value,$index){
        $exists = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
            ->where('header', 'inclusion_exclusions')
            ->where('field', 'like', 'inclusions_%')
            ->where('value', $value)
            ->exists();
        if ($exists) {
            // Remove from DB
            ItineraryDetail::where('itinerary_id', $this->itinerary_id)
            ->where('header', 'inclusion_exclusions')
            ->where('field', 'like', 'inclusions_%')
            ->where('value', $value)
            ->delete();

            // Remove from local array
            $this->selectedInclusions = array_filter($this->selectedInclusions, fn($v) => $v !== $value);
        } else {
            // Insert into DB
            ItineraryDetail::create([
                'itinerary_id' => $this->itinerary_id,
                'header' => 'inclusion_exclusions',
                'field' => 'inclusions_'.$index,
                'value' => $value,
            ]);

            $this->loadSelectedInclusions();
        }
    }
    public function toggleExclusion($value,$index){
        $exists = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
            ->where('header', 'inclusion_exclusions')
            ->where('field', 'like', 'exclusions_%')
            ->where('value', $value)
            ->exists();
        if ($exists) {
            // Remove from DB
            ItineraryDetail::where('itinerary_id', $this->itinerary_id)
            ->where('header', 'inclusion_exclusions')
            ->where('field', 'like', 'exclusions_%')
            ->where('value', $value)
            ->delete();

            // Remove from local array
            $this->selectedExclusions = array_filter($this->selectedExclusions, fn($v) => $v !== $value);
        } else {
            // Insert into DB
            ItineraryDetail::create([
                'itinerary_id' => $this->itinerary_id,
                'header' => 'inclusion_exclusions',
                'field' => 'exclusions_'.$index,
                'value' => $value,
            ]);

            $this->loadSelectedExclusions();
        }
    }

     public function getTriphighlight(){
        $template= ItineraryTemplate::where('destination_id', $this->destinationId)->first();

        if($template){
            $this->triphighlight = ItineraryTemplateDetail::where('itinerary_template_id', $template->id)->where('header', 'about_destination')->where('field', 'like', 'trip_highlights_%')->pluck('value')->toArray();

           if (count($this->triphighlight) > 0) {
                ItineraryDetail::where('itinerary_id', $this->itinerary_id)
                    ->where('header', 'about_destination')
                    ->where('field', 'like', 'trip_highlights_%')
                    ->whereNotIn('value', $this->triphighlight)
                    ->delete();
            }
            
        }
    }
    public function loadSelectedtripHighlight()
    {
        $this->selectedTriphighlight = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
            ->where('header', 'about_destination')
            ->where('field', 'like', 'trip_highlights_%')
            ->pluck('value')
            ->toArray();
    }
    public function toggleTriphighlight($value,$index){
        $exists = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
            ->where('header', 'about_destination')
            ->where('field', 'like', 'trip_highlights_%')
            ->where('value', $value)
            ->exists();
        if ($exists) {
            // Remove from DB
            ItineraryDetail::where('itinerary_id', $this->itinerary_id)
            ->where('header', 'about_destination')
            ->where('field', 'like', 'trip_highlights_%')
            ->where('value', $value)
            ->delete();
            // Remove from local array
            $this->selectedTriphighlight = array_filter($this->selectedTriphighlight, fn($v) => $v !== $value);
        } else {
            // Insert into DB
            ItineraryDetail::create([
                'itinerary_id' => $this->itinerary_id,
                'header' => 'about_destination',
                'field' => 'trip_highlights_'.$index,
                'value' => $value,
            ]);

            $this->loadSelectedtripHighlight();
        }
    }

   public function changePackageBudget()
    {
        $this->loadDayHotelsTime = 2;
        $this->package_budget = ($this->package_budget === 'max') ? 'min' : 'max';
        $this->budgetSortOrder = $this->package_budget === 'min' ? 'ASC' : 'DESC';
        $this->filterHotelData();
    }

    public function messageChannelChanged()
    {

    }
    public function sendMessages()
    {
        
        $methods = [];

        if ($this->send_whatsapp) {
            $methods[] = 'WhatsApp';
        }
        if ($this->send_email) {
            $methods[] = 'Email';
        }

        if ($this->send_sms) {
            $methods[] = 'SMS';
        }

        $sent_via = implode(',', $methods);
        // Get initials from customer name (e.g. 'RAK')
        $initials = collect(explode(' ', $this->leadData->customer_name))
            ->filter()
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->implode('');

       // Step 2: Initialize counter and loop
        $sent_count = SendedLeadItinerary::where('lead_id', $this->leadData->id)->count();
        $counter = $sent_count + 1;

        do {
            $code = $initials . str_pad($counter, 3, '0', STR_PAD_LEFT);
            $exists = SendedLeadItinerary::
                where('itinerary_code', $code)
                ->exists();
            $counter++;
        } while ($exists);

        // Final unique $code
                
        DB::beginTransaction();
        try {
            $store = new SendedLeadItinerary;
            $store->lead_id = $this->leadData->id;
            $store->itinerary_code = $code;
            $store->destination_id = $this->itineraryData->destination_id;
            $store->hotel_category = $this->itineraryData->hotel_category;
            $store->itinerary_syntax =$this->itineraryData->itinerary_syntax;
            $store->total_days = $this->itineraryData->total_days;
            $store->total_nights = $this->itineraryData->total_nights;
            $store->stay_by_journey = $this->itineraryData->stay_by_journey;
            $store->itinerary_journey = $this->itineraryData->itinerary_journey;
            $store->total_cost = $this->total_amount;
            $store->send_via = $sent_via;
            $store->sent_by_admin_id = Auth::guard('admin')->user()->id;
            $store->sent_at = now()->toDateTimeString();
            $store->save();

            // Step 2: Fetch itinerary details
            $fetch_itineraries = ItineraryDetail::where('itinerary_id', $this->itinerary_id)->get();

            // Step 3: Prepare bulk insert data
            $bulkInsertData = [];
            foreach ($fetch_itineraries as $item) {
                $bulkInsertData[] = [
                    'sended_lead_itinerary_id' => $store->id,
                    'route_service_summary_id' => $item->route_service_summary_id,
                    'hotel_id' => $item->hotel_id,
                    'room_id' => $item->room_id,
                    'cab_id' => $item->cab_id,
                    'header' => $item->header,
                    'field' => $item->field,
                    'value' => $item->value,
                    'value_quantity' => $item->value_quantity,
                    'price' => $item->price,
                    'rate' => $item->rate,
                    'ticket_price' => $item->ticket_price,
                    'created_at'=> now()->toDateTimeString(),
                    'updated_at'=> now()->toDateTimeString()
                ];
            }

            // Step 4: Bulk insert
            if (!empty($bulkInsertData)) {
                SendedLeadItineraryDetail::insert($bulkInsertData);
            }
        

            // Shared Log link
            $leadUrlShare = new LeadUrlShare();
            $leadUrlShare->lead_id = $store->lead_id;
            $leadUrlShare->sended_lead_itinerary_id = $store->id;
            $leadUrlShare->shared_by = Auth::guard('admin')->id();
            $leadUrlShare->channel = $sent_via;
            $leadUrlShare->links = asset('customized/itinerary/'.$store->itinerary_code);
            $leadUrlShare->save();

            // Log the assignment
            LeadActivityLog::create([
                'lead_id' => $store->lead_id,
                'updated_by' => Auth::guard('admin')->id(), // cleaner and safer
                'message' => json_encode([
                    'action' => 'Customize Itinerary Sent',
                    'itinerary_code' => $store->itinerary_code,
                    'destination' => $store->destination?->name,
                    'hotel_category' => $store->category?->name, // changed key for consistency
                    'total_days' => $store->total_days,
                    'total_nights' => $store->total_nights,
                    'total_cost' => $store->total_cost,
                    'sent_via' => $sent_via, // hardcoded is fine if applicable
                    'link' => $leadUrlShare->links, // hardcoded is fine if applicable
                    'sent_by' => Auth::guard('admin')->user()->name . ' (' . Auth::guard('admin')->user()->email . ')',
                    'timestamp' => now()->toDateTimeString(),
                ]),
            ]);

        DB::commit();

        // Mailing section
        if ($this->send_email) {
          MailTemplateService::send(
                $this->leadData->customer_email,//mail to
                'customer_customized_itinerary_link', //mail template slug
                [
                    'customer_name' => $this->leadData->customer_name,
                    'itinerary_link' => $leadUrlShare->links,
                    'estimated_amount' => env('DEFAULT_CURRENCY_SYMBOL').number_format($this->total_amount,2),
                    'admin_name' => Auth::guard('admin')->user()->name,
                ], // mail body data
                ['customer_name' => $this->leadData->customer_name,], //mail subject data
                ENV('MAIL_FROM_ADDRESS'),     // From Email
                ENV('MAIL_FROM_NAME')         // From Name
            );
        }

        // Whatspp Section
        if($this->send_whatsapp){
            $whatsapp_response = $this->whatsapp->sendTemplate(
                phone: "8617207525",
                templateName: "template-name",
                languageCode: env('WHATSAPP_DEFAULT_TEMPLATE_LANGUAGE'),
                headerParams: [],
                bodyParams: ["John"] // Fills {{1}}
            );
            dd($whatsapp_response);
        }
        

            session()->flash('success', 'Itinerary sent and details saved successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
                dd('Transaction failed: ' . $e->getMessage());
            session()->flash('error', 'Failed to send itinerary. Please try again.');
        
        }
    }
    public function render()
    {   
        // dd($this->dayExtraHotels);
        $this->GetAllQuantity();
        $this->total_amount = ItineraryDetail::where('itinerary_id', $this->itinerary_id)->sum('price');
        $this->selected_rooms_count = ItineraryDetail::where('itinerary_id', $this->itinerary_id)->whereNotNull('room_id')->where('field', 'day_room_main_plan')->sum('value_quantity');
        // Per day wise amount
        $this->mainBanner = ItineraryBanner::where('destination_id', $this->destinationId)->get();
        $sent_itineraries = SendedLeadItinerary::where('lead_id', $this->leadData->id)->orderBy('id','DESC')->get();
        return view('livewire.itinerary.create-query-itinerary',compact('sent_itineraries'));
    }
   
}
