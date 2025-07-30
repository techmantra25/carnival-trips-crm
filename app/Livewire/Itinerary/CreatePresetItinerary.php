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
use App\Models\DivisionWiseSightseeingImage;
use App\Models\DivisionWiseSightseeing;
use App\Models\ServiceWiseSightseeing;
use App\Models\DivisionWiseActivityImage;
use App\Models\Itinerary;
use App\Models\RouteServiceSummary;
use App\Models\DivisionWiseActivity;
use App\Models\ServiceWiseActivity;
use App\Models\DestinationWiseRoute;
use App\Models\ItineraryDetail;
use App\Models\HotelPriceChartType;
use App\Models\ItineraryTemplate;
use App\Models\ItineraryTemplateDetail;
use App\Helpers\CustomHelper;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Carbon;

use App\Models\ItineraryBanner;

class CreatePresetItinerary extends Component
{
    use WithFileUploads;
    public $stay_by_journey = [];
    public $destinationId;
    public $inclusions = [];
    public $exclusions = [];
    public $selectedInclusions = [];
    public $selectedExclusions = [];
    public $selectedTriphighlight = [];
    public $triphighlight = [];
    public $itineraryType;
    public $itinerary_id;
    public $active_assign_route_modal;
    public $selectedDivision;
    public $categoryId;
    public $mainBanner = [];
    public $hotel_room_price = [];
    public $destinationName;
    public $categoryName;
    public $itinerary_syntax;
    public $itinerary_journey = [];
    public $night;
    public $errorMessage = '';
    public $uploadDestinationSlider = []; 
    public $uploadDayImages = [];
    public $day_by_divisions = [];

    public $selected_rooms = [];
    public $selected_room_plan = [];

    public $showModal = false;
    public $modalImage = '';
    
    public $uploadMainBanner;
    public $name_of_lead, $welcome_to, $main_banner, $about_destination_title, $about_destination_text;
    public $destinationImages = [];
    public $dayImages = [];
    public $dayHotels = [];
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
    public $existing_night_halt_details = [];
    public $selected_night_halt;
    public $total_amount = 0;
    public $itinerary_journey_divisions = [];
    public $active_new_night_halt_modal = 0;
    public $active_night_distribution = 0;
    public $selectedCategory =null;
    public $selected_night_halt_details =null;
    public $newPresetError = '';
    public $categories;
    public $divisions = [];
    public $nightHalt = [
        'day' => "",
        'night' => "",
        'night_sum' => 0,
        'night_distribution' => "",
    ];
    public $activeInExTab = 'inclusion';
    public $active_new_route_modal,$new_route_name,$service_summary_id;
    public $active_new_sightseeing_modal = 0;
    public $active_route_new_sightseeing_modal = 0;
    public $new_sightseeings = [];
    public $new_activities = [];
    public $active_new_activity_modal = 0;
    public $active_route_new_activity_modal = 0;

    public function mount($encryptedId){
        $itineraryExists = Itinerary::with('lead')->find($encryptedId);
        $this->itinerary_id =$encryptedId;
        $this->stay_by_journey =$itineraryExists->stay_by_journey;
        $this->leadData = $itineraryExists->lead;
    
        $this->itineraryType =$itineraryExists->type;
        $categoryExists = Category::where('id', $itineraryExists->hotel_category)->first();
        $this->categories = Category::where('status', 1)->orderBy('name', 'ASC')->get();
        $this->destinationName = $itineraryExists->destination->name;
        $this->categoryName = $categoryExists->name;
        $this->destinationId = $itineraryExists->destination_id;
        $this->categoryId = $itineraryExists->hotel_category;
        $this->itinerary_syntax = $itineraryExists->itinerary_syntax;
        $this->selected_night_halt_details = $itineraryExists->itinerary_journey;
        $city = City::where('state_id', $itineraryExists->destination_id)->first();
        if($city){
            $this->selectedDivision = $city->id;
        }


        
        // Get divisions
        $stay_by_journey = explode(',',$itineraryExists->stay_by_journey);
        if(count($stay_by_journey)>0){
            $days_journey = [];
            foreach($stay_by_journey as $k=>$journey){

                $hotels = Hotel::select('id', 'name')
                // ->where('hotel_category', $this->categoryId)
                ->where('division', $journey)->orderBy('name', 'ASC')->get()->toArray();

                $destination_route = RouteServiceSummary::with('route')->where('destination_id', $this->destinationId)->where('service_type', 'Route Wise')->get()->toArray();

                $city = City::find($journey);

                
                $days_journey[$k+1]['division_id'] = $journey;
                $days_journey[$k+1]['division_name'] = $city?$city->name:"N/A";
                $days_journey[$k+1]['division_hotels'] =$hotels;
                $days_journey[$k+1]['division_routes'] =$destination_route;
                $days_journey[$k+1]['day_hotel'] =$this->loadDayHotels($k+1);
                $days_journey[$k+1]['day_route'] =$this->loadDayRoutes($k+1);
                $days_journey[$k+1]['per_day_cab'] =$this->loadDayCabs($k+1);
                $this->loadDayImages($k+1);
            }
            $this->day_by_divisions = $days_journey;
        }
        $this->ExistingData();
        if($this->leadData){
            $this->fetchNightHaltDetails();
            $this->nightHalt['day'] = $this->leadData->travel_in_days;
            $this->nightHalt['night'] = $this->leadData->travel_in_nights;
            $this->active_night_distribution = 1;
        }

        $this->getInclusionExclusion();
        $this->getTriphighlight();
        $this->loadSelectedInclusions();
        $this->loadSelectedExclusions();
        $this->loadSelectedtripHighlight();
        
    }

    public function getInclusionExclusion(){
        $template= ItineraryTemplate::where('destination_id', $this->destinationId)->first();

        if($template){
            $this->inclusions = ItineraryTemplateDetail::where('itinerary_template_id', $template->id)->where('header', 'inclusion_exclusions')->where('field', 'like', 'inclusions_%')->pluck('value')->toArray();

            $this->exclusions = ItineraryTemplateDetail::where('itinerary_template_id', $template->id)->where('header', 'inclusion_exclusions')->where('field', 'like', 'exclusions_%')->pluck('value')->toArray();
            // Delete non-existing inclusions
            if (count($this->inclusions) > 0) {
                ItineraryDetail::where('itinerary_id', $this->itinerary_id)
                    ->where('header', 'inclusion_exclusions')
                    ->where('field', 'like', 'inclusions_%')
                    ->whereNotIn('value', $this->inclusions)
                    ->delete();
            }

            // Delete non-existing exclusions
            if (count($this->exclusions) > 0) {
                ItineraryDetail::where('itinerary_id', $this->itinerary_id)
                    ->where('header', 'inclusion_exclusions')
                    ->where('field', 'like', 'exclusions_%')
                    ->whereNotIn('value', $this->exclusions)
                    ->delete();
            }
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
    // Check Existing Itinerary Details


    public function fetchNightHaltDetails(){
        // Fetch itinerary package
        $this->existing_night_halt_details = Itinerary::select('id', 'itinerary_journey')->where('type', 'post_inquiry')->where('itinerary_syntax', $this->leadData->travel_duration_text)->where('destination_id', $this->leadData->travel_location)->orderBy('updated_at', 'DESC')->get()->toArray();
        $this->selected_night_halt = $this->itinerary_id;
    }
    public function NewNightHalt($value){

        $this->divisions= City::where('state_id', $this->leadData->travel_location)->orderBy('name', 'ASC')->get();

        $this->nightHalt['night_sum'] = 0;
        $this->nightHalt['night_distribution'] = "";
        $this->active_new_night_halt_modal = $value=="yes"?1:0;
        $this->dispatch('refreshComponent');
    }
    public function GetCategory($value){
        $this->selectedCategory = $value;
        $this->active_new_night_halt_modal = 1;
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
                    'lead_id' => $this->leadData->id,
                    'destination_id' => $this->leadData->travel_location, 
                    'hotel_category' => $this->selectedCategory,
                    'total_days' => $this->nightHalt['day'],
                    'total_nights' => $this->nightHalt['night'],
                    'itinerary_journey' => $formattedString
                ], // Search Conditions (Ensuring uniqueness)
                [
                    'type' => 'post_inquiry',
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
            $lead_id = Crypt::encrypt($this->leadData->id);
            return redirect()->route('admin.itinerary.preset.list',['lead_id'=>$lead_id]);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on error
            $this->newPresetError = $e->getMessage();
            // session()->flash('error', 'Failed to save itinerary: ' . $e->getMessage());
        }
    }

    public function ChangeNightHalt($value){
        if (!$value) {
            session()->flash('error', 'Please select a night halt package before proceeding.');
            return;
        }
        $itinerary_id = Crypt::encrypt($value);
        return redirect()->route('admin.itinerary.preset.build', $itinerary_id);
    }

    public function ExistingData(){
        // Banner section
        $name_of_lead = $this->leadData?$this->leadData->customer_name:"";
        $this->name_of_lead = ItineraryDetail::where('itinerary_id', $this->itinerary_id)->where('header', 'banner_section')->where('field', 'name_of_lead')->value('value');
        $this->name_of_lead = $this->name_of_lead?$this->name_of_lead:$name_of_lead;

        $this->welcome_to = ItineraryDetail::where('itinerary_id', $this->itinerary_id)->where('header', 'banner_section')->where('field', 'welcome_to')->value('value');
        $this->main_banner = ItineraryDetail::where('itinerary_id', $this->itinerary_id)->where('header', 'banner_section')->where('field', 'main_banner')->value('value');

        // About Destination
        $this->about_destination_title = ItineraryDetail::where('itinerary_id', $this->itinerary_id)->where('header', 'about_destination')->where('field', 'about_destination_title')->value('value');
        $this->about_destination_text = ItineraryDetail::where('itinerary_id', $this->itinerary_id)->where('header', 'about_destination')->where('field', 'about_destination_text')->value('value');
        $this->trip_highlights = ItineraryDetail::where('itinerary_id', $this->itinerary_id)->where('header', 'about_destination')->where('field', 'like', 'trip_highlights_%')->pluck('value')->toArray();

        // Fetch all slider images from DB
        $this->loadDestinationImages();

        // Load Day wise data
        $this->day_texts = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
        ->where('header', 'LIKE', 'day_%')
        ->pluck('value', 'field')
        ->toArray();
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

    public function updatedUploadMainBanner()
    {
        // Validate Image
        DB::beginTransaction();
        try {
            $this->validate([
                'uploadMainBanner' => 'image|max:2048', // 2MB Max
            ]);
            // dd($this->selectedDivision);
            $store = new ItineraryBanner;
            $store->division_id = $this->selectedDivision;
            $store->destination_id = $this->destinationId;

            $uploadedFiles = $this->uploadMainBanner ?? null;
            if ($uploadedFiles) {
                $dynamicText =rand(1111,9999);
                $destinationName = $this->destinationName; // Assuming you have a division name
                $uploadedPath = CustomHelper::uploadImage($uploadedFiles, $dynamicText, $destinationName, 'itinerary_banners');
                $store->image = $uploadedPath;
            }
            $store->save();
            DB::commit();
            session()->flash('success', 'Banner uploaded successfully!');
        } catch (\Exception $e) {
            $this->reset(['uploadMainBanner']);
            DB::rollBack(); // Rollback on error
            session()->flash('error', 'Failed: ' . $e->getMessage());
        }
        
    }
    public function loadDestinationImages() {
        // Fetch all slider images from DB
        $this->destinationImages = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
            ->where('header', 'about_destination')
            ->where('field', 'slider_image')
            ->pluck('value')
            ->toArray();
    }
    public function loadDayImages($index){
        $this->dayImages[$index] = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
            ->where('header', 'day_' . $index)
            ->where('field', 'day_image')
            ->pluck('value')
            ->toArray();
    }
    public function loadDayHotels($index){
        // Fetch itinerary detail with hotel relation
        $data = ItineraryDetail::with('hotel')
        ->where('itinerary_id', $this->itinerary_id)
        ->where('header', 'day_' . $index)
        ->where('field', 'day_hotel')
        ->get();

        // Ensure hotel data exists before assigning
        $results = [];
       // Loop through each itinerary detail and extract hotel data
     
        foreach ($data as $item) {
            if ($item->hotel) {
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
                    'hotel_image'    => optional($item->hotel)->image,
                    'hotel_address' => optional($item->hotel)->address,
                    'hotel_rooms'   => optional($item->hotel)->rooms,
                    'selected_room'   => $room?$room->room_id:null,
                    'selected_room_main_plan' => $selected_room_main_plan?$selected_room_main_plan:null,
                ];
            }
        }
        return $results;
        // Merge and assign the updated values
    }
    public function ReloadDayHotels($index){
        // Fetch itinerary detail with hotel relation
        $data = ItineraryDetail::with('hotel')
        ->where('itinerary_id', $this->itinerary_id)
        ->where('header', 'day_' . $index)
        ->where('field', 'day_hotel')
        ->get();

        // Ensure hotel data exists before assigning
        $results = [];
       // Loop through each itinerary detail and extract hotel data
        foreach ($data as $item) {
            if ($item->hotel) {

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
                    'hotel_name'    => optional($item->hotel)->name,
                    'hotel_image'    => optional($item->hotel)->image,
                    'hotel_address' => optional($item->hotel)->address,
                    'hotel_rooms'   => optional($item->hotel)->rooms,
                    'selected_room'   => $room?$room->room_id:null,
                    'selected_room_main_plan'   => $selected_room_main_plan?$selected_room_main_plan:null,
                ];
            }
        }

       
        $this->day_by_divisions[$index]['day_hotel'] = $results;
        // Merge and assign the updated values
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
                            'ticket_price' => optional($act_item->activity)->ticket_price,
                        ];
                    }

                    // // Existing Sightsiings
                    foreach($RouteServiceSummary->sightseeings as $sight_index =>$sight_item){
                        $existing_sightseeings[] = [
                            'name'=> optional($sight_item->sightseeing)->name,
                            'type'         => optional($sight_item->sightseeing)->type,
                            'ticket_price' => optional($sight_item->sightseeing)->ticket_price,
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
                            'ticket_price' => optional($act_item->activity)->ticket_price,
                        ];
                    }

                    // // Existing Sightsiings
                    foreach($RouteServiceSummary->sightseeings as $sight_index =>$sight_item){
                        $existing_sightseeings[] = [
                            'name'=> optional($sight_item->sightseeing)->name,
                            'type'         => optional($sight_item->sightseeing)->type,
                            'ticket_price' => optional($sight_item->sightseeing)->ticket_price,
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
   
    public function updateduploadDestinationSlider()
    {
        // dd($this->uploadDestinationSlider);
        DB::beginTransaction();

        try {
            // Validate each uploaded image
            $this->validate([
                'uploadDestinationSlider.*' => 'image|max:2048', // 2MB max per image
            ]);

            foreach ($this->uploadDestinationSlider as $image) {
                if ($image) {
                    $dynamicText = rand(1111, 9999);
                    $uploadedPath = CustomHelper::uploadImage($image, $dynamicText, $this->destinationName, 'itinerary');

                    // Save file path in database
                    ItineraryDetail::create([
                        'itinerary_id' => $this->itinerary_id,
                        'header' => 'about_destination',
                        'field' => 'slider_image',
                        'value' => $uploadedPath,
                    ]);
                }
            }

            DB::commit();

            // Reload images after upload
            $this->loadDestinationImages();

            session()->flash('success', 'Images uploaded successfully!');
            $this->reset('uploadDestinationSlider'); // Reset input after upload

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Upload failed: ' . $e->getMessage());
        }
    }
    public function updatedUploadDayImages($images, $index)
    {
        // dd($this->uploadDestinationSlider);
        if (!isset($this->uploadDayImages[$index])) {
            $this->uploadDayImages[$index] = [];
        }
        DB::beginTransaction();

        try {
            // Validate each uploaded image
            $this->validate([
                "uploadDayImages.$index.*" => 'image|max:2048', // Validate each image (2MB max)
            ]);

            foreach ($images as $image) {
                if ($image) {
                    $dynamicText = rand(1111, 9999);
                    $uploadedPath = CustomHelper::uploadImage($image, $dynamicText, $this->destinationName, 'itinerary');

                    // Save file path in database
                    ItineraryDetail::create([
                        'itinerary_id' => $this->itinerary_id,
                        'header' => 'day_' . $index,
                        'field' => 'day_image',
                        'value' => $uploadedPath,
                    ]);
                }
            }

            DB::commit();
            // Fetch newly uploaded images
            $this->loadDayImages($index);
            session()->flash('success', 'Images uploaded successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Upload failed: ' . $e->getMessage());
        }
    }
    public function ItineraryImageDelete($slider_image){
        DB::beginTransaction();
    
        try {
            $item = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
                ->where('value', $slider_image)
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
    
                // Reload updated images list
                $this->loadDestinationImages();
    
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
    public function showImageModal($image)
    {
        $this->modalImage = $image;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function getHotel($index,$id){
        try {
            if (!$id) {
                throw new \Exception("Invalid hotel ID");
            }
    
            // Find the hotel
            $hotel = Hotel::find($id);
            if (!$hotel) {
                throw new \Exception("Hotel not found");
            }
    
            // Delete Existing Another Hotel Details
            $hotel_details = ItineraryDetail::where('itinerary_id', $this->itinerary_id)
            ->where('header', 'day_' . $index)
            ->whereNotNull('hotel_id')
            ->get();
            if ($hotel_details) {
                $hotel_details->each(function ($detail) {
                    $detail->delete();
                });
            }
            // Start database transaction
            DB::beginTransaction();
    
            // Update or create itinerary detail
            ItineraryDetail::updateOrCreate(
                [
                    'itinerary_id' => $this->itinerary_id,
                    'header' => "day_$index", // Assuming you meant to use 'day_{index}'
                    'field' => 'day_hotel',
                ],
                [
                    'value' => $hotel->name, // Store the hotel ID
                    'hotel_id' => $hotel->id,
                ]
            );
    
            // Commit transaction

            DB::commit();
            $this->ReloadDayHotels($index);
            $this->errorHotel[$index] = '';
        } catch (\Exception $e) {
            // Rollback transaction if there's an error
            DB::rollBack();
    
            // Store error message for Livewire validation
            $this->errorHotel[$index] = $e->getMessage();
        }
    }
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
    
            // Commit transaction
            
            DB::commit();
          
            $this->errorRoute[$index] = '';
            $this->ReloadDayRoute($index);
            $this->active_new_route_modal = 0;
        } catch (\Exception $e) {
            // Rollback transaction if there's an error
            DB::rollBack();
            // Store error message for Livewire validation
            $this->errorRoute[$index] = $e->getMessage();
        }
    }
    public function getActivityOrSightseeing($field, $index, $route_index, $route_service_summary_id, $value, $price, $ticket_price,$cab_id){
        try {
            // Start database transaction
            DB::beginTransaction();
            // Ensure price and ticket price are numeric
            $number_of_travellers = 1;

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
                    'value_quantity' => $ticket_price>0?$number_of_travellers:1,
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
    public function getPerDayCab($field, $index, $value, $price, $cab_id){
        try {
            // Start database transaction
            DB::beginTransaction();

            if($field=='per_day_cab'){
                $totalPrice = round((float) $price);
            }
           
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
                ]
            );
    
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
            'new_sightseeings.*.description' => 'nullable',
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
                // if($this->leadData->nationality_type=="Indian"){
                    $ticket_price = (float) ($sightseeing['ticket_price'] ?? 0);
                    $value_quantity = 1;
                // }else{
                //     $ticket_price = (float) ($sightseeing['ticket_price_nri'] ?? 0);
                //     $value_quantity = $ticket_price>0?$this->leadData->number_of_travellor:1;
                // }
                
               
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
                        'price' => round((float) $ticket_price * 1),
                        'value_quantity' => $value_quantity,
                        'ticket_price' => round((float) $ticket_price * 1),
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
                    'description' => $activity['description'], // Ensure this key exists and is 'PAID' or 'UNPAID'
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
                // if($this->leadData->nationality_type=="Indian"){
                    $ticket_price = (float) ($activity['ticket_price'] ?? 0);
                    $value_quantity = 1;
                // }else{
                //     $ticket_price = (float) ($activity['ticket_price_nri'] ?? 0);
                //     $value_quantity = $ticket_price>0?$this->leadData->number_of_travellor:1;
                // }
                
                $traveller_count = (int) 1;
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
                        'ticket_price' => round((float) $ticket_price * 1),
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
        dd($this->all());
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
        // dd($roomId);
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
                    'value_quantity' => 1, // Store the hotel ID
                    'price' => $price?$price:0,
                ]
            );

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

    public function decreaseQuantity($index,$id){
        $ItineraryDetail = ItineraryDetail::find($id);

        if (!$ItineraryDetail) {
            return; // Exit if the item is not found
        }

        // Ensure value_quantity is at least 1 to avoid division by zero
        $value_quantity = ($ItineraryDetail->value_quantity == "" || $ItineraryDetail->value_quantity == 0) ? 1 : $ItineraryDetail->value_quantity;

        $updated_quantity = $value_quantity - 1;

        if ($updated_quantity >= 1) {
            $piece_price = $ItineraryDetail->price / $value_quantity; // Ensure no division by zero
            $ItineraryDetail->price = $piece_price * $updated_quantity;
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
            $piece_price = $ItineraryDetail->price / $value_quantity; // Ensure no division by zero
            $ItineraryDetail->price = $piece_price * $updated_quantity;
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

    public function updateQuantity($index, $id){
        $ItineraryDetail = ItineraryDetail::find($id);
      
        if (!$ItineraryDetail) {
            return; // Exit if the item is not found
        }

        // Ensure the quantity is set, otherwise use default (1)
        $updated_quantity = $this->itemQuantity[$index][$id] ?? 0;

        // Calculate new price based on updated quantity
        $piece_price = $ItineraryDetail->price / max($ItineraryDetail->value_quantity, 1);
        
        if ($updated_quantity === "" || $updated_quantity == 0) {
            $updated_quantity = 0;
            $ItineraryDetail->price = $piece_price*1;
        }else{
            $ItineraryDetail->price = $piece_price * $updated_quantity;
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
        $ItineraryDetail = ItineraryDetail::where('itinerary_id', $this->itinerary_id)->get();
        if (count($ItineraryDetail)>0) {
            $ItineraryDetail->each(function ($detail) {
                $detail->delete();
            });
        }
        $itinerary_id = Crypt::encrypt($this->itinerary_id);
        return redirect()->route('admin.itinerary.preset.build', $itinerary_id);
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
    public function render()
    {   
       
        $this->GetAllQuantity();
        $this->total_amount = ItineraryDetail::where('itinerary_id', $this->itinerary_id)->sum('price');
        // Per day wise amount
        $this->mainBanner = ItineraryBanner::where('destination_id', $this->destinationId)->get();
        return view('livewire.itinerary.create-preset-itinerary');
    }
   
}
