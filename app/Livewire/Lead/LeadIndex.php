<?php

namespace App\Livewire\Lead;

use Livewire\Component;
use App\Models\Lead;
use App\Models\State;
use App\Models\SeasionPlan;
use App\Models\Itinerary;
use App\Models\ItineraryDetail;
use App\Models\Admin;
use App\Models\City;
use App\Models\ServiceWiseSightseeing;
use App\Models\ServiceWiseActivity;
use App\Models\Category;
use App\Helpers\CustomHelper;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use App\Imports\LeadImport;
use App\Models\LeadActivityLog;
use App\Models\LeadUrlShare;
use App\Models\SendedLeadItinerary;
use Maatwebsite\Excel\Facades\Excel;

class LeadIndex extends Component
{   
    use WithPagination,WithFileUploads;
    protected $paginationTheme = 'bootstrap';
    public $active_lead,$common,$filter_package,$filter_source_type,$filter_lead_status = [],$filter,$start_date,$end_date,$selected_member,$selected_status,$selected_itinerary;
    public $leads_status = [], $destinations = [], $search_destination  = [], $members = [];

    public $destination, $selectedType,$search,$meal_type,$customer_name,$total_members,$number_of_adults,$number_of_childs,$source_type,$mobile_number,$arrival_date,$departure_date,$company_name,$whatsapp_number,$email_address,$hotel_category,$package_type,$package_nights,$package_days,$nationality_type,$number_of_rooms,$extra_mattress;
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
    public $childs = [],$sent_lead_itineraries = [];
    public $showImportModal = false;
    public $showAssignLeadModal = false;
    public $showLeadStatusModal = false;
    public $importFile;
    public $leadImportError,$leadAssignError,$authUser;
    public $valid_from_day = 01, $valid_from_month = 01, $valid_to_day = 31,$valid_to_month = 12;
    public function mount(){
        $this->authUser = Auth::guard('admin')->user();

        $this->filter_source_type = request()->get('source_type', null); // fallback if not present

        $this->common = CustomHelper::setHeadersAndTitle('Lead Management', 'Leads');
        
        $this->destinations = State::where('status',1)->orderBy('name','ASC')->get();
        $this->leads_status = DB::table('leads_status')->where('status', 1)->orderBy('position', 'ASC')->get();

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
            ['name' => '2D/1N',  'day' => 2,  'night' => 1],
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
    public function changeLeadType($value){
        $this->filter_source_type = $value;
    }
    public function changePackage($value){
        $this->filter_package = $value;
    }
    public function ResetAllField(){
        $this->reset(['filter_package','filter_source_type', 'filter_lead_status','filter','search_destination','start_date','end_date']);
        $this->dispatch('refreshComponent');
    }
    public function SetFilter($value)
    {
        $this->filter = $value;
    }

    public function toggleDestination($id)
    {
        if (in_array($id, $this->search_destination)) {
            $this->search_destination = array_diff($this->search_destination, [$id]);
        } else {
            $this->search_destination[] = $id;
        }
    }

    public function clearAllDestinations()
    {
        $this->search_destination = [];
    }

    public function changeLeadStatus($value){
        if (in_array($value, $this->filter_lead_status)) {
            $this->filter_lead_status = array_diff($this->filter_lead_status, [$value]);
        } else {
            $this->filter_lead_status[] = $value;
        }
    }
    public function clearAllLeadStatus()
    {
        $this->filter_lead_status = [];
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
        $this->reset(['childs']);
        $this->meal_type = $value;
    }
    public function changeNationalityType($value){
        $this->nationality_type = $value;
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

        // $this->existing_night_halt_details = Itinerary::select('id', 'itinerary_journey')
        // ->whereIn('type', ['preset','query'])
        // ->where('destination_id', $this->destination)
        // ->where('hotel_category', $this->hotel_category)
        // ->where('itinerary_syntax', $this->package_type)
        // ->where('itinerary_journey', 'LIKE', '%' . $value . '%') // filter using LIKE
        // ->orderBy('updated_at', 'DESC')
        // ->get()
        // ->unique('itinerary_journey') // still ensure unique if needed
        // ->values()
        // ->toArray();

        // Fetch all matching itineraries first
        $itineraries = Itinerary::select('id', 'itinerary_journey', 'valid_to')
            ->whereIn('type', ['preset', 'query'])
            ->where('destination_id', $this->destination)
            ->where('hotel_category', $this->hotel_category)
            ->where('itinerary_syntax', $this->package_type)
            ->where('itinerary_journey', 'LIKE', '%' . $value . '%')
            ->orderBy('updated_at', 'DESC')
            ->get();

        // Prepare arrival date as Carbon
        $arrivalDate = Carbon::parse($this->arrival_date);

        // Filter based on valid_from <= arrival_date (using current year)

        $this->existing_night_halt_details = $itineraries
            ->filter(function ($item) use ($arrivalDate) {
                if (!$item->valid_to) {
                    return false;
                }

                try {
                    // Convert valid_from (MM-DD) to full date with current year
                    [$day, $month] = explode('-', $item->valid_to);

                    // Build valid_to full date (Y-m-d) using current year
                    $validTo = Carbon::createFromFormat('Y-m-d', now()->year . '-' . $month . '-' . $day);
                    // Compare: arrivalDate <= validTo
                    return Carbon::parse($arrivalDate)->lessThanOrEqualTo($validTo);
                } catch (\Exception $e) {
                    return false;
                }
            })
            ->unique('itinerary_journey') // Optional uniqueness
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
        $this->validateDaysAndNights();
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
        $this->newPresetError = null;
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
        $this->validateDaysAndNights();
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
        $this->reset(['night_halt','night_halt_details']);
        $this->dispatch('refreshComponent');
        $this->queryDateUpdate($value, $this->package_nights);
    }
    public function changeHotelCategory($value){
        $this->reset(['night_halt','night_halt_details']);
        $this->dispatch('refreshComponent');
        $this->hotel_category = $value;
        $this->selectedCategory = $value;
        $this->validateDaysAndNights();
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
            'childs.*.addon_type.required_if' => 'Type is required.',
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
            
            $lead = new Lead();
            // Assign the validated data to the model's properties
            $lead->unique_id = CustomHelper::GenerateUniqueId('leads', 'LTD');
            $lead->customer_name = ucwords($this->customer_name);
            $lead->customer_email = $this->email_address;
            $lead->generate_from = 'lead';
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
            $lead->team_lead_id = CustomHelper::getNextTeamLeadIdByLeadCount($this->destination);
            $lead->assigned_to_id = $lead->team_lead_id;
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

                // Send Itinerary
                $SharedIds =  CustomHelper::makePresetItineraryLink($lead->id);
                if(count($SharedIds)>0){
                    foreach($SharedIds as $link_index => $linkItem){
                        $LeadUrlShare = LeadUrlShare::find($linkItem);

                        CustomHelper::sendItineraryLinkOnWhatsapp($linkItem);
                        CustomHelper::sendItineraryLinkOnEmail($linkItem);
                    
                        //  Log success
                        LeadActivityLog::create([
                            'lead_id' => $lead->id,
                            'updated_by' => Auth::guard('admin')->user()->id,
                            'message' => json_encode([
                                'action' => 'Itinerary Shared From Create Lead',
                                'shared_link_ids' => $LeadUrlShare?$LeadUrlShare->links:null,
                                'shared_by' => Auth::guard('admin')->user()->name . ' (' . Auth::guard('admin')->user()->email . ')',
                                'timestamp' => now()->toDateTimeString()
                            ])
                        ]);
                    }
                }else{
                    //  Log failure
                    LeadActivityLog::create([
                        'lead_id' => $lead->id,
                        'updated_by' => Auth::guard('admin')->user()->id,
                        'message' => json_encode([
                            'action' => 'Itinerary Share Failed From CRM',
                            'reason' => 'No matching preset itinerary found',
                            'shared_by' => Auth::guard('admin')->user()->name . ' (' . Auth::guard('admin')->user()->email . ')',
                            'timestamp' => now()->toDateTimeString()
                        ])
                    ]);
                }

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

    public function openImportModal(){
        $this->reset('importFile');
        $this->showImportModal = true;
    }
    public function importLeads()
    {
        $this->resetErrorBag();
        $this->leadImportError = null;
        $this->validate([
            'importFile' => 'required|file|mimes:xlsx,csv|max:10048',
        ]);

        try {
            Excel::import(new LeadImport, $this->importFile);
            session()->flash('success', 'Leads imported successfully!');
            $this->showImportModal = false;
            $this->importFile = null;

        } catch (\Exception $e) {
            $this->leadImportError = $e->getMessage();
        }
    }
    public function ChangeDate($value,$type){
        if($type==="start_date"){
            $this->start_date = $value;
        }else{
            $this->end_date = $value;
        }
    }

    public function assignLeadModal($lead_id){
        $this->reset(['active_lead', 'members']);
        $this->showAssignLeadModal = true;
        $this->active_lead = Lead::find($lead_id);

        $this->members = Admin::select('id', 'name')
        ->when(in_array($this->authUser->role, ['team_lead', 'member']), function ($query) {
            $query->where(function ($q) {
                $q->where('team_lead', $this->authUser->id)
                ->orWhere('id', $this->authUser->id); // include team_lead themselves
            });
        })
        ->when(in_array($this->authUser->role, ['super_admin', 'admin']), function ($query) {
            $query->where(function ($q) {
                $q->where('role', 'member')
                ->where('team_lead', $this->active_lead->team_lead_id)
                ->orWhere('id', $this->active_lead->team_lead_id); // include the team_lead
            });
        })
        ->get();

        $this->selected_member = $this->active_lead->assigned_to_id;
        
    }

    public function LeadStatusModal($lead_id){
        $this->reset(['active_lead']);
        $this->showLeadStatusModal = true;
        $this->active_lead = Lead::find($lead_id);
        $this->selected_status = $this->active_lead->status;

        if ($this->selected_status == 'Confirmed') {
            $this->sent_lead_itineraries = SendedLeadItinerary::where('lead_id', $this->active_lead->id)
                ->orderBy('id', 'ASC')
                ->get();
            $this->selected_itinerary = SendedLeadItinerary::where('lead_id', $this->active_lead->id)
                        ->where('is_confirmed', 1)
                        ->value('id');
        }
    }
    public function assignLead(){
        $this->resetErrorBag();
        $this->leadAssignError = null;
        if(!isset($this->selected_member)){
            $this->leadAssignError = "Please choose a member";
            return;
        }
        try {
            $emp = Admin::find($this->selected_member);
            if ($emp) {
                $lead = Lead::find($this->active_lead->id);
                $lead->team_lead_id = $emp->team_lead;
                $lead->assigned_to_id = $emp->id;
                $lead->save();

                // Log the assignment
                LeadActivityLog::create([
                    'lead_id' => $lead->id,
                    'updated_by' =>$this->authUser->id,
                    'message' => json_encode([
                        'action' => 'Lead Assigned',
                        'assigned_to' => $emp->name.'('.$emp->email.')',
                        'team_lead' => optional($emp->leader)->name,
                        'assigned_by' => $this->authUser->name.'('.$this->authUser->email.')',
                        'timestamp' => now()->toDateTimeString()
                    ])
                ]);

                session()->flash('success', 'Lead assigned successfully!');
                $this->showAssignLeadModal = false;
                $this->selected_member = null;
            } else {
                $this->leadAssignError = 'Selected member not found.';
            }
           
        } catch (\Exception $e) {
            $this->leadAssignError = $e->getMessage();
        }
    }
   public function assignLeadStatus()
    {
        // The selected status is already updated via wire:model
        $this->sent_lead_itineraries = SendedLeadItinerary::where('lead_id', $this->active_lead->id)
            ->orderBy('id', 'ASC')
            ->get();
    }
    public function updateLeadStatus(){
        $this->resetErrorBag();
        $this->leadAssignError = null;
        if(!isset($this->selected_status)){
            $this->leadAssignError = "Please choose a status";
            return;
        }
        // leadAssignError
        if ($this->selected_status == 'Confirmed' && !$this->selected_itinerary) {
            $this->leadAssignError = "Please select an itinerary before confirming the status.";
            return;
        }

        DB::beginTransaction(); // ← Transaction start

        try {
            $lead = Lead::find($this->active_lead->id);
            if ($lead) {
                $oldStatus = $lead->status;
                $lead->status = $this->selected_status;
                $lead->save();

                $logMessage = [
                    'action' => 'Status Updated',
                    'previous_status' => $oldStatus,
                    'new_status' => $this->selected_status,
                    'updated_by' => $this->authUser->name . ' (' . $this->authUser->email . ')',
                    'timestamp' => now()->toDateTimeString(),
                ];

                // Reset previous confirmed itinerary (if any)
                $previousConfirmed = SendedLeadItinerary::where('lead_id', $lead->id)
                    ->where('is_confirmed', 1)
                    ->first();

                // If previously confirmed itinerary exists and new status is NOT Confirmed,
                // then add back (restore) the deducted room inventory stock
                if ($previousConfirmed && $this->selected_status !== 'Confirmed') {
                    $previousConfirmed->is_confirmed = 0;
                    $previousConfirmed->confirmed_by = null;
                    $previousConfirmed->confirmed_at = null;
                    $previousConfirmed->save();
                    CustomHelper::updateRoomInventoryStock($previousConfirmed->id, $this->selected_status);
                }

                if ($this->selected_status == 'Confirmed') {
                    // Confirm the newly selected itinerary
                    $SendedLeadItinerary = SendedLeadItinerary::findOrFail($this->selected_itinerary);
                    $SendedLeadItinerary->is_confirmed = 1;
                    $SendedLeadItinerary->confirmed_by = $this->authUser->id;
                    $SendedLeadItinerary->confirmed_at = now()->toDateTimeString();
                    $SendedLeadItinerary->save();
                    $this->selected_itinerary = null;

                    // Log both itineraries
                    $logMessage += [
                        'previous_itinerary' => $previousConfirmed ? [
                            'itinerary_code' => $previousConfirmed->itinerary_code,
                            'syntax' => $previousConfirmed->itinerary_syntax,
                            'total_cost' => $previousConfirmed->total_cost,
                            'destination' => optional($previousConfirmed->destination)->name,
                            'hotel_category' => optional($previousConfirmed->category)->name,
                        ] : null,

                        'new_selected_itinerary' => [
                            'itinerary_code' => $SendedLeadItinerary->itinerary_code,
                            'syntax' => $SendedLeadItinerary->itinerary_syntax,
                            'total_cost' => $SendedLeadItinerary->total_cost,
                            'destination' => optional($SendedLeadItinerary->destination)->name,
                            'hotel_category' => optional($SendedLeadItinerary->category)->name,
                        ],
                    ];
                    CustomHelper::updateRoomInventoryStock($SendedLeadItinerary->id, "Confirmed");
                }

                // Final log write
                LeadActivityLog::create([
                    'lead_id' => $lead->id,
                    'updated_by' => $this->authUser->id,
                    'message' => json_encode($logMessage),
                ]);

                DB::commit(); // ← Transaction commit

                session()->flash('success', 'Lead status updated successfully!');
                $this->reset(['selected_itinerary', 'showLeadStatusModal', 'selected_status']);
            } else {
                $this->leadAssignError = 'Selected lead not found.';
                DB::rollBack(); // rollback on failure
            }

        } catch (\Exception $e) {
            DB::rollBack(); // ← Transaction rollback
            $this->leadAssignError = $e->getMessage();
        }
    }
    public function render()
    {
        $this->divisions= City::where('state_id', $this->destination)->orderBy('name', 'ASC')->get();
        $leads = Lead::
        when($this->filter, function ($query) {
            $searchTerm = '%' . $this->filter . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('customer_name', 'like', $searchTerm)
                ->orWhere('customer_email', 'like', $searchTerm)
                ->orWhere('unique_id', 'like', $searchTerm)
                ->orWhere('customer_mobile', 'like', $searchTerm);
            });
        })
        ->when(count($this->search_destination)>0, function ($query){
            $selected_destination = $this->search_destination;
            $query->whereIn('travel_location',$selected_destination);
        })
        ->when($this->filter_package, function ($query){
            $package_type = $this->filter_package;
            $query->where('package_type',$package_type);
        })
        ->when($this->filter_source_type, function ($query){
            $source_type = $this->filter_source_type;
            $query->where('source_type',$source_type);
        })
        ->when($this->filter_lead_status, function ($query){
            $lead_status = $this->filter_lead_status;
            $query->whereIn('status',$lead_status);
        })
        ->when($this->start_date && $this->end_date, function ($query) {
            $query->whereBetween('created_at', [
                Carbon::parse($this->start_date)->startOfDay(),
                Carbon::parse($this->end_date)->endOfDay()
            ]);
        })
        ->when($this->start_date && !$this->end_date, function ($query) {
            $query->where('created_at', '>=', Carbon::parse($this->start_date)->startOfDay());
        })
        ->when(!$this->start_date && $this->end_date, function ($query) {
            $query->where('created_at', '<=', Carbon::parse($this->end_date)->endOfDay());
        })
        ->when(true, function ($query) {
            $query->where('generate_from', 'lead');
        })
        ->when(in_array($this->authUser->role, ['team_lead', 'member']), function ($query) {
            if ($this->authUser->role === 'team_lead') {
                $query->where('team_lead_id', $this->authUser->id);
            } elseif ($this->authUser->role === 'member') {
                $query->where('assigned_to_id', $this->authUser->id);
            }
        })
        ->orderBy('id', 'DESC')
        ->paginate(10);
        return view('livewire.lead.lead-index',[
            'leads'=>$leads,
        ]);
    }
}
