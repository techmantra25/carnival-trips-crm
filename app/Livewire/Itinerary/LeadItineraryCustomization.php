<?php

namespace App\Livewire\Itinerary;

use Livewire\Component;
use App\Models\Lead;
use App\Models\Itinerary;
use App\Models\Category;
use App\Models\City;
use App\Models\Hotel;
use App\Models\SeasionPlan;
use App\Models\HotelPriceChartType;
use App\Helpers\CustomHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class LeadItineraryCustomization extends Component
{
    public $lead_id;
    public $duration;
    public $leadData;
    public $itinerary_syntax;
    public $categories =[];
    public $selectedCategory =null;
    public $newPresetError = '';
    public $existing_night_halt_details = [];
    public $total_amount = 0;
    public $selected_package,$selected_night_halt;
    public $selected_package_type = 'general';
    public $itinerary_journey = [];
    public $itinerary_journey_divisions = [];
    public $active_new_night_halt_modal = 0;
    public $active_night_distribution = 0;
    public $divisions = [];
    public $day_by_divisions = [];
    public $package = [];

    public $nightHalt = [
        'day' => "",
        'night' => "",
        'night_sum' => 0,
        'night_distribution' => "",
    ];

    public function mount(){
        $this->lead_id = request()->get('lead_id');
        $this->duration = request()->get('duration');
        $this->leadData = Lead::where('id', $this->lead_id)->where('travel_duration_text',$this->duration)->first();
        
        if(!$this->leadData){
            abort(403);
        }
        $this->itinerary_syntax = $this->leadData->travel_duration_text;

        $this->categories = Category::where('status', 1)->orderBy('name', 'ASC')->get();
        $this->fetchNightHaltDetails();
        $this->nightHalt['day'] = $this->leadData->travel_in_days;
        $this->nightHalt['night'] = $this->leadData->travel_in_nights;
        $this->active_night_distribution = 1;
        $this->loadAllDayByDivision();
    }

    public function fetchNightHaltDetails(){
        // Fetch itinerary package
        $this->existing_night_halt_details = Itinerary::select('id', 'itinerary_journey')->where('itinerary_syntax', $this->duration)->where('destination_id', $this->leadData->travel_location)->orderBy('updated_at', 'DESC')->get()->toArray();
        $this->selected_night_halt = isset($this->existing_night_halt_details[0]) ?$this->existing_night_halt_details[0]['id']: null;
    }

    public function GetCategory($value){
        $this->selectedCategory = $value;
        $this->active_new_night_halt_modal = 1;
    }
    public function NewNightHalt($value){

        $this->divisions= City::where('state_id', $this->leadData->travel_location)->orderBy('name', 'ASC')->get();

        $this->nightHalt['night_sum'] = 0;
        $this->nightHalt['night_distribution'] = "";
        $this->active_new_night_halt_modal = $value=="yes"?1:0;
        $this->dispatch('refreshComponent');
    }

    public function validateDaysAndNights($value){

        if (!preg_match('/^\d+(\s\d+)*$/', $value)) {
            $this->newPresetError = 'Please enter a valid numeric value.';
            $this->reset(['itinerary_journey', 'itinerary_journey_divisions']);
            return;
        }
        elseif(!$this->selectedCategory){
            $this->newPresetError = 'Please select category first.';
            return;
        }elseif ($this->nightHalt['day'] < $this->nightHalt['night']) {
            $this->newPresetError = 'Days (D) must be greater than Nights (N).';
            $this->active_night_distribution = 0;
            $this->nightHalt['night_distribution'] = "";
            return;
        } else {
            $this->newPresetError = "";
            $this->active_night_distribution = 1;
        }
    }
    public function updateJourneyDivision($index, $value){
        $this->itinerary_journey_divisions[$index] = $value;
    }
    public function validateNightDistribution(){
        if(!$this->selectedCategory){
            $this->newPresetError = 'Please select category first.';
            return;
        }
        $cleanedValue = str_replace('+', ' ', $this->nightHalt['night_distribution']);
         // Check if the cleaned value contains only numbers and spaces
        if (!preg_match('/^\d+(\s\d+)*$/', $cleanedValue)) {
            $this->newPresetError = 'Please enter a valid numeric value.';
            $this->reset(['itinerary_journey', 'itinerary_journey_divisions']);
            return;
        }
        $numbers = array_map('intval', explode(' ', $cleanedValue));
        $this->nightHalt['night_sum'] = array_sum($numbers);
        // Validate the total sum matches the expected night count
        if ($this->nightHalt['night'] != $this->nightHalt['night_sum']) {
            $this->newPresetError = 'Value should be equal to ' . $this->nightHalt['night'];
            $this->reset(['itinerary_journey', 'itinerary_journey_divisions']);
            return;
        }

        // If everything is correct, clear the error
        $this->itinerary_journey = $numbers;
        $this->newPresetError = null;
    }

    public function nightHaltSubmitForm()
    {
        if(!$this->selectedCategory){
            $this->newPresetError = 'Please select category first.';
            return;
        }elseif ($this->nightHalt['day'] < $this->nightHalt['night'] || $this->nightHalt['day']==0) {
            $this->newPresetError = 'Days (D) must be greater than Nights (N).';
            $this->active_night_distribution = 0;
            $this->nightHalt['night_distribution'] = "";
            return;
        }elseif (count($this->itinerary_journey)==0 || count($this->itinerary_journey_divisions)==0) {
            $this->newPresetError = 'Please fill the itinerary journey.';
            return;
        }
        DB::beginTransaction();
        try {
           // Convert arrays to string format
            $itinerary_journey = implode(',', $this->itinerary_journey);
           
            $itinerary_journey_divisions = implode(',', $this->itinerary_journey_divisions);
            $stay_by_journey = [];
            foreach($this->itinerary_journey as $index=>$item){
                for ($i = 0; $i < $item; $i++) { 
                    $stay_by_journey[] = $this->itinerary_journey_divisions[$index] ?? null;
                }
            }
            $stay_by_journey = array_filter($stay_by_journey); // Ensure no null values
            if (!empty($stay_by_journey)) {
                $stay_by_journey[] = end($stay_by_journey); // Duplicate last value
            }
            $stay_by_journey = implode(',', $stay_by_journey);

            $formattedString = CustomHelper::formatNightJourney($itinerary_journey, $itinerary_journey_divisions);

            // Find existing record or create a new one
            $itinerary_syntax = $this->nightHalt['day'].'D/'.$this->nightHalt['night'].'N';
            $create = Itinerary::updateOrCreate(
                [
                    'destination_id' => $this->leadData->travel_location, 
                    'hotel_category' => $this->selectedCategory,
                    'total_days' => $this->nightHalt['day'],
                    'total_nights' => $this->nightHalt['night'],
                    'itinerary_journey' => $formattedString
                ], // Search Conditions (Ensuring uniqueness)
                [
                    'type' => 'preset',
                    'itinerary_journey' => $formattedString, // Corrected placement
                    'itinerary_syntax' => $itinerary_syntax,
                    'night_journey' => $itinerary_journey,
                    'divisions_journey' => $itinerary_journey_divisions,
                    'stay_by_journey' => $stay_by_journey
                ] // Data to update or insert
            );
    
            // Commit transaction if everything is successful
            DB::commit();
            session()->flash('success', 'Night halt saved successfully!');
            $this->dispatch('refreshPage');

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on error
            $this->newPresetError = $e->getMessage();
            // session()->flash('error', 'Failed to save itinerary: ' . $e->getMessage());
        }
    }
    public function loadAllDayByDivision(){
        $itineraryExists = Itinerary::find($this->selected_night_halt);
        if(!$itineraryExists){
            abort(404);
        }

        $stay_by_journey = explode(',',$itineraryExists->stay_by_journey);
        if(count($stay_by_journey)>0){
            $days_journey = [];

            // Remove the last element
            array_pop($stay_by_journey);

            $travelDate = Carbon::parse($this->leadData->travel_date);
            
            foreach($stay_by_journey as $k=>$journey){
                $city = City::find($journey);

                $date = $travelDate->copy()->addDay($k);
                $season = CustomHelper::GetSeason($this->leadData->travel_location, $date->format('m-d'));
                if (!$season) {
                    session()->flash('error', 'No season period has been defined for the selected destination. Please add the season details first.');
                    return false;
                }

                $days_journey[$k+1]['division_id'] = $journey;
                $days_journey[$k+1]['division_name'] = $city?$city->name:"N/A";

                $this->package[$k+1]['season']= $season;
                // Hotel
                $related_hotels = Hotel::with('priority_rooms')
                ->where('division', $journey)
                ->orderBy('priority', 'ASC')
                ->get();
                $selected_hotel = $related_hotels->first();
                if ($selected_hotel && $selected_hotel->priority_rooms) {
                    if(count($selected_hotel->priority_rooms)>0){
                        $selected_room = $selected_hotel->priority_rooms->first();
                        if($selected_room){
                            // Get Main Plan
                            $room_price_details = HotelPriceChartType::where('hotel_id', $selected_hotel->id)
                            ->where('room_id', $selected_room->id)
                            ->where('type', 2)
                            ->whereHas('seasonNames', function($q) use ($season){
                                $q->where('plan_item', 'CP')->where('plan_title', $season);
                            })
                            ->with(['seasonNames' => function($q) use ($season){
                                $q->where('plan_item', 'CP')->where('plan_title', $season);
                            }])
                            ->first();
                           
                            // Safely get the price
                            $room_single_price = optional(optional($room_price_details)->seasonNames)->first()->item_price ?? null;
                            if (!$room_single_price) {
                                session()->flash('error', 'No price chart found for the hotel "' . $selected_hotel->name . '" and room "' . ($selected_room->room_name ?? 'N/A') . '". Please update the price chart first.');

                                return false;
                            }

                            $room_value = $this->leadData->number_of_adults / $selected_room->no_of_beds;
                            $room_quantity = $room_value < 0 ? floor($room_value) : ceil($room_value);

                            $main_plan_name = optional(optional($room_price_details)->seasonNames)->first()->plan_item ?? null;

                            $roomData = [];
                            $roomData['room_id'] = $selected_room->id;
                            $roomData['room_name'] = $selected_room->room_name;
                            $roomData['room_capacity'] = $selected_room->capacity;
                            $roomData['main_plan_name'] = $main_plan_name;
                            $roomData['main_plan_quantity'] = $room_quantity;
                            $roomData['main_plan_price'] = $room_single_price*$room_quantity;

                            // Addon plans
                            $AddonPlans = SeasionPlan::where('type', 'addon')->where('status', 1)->orderBy('position', 'ASC')->get();
                        
                            $addon_seasion_plan = [];
                            foreach($AddonPlans as $addon_index=>$addon_item){
                                $array_item = explode(', ',$addon_item->plan_item);
                                $addon_seasion_plan[$addon_index] =[
                                    'title'=>$addon_item->title,
                                    'item'=>$array_item,
                                ];
                            }

                            $selected_hotel->addon_seasion_plan = $addon_seasion_plan;
                            // Main Related Plan
                            $main_related_plans = HotelPriceChartType::where('hotel_id', $selected_hotel->id)
                            ->where('room_id', $selected_room->id)
                            ->where('type', 2)
                            ->whereHas('seasonNames', function($q) use ($season, $main_plan_name){
                                $q->where('plan_title', $season);
                            })
                            ->with(['seasonNames' => function($q) use ($season, $main_plan_name){
                                $q->where('plan_title', $season);
                            }])->first();
                            // dd($main_related_plans);
                            $roomData['main_related_plans'] = optional($main_related_plans)->seasonNames ?? null;

                            $selected_hotel->selected_room = $roomData;
                        }
                    }
                    $selected_hotel->related_rooms = $selected_hotel->priority_rooms;
                    unset($selected_hotel->priority_rooms);
                }else{
                    session()->flash('error', 'No hotels are available in the selected division: '.$city->name);
                    return redirect()->back();
                }

                $this->package[$k + 1]['hotel']['selected_hotel'] = $selected_hotel->id;
                $this->package[$k + 1]['hotel']['selected'] = $selected_hotel ? collect($selected_hotel)->except(['priority_rooms'])->toArray() : null;
                $this->package[$k + 1]['hotel']['related_hotels'] = $related_hotels->map(function ($hotel) {
                    $hotel->related_rooms = $hotel->priority_rooms;
                    return collect($hotel)->except(['priority_rooms']);
                })->toArray();
            }
           
            $this->day_by_divisions = $days_journey;
            // dd($this->package);
        }
    }

    public function ChangeNightHalt($value){
        if (!$value) {
            session()->flash('error', 'Please select a night halt package before proceeding.');
            $this->day_by_divisions = [];
            return;
        }
        $this->selected_night_halt = $value;
        $this->loadAllDayByDivision();
    }

    public function updatePackageType($type){
        $this->selected_package_type = $type;
    }

    public function render()
    {
        $this->total_amount = 0;
        
        return view('livewire.itinerary.lead-itinerary-customization');
    }
}
