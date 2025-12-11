<?php

namespace App\Livewire\Itinerary;

use Livewire\Component;
use App\Models\State;
use App\Models\SeasionPlan;
use App\Models\Itinerary;
use App\Models\ItineraryDetail;
use App\Models\City;
use App\Models\Lead;
use App\Models\ServiceWiseSightseeing;
use App\Models\ServiceWiseActivity;
use App\Models\Category;
use App\Helpers\CustomHelper;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
class CostCalculatorQueryList extends Component
{
    public $destinations = [];
    public $Itineraries = [];
    public $destination, $selectedType,$search,$meal_type,$customer_name,$total_members,$number_of_adults,$number_of_childs,$source_type,$mobile_number,$arrival_date,$departure_date,$company_name,$whatsapp_number,$email_address,$hotel_category,$package_type,$package_nights,$package_days,$nationality_type,$number_of_rooms,$extra_mattress,$valid_from_day,$valid_from_month,$valid_to_day,$valid_to_month;
    public $active_assign_new_modal = 0;
    public $queryTypes = [];
    public $companies = [];
    public $packageTypes = [];
    public $mealTypes;
    public $filter_destination;
    public $existing_night_halt_details = [];
    public $night_halt = '';
    public $night_halt_details = '';
    public $night_halt_status = 0;

    public $active_night_distribution = 0;
    public $active_new_night_halt_modal = 0;
    public $newPresetError = '';
    public $categories;
    public $divisions = [];
    public $childsData = [];
    public $nightHalt = [
        'day' => "",
        'night' => "",
        'night_sum' => 0,
        'night_distribution' => "",
    ];
    public $itinerary_journey = [];
    public $itinerary_journey_divisions = [];
    public $selectedCategory =null;

    public $enableChildren = false;
    public $childs = [];

    public function mount(){
        $this->resequence_itinerary_details();

        $this->queryTypes = collect([
            ['name' => 'Facebook'],
            ['name' => 'Instagram'],
            ['name' => 'Website'],
        ]);
        $this->companies = collect([
            ['name' => 'Global Tours Pvt Ltd'],
            ['name' => 'Holiday Planners'],
            ['name' => 'Eventive Solutions'],
            ['name' => 'TechNova Corp'],
            ['name' => 'Sunrise Travels'],
            ['name' => 'Wanderlust Adventures'],
            ['name' => 'ExploreMore Holidays'],
            ['name' => 'Dreamscape Voyages'],
            ['name' => 'Urban Getaways'],
            ['name' => 'Royal Journey Pvt Ltd'],
            ['name' => 'Others'],
        ]);

        $this->packageTypes = collect([
            ['name' => '3D/2N',  'day' => 3,  'night' => 2],
            ['name' => '4D/3N',  'day' => 4,  'night' => 3],
            ['name' => '5D/4N',  'day' => 5,  'night' => 4],
            ['name' => '6D/5N',  'day' => 6,  'night' => 5],
            ['name' => '7D/6N',  'day' => 7,  'night' => 6],
            ['name' => '8D/7N',  'day' => 8,  'night' => 7],
            ['name' => '9D/8N',  'day' => 9,  'night' => 8],
            ['name' => '10D/9N', 'day' => 10, 'night' => 9],
            ['name' => '11D/10N','day' => 11, 'night' => 10],
            ['name' => '12D/11N','day' => 12, 'night' => 11],
            ['name' => '13D/12N','day' => 13, 'night' => 12],
        ]);
        
        $this->destinations = State::where('status', 1)->orderBy('name', 'ASC')->get();
        if(count($this->destinations)>0){
            $this->destination = $this->destinations['0']->id;
        }
        $this->mealTypes = SeasionPlan::where('status', 1)->where('type', 'main')->orderBy('position', 'ASC')->first();
        $this->categories = Category::where('status', 1)->orderBy('name', 'ASC')->get();
        $this->fetchItinerary();
    }


    public function addExtraChild(){
        $this->childs[] = ['quantity'=>'', 'age'=>''];
    }
    public function removeExtraChild($index){
        unset($this->childs[$index]);
        $this->childs = array_values($this->childs);
    }
   public function toggleChildren()
    {
        if (!$this->enableChildren) {
            $this->childs = [];
        }
    }

    public function resequence_itinerary_details(){
        try {
            DB::beginTransaction();

            // Step 1: Fetch all records ordered by created_at
            $records = DB::table('itinerary_details')->orderBy('created_at')->get();

            // Step 2: Remove ID and convert to array
            $data = $records->map(function ($item) {
                $arr = (array) $item;
                unset($arr['id']);
                return $arr;
            })->toArray();
            // Step 3: Truncate table
            DB::table('itinerary_details')->truncate();

            // Step 4: Re-insert
            DB::table('itinerary_details')->insert($data);

            DB::commit();

            // return response()->json(['status' => 'success', 'message' => 'Itinerary resequenced successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            // return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getDestination($value){
        $this->filter_destination = $value;
    }

    public function changeDestination($value){
        $this->destination = $value;
        $this->reset(['night_halt','night_halt_details']);
        $this->dispatch('refreshComponent');
    }
    public function changeMealPlan($value){
        $this->meal_type = $value;
    }
    public function changeNationalityType($value){
        $this->nationality_type = $value;
    }

    public function getItinerary(){
        $this->Itineraries =  Itinerary::with('lead')->where('type', 'query')->whereNotNull('lead_id')->whereHas('lead', function ($q) {
                $q->where('generate_from', 'query');
            })
        ->when($this->filter_destination, function ($query) {
            $query->where('destination_id', $this->filter_destination); // Use '=' for exact match
        })
        ->when($this->selectedCategory, function ($query) {
            $query->where('hotel_category', $this->selectedCategory); // Use '=' for exact match
        })
        ->when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('itinerary_syntax', 'like', "%{$this->search}%")
                  ->orWhere('itinerary_journey', 'like', "%{$this->search}%")
                  ->orWhere('type', 'like', "%{$this->search}%");
            })
            ->orWhereHas('lead', function ($q) {
                $q->where('unique_id', 'like', "%{$this->search}%")
                  ->orWhere('customer_name', 'like', "%{$this->search}%")
                  ->orWhere('customer_mobile', 'like', "%{$this->search}%")
                  ->orWhere('customer_email', 'like', "%{$this->search}%")
                  ->orWhere('customer_whatsapp', 'like', "%{$this->search}%")
                  ->orWhere('number_of_travellor', 'like', "%{$this->search}%");
            });
        })
        ->orderBy('id', 'DESC')
        ->get();
    }

    public function changeAddonType($value){
        $this->reset(['childsData']);
        $rawItems = SeasionPlan::where('status', 1)
        ->where('type', 'addon')
        ->where('title', $value)
        ->pluck('plan_item')
        ->toArray();

        $flattened = collect($rawItems)
        ->flatMap(function ($item) {
            return array_map('trim', explode(',', $item));
        })
        ->filter(function ($item) {
            return str_contains($item, "[{$this->meal_type}]");
        })
        ->unique()
        ->values()
        ->toArray();
        $this->childsData = $flattened;
    }
    public function NewPresetItinerary($value){
       // Make sure $this->mealTypes exists and has plan_item
        $planItemString = $this->mealTypes->plan_item ?? '';

        // Convert to array
        $items = array_filter(array_map('trim', explode(',', $planItemString)));
        // Set first index value if exists, else null
        $this->meal_type = $items[0] ?? null;

        $rawItems = SeasionPlan::where('status', 1)
        ->where('type', 'addon')
        ->whereIn('title', ['CWM','CNB'])
        ->pluck('plan_item')
        ->toArray();

        $flattened = collect($rawItems)
            ->flatMap(function ($item) {
                return array_map('trim', explode(',', $item));
            })
            ->unique()
            ->values()
            ->toArray();
        $this->childsData = $flattened;

        $this->active_assign_new_modal = $value=="yes"?1:0;
        $this->dispatch('refreshComponent');
    }
    public function fetchItinerary(){
        $this->existing_night_halt_details = Itinerary::select('id', 'itinerary_journey')
        // ->whereIn('type', ['post_inquiry', 'query'])
        ->orderBy('updated_at', 'DESC')
        ->where('itinerary_syntax', $this->package_type)
        ->get()
        ->unique('itinerary_journey') // Ensures only unique values based on itinerary_journey
        ->values() // Reset array keys
        ->toArray();
    }
    public function filterNightHalt($value)
    {
        if($value && empty($this->hotel_category)){
            $this->addError('night_halt_details', 'Please first choose hotel category');
            return false;
        }
        if($value && empty($this->package_type)){
            $this->addError('night_halt_details', 'Please first choose package type');
            return false;
        }
       
        $this->resetErrorBag('night_halt_details');

        $this->night_halt_status = 1;
        $this->night_halt_details = '';
        $this->night_halt = '';
        $this->existing_night_halt_details = Itinerary::select('id', 'itinerary_journey')
        ->whereIn('type', ['preset','query'])
        ->where('destination_id', $this->destination)
        ->where('hotel_category', $this->hotel_category)
        ->where('itinerary_syntax', $this->package_type)
        ->where('itinerary_journey', 'LIKE', '%' . $value . '%') // filter using LIKE
        ->orderBy('updated_at', 'DESC')
        ->get()
        ->unique('itinerary_journey') // still ensure unique if needed
        ->values()
        ->toArray();
    }

    public function setNightHalt($id,$value)
    {
        $this->night_halt = $id;
        $this->night_halt_details = $value;
        $this->dispatch('setNightHaltInput', ['value' => $value]);
        $this->night_halt_status = 0;
    }


    // For New Night halt details 
    public function NewNightHalt($value){

        if(empty($this->package_days) && empty($this->package_nights)){
            session()->flash('error', 'Please first choose package type.');
            return false;
        }

        $this->nightHalt = [
            'day' => $this->package_days,
            'night' => $this->package_nights
        ];

        $this->nightHalt['night_sum'] = 0;
        $this->nightHalt['night_distribution'] = "";
        $this->active_new_night_halt_modal = $value=="yes"?1:0;
        $this->active_night_distribution = 1;
        $this->validateDaysAndNights();
        $this->dispatch('refreshComponent');
    }
    public function GetCategory($value){
        $this->selectedCategory = $value;
        $this->hotel_category = $value;
        $this->active_new_night_halt_modal = 1;
    }
    public function validateDaysAndNights(){
        if (!$this->destination) {
            $this->newPresetError = 'Please select destination first.';
            return;
        } elseif (!$this->selectedCategory) {
            $this->newPresetError = 'Please select category first.';
            return;
        } 
        // Generate array of day numbers
        $numbers = [];
        for ($i = 1; $i <= $this->nightHalt['day']; $i++) {
            $numbers[] = $i;
        }

        $this->itinerary_journey = $numbers;
    }

    public function updateJourneyDivision($index, $value){
        $last_index = $index+1;
        $this->itinerary_journey_divisions[$index] = $value;
        if($last_index !== count($this->itinerary_journey)){
            $this->nightHalt['night_distribution'] = CustomHelper::formatDayJourney($this->itinerary_journey_divisions);
        }
    }
    // public function validateNightDistribution(){
    //     if(!$this->selectedCategory){
    //         $this->newPresetError = 'Please select category first.';
    //         return;
    //     }
    //     $cleanedValue = str_replace('+', ' ', $this->nightHalt['night_distribution']);
    //      // Check if the cleaned value contains only numbers and spaces
    //     if (!preg_match('/^\d+(\s\d+)*$/', $cleanedValue)) {
    //         $this->newPresetError = 'Please enter a valid numeric value.';
    //         $this->reset(['itinerary_journey', 'itinerary_journey_divisions']);
    //         return;
    //     }
    //     $numbers = array_map('intval', explode(' ', $cleanedValue));
    //     $this->nightHalt['night_sum'] = array_sum($numbers);
    //     // Validate the total sum matches the expected night count
    //     if ($this->nightHalt['night'] != $this->nightHalt['night_sum']) {
    //         $this->newPresetError = 'Value should be equal to ' . $this->nightHalt['night'];
    //         $this->reset(['itinerary_journey', 'itinerary_journey_divisions']);
    //         return;
    //     }

    //     // If everything is correct, clear the error
    //     $this->itinerary_journey = $numbers;
    //     $this->newPresetError = null;
    // }

    public function nightHaltSubmitForm()
    {
        if(!$this->destination){
            $this->newPresetError = 'Please choose destination first.';
            return;
        }
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
        }elseif (!$this->valid_from_day || !$this->valid_from_month) {
            $this->newPresetError = 'Please select both day and month for "Valid From".';
            return;
        }elseif (!$this->valid_to_day || !$this->valid_to_month) {
            $this->newPresetError = 'Please select both day and month for "Valid To".';
            return;
        }
        // Convert carbon dates with a dummy years (e.g 2000)
        try {
            $validFrom = Carbon::createFromFormat('Y-m-d', '2000-' . $this->valid_from_month. '-' . $this->valid_from_day);
            $validTo = Carbon::createFromFormat('Y-m-d', '2000-' . $this->valid_to_month. '-' . $this->valid_to_day);
            
            if($validFrom->gt($validTo)){
                $this->newPresetError = '"Valid From" date must be before or equal to "Valid To" date.';
                return;
            }
             // Extract day and month from arrival_date for comparison
            $arrival = Carbon::parse($this->arrival_date); // actual date with year
            $arrivalComparable = Carbon::createFromFormat('Y-m-d', '2000-' . $arrival->format('m-d'));

            if ($validFrom->gt($arrivalComparable)) {
                $this->newPresetError = '"Valid From" date must be on or after the arrival date (' . $arrival->format('d M') . ').';
                return;
            }
        } catch (\Exception $e) {
            $this->newPresetError = 'Invalid date format selected.';
            return;
        }
        DB::beginTransaction();
        try {
            // Convert arrays to string format
            $itinerary_journey = implode(',', $this->itinerary_journey);
            
            $itinerary_journey_divisions = implode(',', $this->itinerary_journey_divisions);
         
            $formattedString = $this->nightHalt['night_distribution'];

            // Find existing record or create a new one
            $itinerary_syntax = $this->nightHalt['day'].'D/'.$this->nightHalt['night'].'N';
            $create = Itinerary::updateOrCreate(
                [
                    'destination_id' => $this->destination, 
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
                    'stay_by_journey' => $itinerary_journey_divisions,
                    'valid_from' => $this->valid_from_day.'-'.$this->valid_from_month, //Format: "MM-DD"
                    'valid_to' =>  $this->valid_to_day.'-'.$this->valid_to_month, //Format: "MM-DD"
                ] // Data to update or insert
            );

    
            // Commit transaction if everything is successful
            DB::commit();
            $this->reset(['nightHalt','itinerary_journey', 'itinerary_journey_divisions']);
            session()->flash('success', 'Night halt saved successfully!');
            $this->setNightHalt($create->id, $formattedString);
            $this->active_new_night_halt_modal = 0;

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on error
            $this->newPresetError = $e->getMessage();
            // session()->flash('error', 'Failed to save itinerary: ' . $e->getMessage());
        }
    }
    public function changePackageType($value, $day, $night)
    {
        $this->reset(['night_halt','night_halt_details']);
        $this->dispatch('refreshComponent');
        $this->package_type = $value;
        $this->package_days = $day;
        $this->package_nights = $night;
        $this->queryDateUpdate(Carbon::today(), $this->package_nights);
        $this->resetErrorBag('night_halt_details');
    }
   public function queryDateUpdate($start_date, $nights)
    {
        // Ensure $start_date is a Carbon instance
        if (!$start_date instanceof Carbon) {
            $start_date = Carbon::parse($start_date);
        }

        $arrival = $start_date;
        $this->arrival_date = $arrival->format('Y-m-d');

        $departure = $arrival->copy()->addDays((int)$nights);
        $this->departure_date = $departure->format('Y-m-d');

        $this->dispatch('setDate', [
            'arrival_date' => $this->arrival_date,
            'departure_date' => $this->departure_date,
        ]);
    }
    public function changeArrivalDate($value)
    {
        $this->queryDateUpdate($value, $this->package_nights);
    }
    public function changeHotelCategory($value){
        $this->reset(['night_halt','night_halt_details']);
        $this->dispatch('refreshComponent');
        $this->hotel_category = $value;
        $this->selectedCategory = $value;
        $this->resetErrorBag('night_halt_details');
    }

    public function submitForm(){

        $validated = $this->validate([
            'destination' => 'required',
            'customer_name' => 'required|string',
            'total_members' => 'required|numeric|min:1',
            'number_of_adults' => 'required|numeric|min:1',
            // 'number_of_childs' => 'nullable|numeric',
            'childs.*.addon_type' => 'required_if:enableChildren,true',
            'childs.*.quantity' => 'required_if:enableChildren,true',
            'childs.*.age' => 'required_if:enableChildren,true',

            'source_type' => 'required|string',
            'mobile_number' => 'nullable|digits:10',
            'arrival_date' => 'required|date',
            'departure_date' => 'required|date|after_or_equal:arrival_date',
            // 'company_name' => 'required|string',
            'whatsapp_number' => 'nullable|digits:10',
            'email_address' => 'nullable|email',
            'hotel_category' => 'required',
            'package_type' => 'required|string',
            'night_halt_details' => 'required',
            'meal_type' => 'required',
            'nationality_type' => 'required',
            'number_of_rooms' => 'required|numeric|min:1',
            'extra_mattress' => 'nullable|numeric|min:0',
        ], [
            'destination.required' => 'Destination is required.',
            'customer_name.required' => 'Guest name is required.',
            'total_members.required' => 'Total members required.',
            'total_members.numeric' => 'Total members must be a number.',
            'number_of_adults.required' => 'Adults count is required.',
            'number_of_adults.numeric' => 'Adults must be a number.',
            'source_type.required' => 'Source type is required.',
            'mobile_number.digits' => 'Mobile must be 10 digits.',
      
            'arrival_date.required' => 'Arrival Date is required.',
            'departure_date.required' => 'Departure Date is required.',
            'departure_date.after_or_equal' => 'Departure must be after arrival.',
            // 'company_name.required' => 'Company name is required.',
            'whatsapp_number.digits' => 'WhatsApp must be 10 digits.',
            'email_address.email' => 'Enter a valid email.',
            'hotel_category.required' => 'Hotel category is required.',
            'package_type.required' => 'Package type is required.',
            'night_halt_details.required' => 'Night halt is required.',
            'meal_type.required' => 'Meal type is required.',
            'nationality_type.required' => 'Nationality type is required.',
            'childs.*.addon_type.required_if' => 'Addon type is required.',
            'childs.*.quantity.required_if' => 'Quantity is required.',
            'childs.*.age.required_if' => 'Child age is required.',
            'number_of_rooms.required' => 'Number of rooms required.',
            'number_of_rooms.numeric' => 'Number of rooms must be a number.',
            'extra_mattress.numeric' => 'Extra mattress must be a number.',
        ]);

        // Match Day with package Type
        $startDate = Carbon::parse($this->arrival_date);
        $endDate = Carbon::parse($this->departure_date);
        $total_day = $startDate->diffInDays($endDate) + 1; // Inclusive of both dates
        $children_data = null;
        $quantity = 0;

        if ($this->enableChildren) {
            $childs = $this->childs;
            $children_data = json_encode($childs);
        
            foreach ($childs as $child_item) {
                $quantity += (int) $child_item['quantity'];
            }
        
            $total_member = $this->number_of_adults + $quantity;
        } else {
            $total_member = $this->number_of_adults;
        }
        // if(isset($this->extra_mattress) && $this->extra_mattress>0){
        //     $total_member = $total_member+$this->extra_mattress;
        // }

        if ($this->total_members != $total_member) {
            session()->flash('error', 'The total number of travelers does not match the number of adults and children. Please review and update.');
            return false;
        }
        if ($total_day != $this->package_days) {
            session()->flash('error', 'The travel duration does not match the package duration. Please check your start and end dates.');
            return false;
        }
        Db::beginTransaction();
        try{
            // dd('good');
            $lead = new Lead();
            // Assign the validated data to the model's properties
            $lead->unique_id = CustomHelper::GenerateUniqueId('leads', 'QRY');
            $lead->customer_name = ucwords($this->customer_name);
            $lead->customer_email = $this->email_address;
            $lead->generate_from = 'query';
            $lead->customer_mobile = $this->mobile_number;
            $lead->country_code = '91';
            $lead->customer_whatsapp = $this->whatsapp_number;

            $lead->travel_location = $this->destination;
            $lead->travel_duration = $total_day;

            $lead->travel_in_days = $this->package_days;
            $lead->travel_in_nights = $this->package_nights;
            $lead->travel_duration_text = $this->package_type;
            $lead->hotel_category = $this->hotel_category;

            $lead->arrival_date = $this->arrival_date;
            $lead->departure_date = $this->departure_date;
            
            $lead->number_of_adults = $this->number_of_adults;
            $lead->number_of_children = $quantity;
            $lead->children_data = $children_data;
            $lead->number_of_travellor = $this->total_members;

            $lead->source_type =$this->source_type;
            // $lead->lead_source =$this->company_name;
            $lead->meal_type =$this->meal_type;
            $lead->nationality_type =$this->nationality_type;
            $lead->number_of_rooms =$this->number_of_rooms;
            $lead->extra_mattress =$this->extra_mattress;
            $lead->created_by = Auth::guard('admin')->user()->id;
        
            // Save the model to the database
            $lead->save();

            if($lead){
                $fetch = Itinerary::find($this->night_halt);
                $create  = new Itinerary;
                $create->type = 'query';
                $create->lead_id = $lead->id;
                $create->destination_id = $fetch->destination_id;
                $create->hotel_category  = $fetch->hotel_category;
                $create->itinerary_syntax = $fetch->itinerary_syntax;
                $create->total_days = $fetch->total_days;
                $create->total_nights = $fetch->total_nights;
                $create->night_journey = $fetch->night_journey;
                $create->divisions_journey = $fetch->divisions_journey;
                $create->stay_by_journey = $fetch->stay_by_journey;
                $create->itinerary_journey = $fetch->itinerary_journey;
                $create->save();
                DB::commit();

                $this->cloneItineraryDetails($this->night_halt, $create->id);
                $encryptedId = Crypt::encrypt($create->id);
                return redirect()->route('admin.itinerary.query.build', $encryptedId);
            }
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on error
            session()->flash('error', $e->getMessage());
            // session()->flash('error', 'Failed to save itinerary: ' . $e->getMessage());
        }
    }


    public function cloneItineraryDetails($old_itinerary_id, $new_itinerary_id){
        $fetchDetails = ItineraryDetail::where('itinerary_id', $old_itinerary_id)->whereNotNull('route_service_summary_id')->orderBy('id', 'ASC')->get();
        foreach($fetchDetails as $item){
            
                $newEntry = new ItineraryDetail;
                $newEntry->itinerary_id = $new_itinerary_id;
                $newEntry->route_service_summary_id = $item->route_service_summary_id;
                $newEntry->header = $item->header;
                $newEntry->field = $item->field;
                $newEntry->value = $item->value;
                if($item->field=='day_route'){
                    $newEntry->value_quantity = $item->value_quantity;
                }elseif($item->field=='day_sightseeing'){
                    $findData = ServiceWiseSightseeing::where('service_summary_id',$item->route_service_summary_id)->whereHas('sightseeing', function ($q) use ($item) {
                        $q->where('name', $item->value);
                    })->with('sightseeing:id,name,ticket_price')->first();
                    if(isset($findData) && !empty($findData->sightseeing)){
                        $sightseeing = $findData->sightseeing;
                        $newEntry->value_quantity = $this->total_members;
                        $newEntry->price = $sightseeing->ticket_price*$this->total_members;
                    }
                }elseif($item->field=='day_activity'){
                    $findData = ServiceWiseActivity::where('service_summary_id',$item->route_service_summary_id)->whereHas('activity', function ($q) use ($item) {
                        $q->where('name', $item->value);
                    })->with('activity:id,name,price,ticket_price')->first();
                    if(isset($findData) && !empty($findData->activity)){
                        $activity = $findData->activity;
                        $price = $activity->price+$activity->ticket_price;
                        $newEntry->value_quantity = $this->total_members;
                        $newEntry->price = $price*$this->total_members;
                    }
                }
                $newEntry->save();
        }
    }
    public function QuickSearch($value){
        $this->search = $value;
    }
    public function convertQueryToLead($id)
    {
        try {
            // Retrieve lead
            $lead = Lead::findOrFail($id);

            // Update lead fields
            $lead->unique_id = CustomHelper::GenerateUniqueId('leads', 'LTD');
            $lead->generate_from = "lead";
            $lead->created_at = now();
            $lead->updated_at = now();
            $lead->assigned_to_id = Auth::guard('admin')->user()->id;

            $lead->save();

            // Success redirect
            return redirect()
                ->route('admin.leads.index')
                ->with('success', 'Query has been successfully converted to a Lead.');

        } catch (\Exception $e) {

            // Log the error
            \Log::error('Query-to-Lead error: ' . $e->getMessage());

            // Redirect with error message
            session()->flash('success', 'Query successfully converted to Lead.');
        }
    }


    public function render()
    {   
        $this->divisions= City::where('state_id', $this->destination)->orderBy('name', 'ASC')->get();
        $this->getItinerary();
        return view('livewire.itinerary.cost-calculator-query-list');
    }
}
