<?php

namespace App\Livewire\Itinerary;

use Livewire\Component;
use App\Models\State;
use App\Models\City;
use App\Models\Lead;
use App\Models\Itinerary;
use App\Models\Category;
use App\Helpers\CustomHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Livewire\WithPagination;

class PresetItineraryList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // or 'tailwind'
    public $desitinations =[];
    public $divisions =[];
    public $categories =[];
    public $selectedDestination = null;
    public $selectedCategory =null;
    public $search ="";
    public $selectedType ='preset';
    public $active_assign_new_modal = 0;
    public $active_night_distribution = 0;
    public $day;
    public $night;
    public $night_sum =0;
    public $night_distribution;
    public $newPresetError = '';
    public $itinerary_journey = [];
    public $preset_itineraries = [];
    public $itinerary_journey_divisions = [];
    public $leadData;
    public $travel_duration_text, $lead_id, $valid_from_day,$valid_from_month,$valid_to_day,$valid_to_month;
    public function mount($leadId = null){
        if($leadId){
            $id = Crypt::decrypt($leadId);
            $leadData = Lead::find($id);
            if(!$leadData){
                abort(404, 'Itinerary not found.');
            }

            $existing_itineraries = Itinerary::where('itinerary_syntax',$leadData->travel_duration_text)->where('destination_id', $leadData->travel_location)->first();
          
            if(!$existing_itineraries){
                $this->search = $leadData->travel_duration_text;
                $this->selectedDestination = $leadData->travel_location;
                $this->day = $leadData->travel_in_days;
                $this->night = $leadData->travel_in_nights;
                $this->active_assign_new_modal = 1;
                $this->getDestination($leadData->travel_location);
                $this->lead_id = $id;
                $this->travel_duration_text = $leadData->travel_duration_text;
                $this->newPresetError = 'Sorry! There is no package added for ' . ($leadData->travel_duration_text ?? 'this duration') . ' & fill the data.';
            }else{
                $Itinerary = Itinerary::updateOrCreate(
                    [
                        'lead_id' => $id,
                        'destination_id' => $existing_itineraries->destination_id,
                        'hotel_category' => $existing_itineraries->hotel_category,
                        'total_days' => $existing_itineraries->total_days,
                        'total_nights' => $existing_itineraries->total_nights,
                        'itinerary_journey' => $existing_itineraries->itinerary_journey,
                    ],
                    [
                        'type' => 'post_inquiry',
                        'itinerary_syntax' => $existing_itineraries->itinerary_syntax,
                        'night_journey' => $existing_itineraries->night_journey,
                        // 'divisions_journey' => $existing_itineraries->divisions_journey,
                        'stay_by_journey' => $existing_itineraries->stay_by_journey,
                    ]
                );
                $itinerary_id = Crypt::encrypt($Itinerary->id);
                return redirect()->route('admin.itinerary.preset.build', $itinerary_id);
            }
        
        }
        
        $this->desitinations = State::where('status', 1)->orderBy('name', 'ASC')->get();
        $this->categories = Category::where('status', 1)->orderBy('name', 'ASC')->get();
        // $this->preset_itineraries = $this->getItinerary();
    }


    public function NewPresetItinerary($value){
        $this->day = "";
        $this->reset(['day', 'night', 'night_sum', 'night_distribution']);
        $this->active_assign_new_modal = $value=="yes"?1:0;
        $this->dispatch('refreshComponent');
    }
    public function getDestination($value){
        $this->selectedDestination = $value;
        $this->divisions= City::where('state_id', $value)->orderBy('name', 'ASC')->get();
        $this->active_night_distribution = 1;
        $this->validateDaysAndNights($this->day);
        // $this->preset_itineraries = $this->getItinerary();
    }
    public function GetCategory($value){
        $this->selectedCategory = $value;
        $this->active_night_distribution = 1;
        $this->validateDaysAndNights($this->day);
        // $this->preset_itineraries = $this->getItinerary();
    }

    public function QuickSearch($value){
        $this->search = $value;
        // $this->preset_itineraries = $this->getItinerary();
    }
    public function ResetData(){
        $this->search = '';
    }
    public function validateDaysAndNights($value){

        if (!preg_match('/^\d+(\s\d+)*$/', $value)) {
            $this->newPresetError = 'Please enter a valid numeric value.';
            $this->reset(['itinerary_journey', 'itinerary_journey_divisions']);
            return;
        }

        if (!$this->selectedDestination) {
            $this->newPresetError = 'Please select destination first.';
            return;
        } elseif (!$this->selectedCategory) {
            $this->newPresetError = 'Please select category first.';
            return;
        } elseif ($this->day < $this->night) {
            $this->newPresetError = 'Days (D) must be greater than Nights (N).';
            $this->active_night_distribution = 0;
            $this->reset(['night_distribution']);
            return;
        } elseif ($this->night != ($this->day - 1)) {
            $this->newPresetError = 'Nights (N) should be exactly one less than Days (D).';
            $this->active_night_distribution = 0;
            $this->reset(['night_distribution']);
            return;
        } else {
            $this->newPresetError = "";
            $this->active_night_distribution = 1;

            // Generate array of day numbers
            $numbers = [];
            for ($i = 1; $i <= $this->day; $i++) {
                $numbers[] = $i;
            }

            $this->itinerary_journey = $numbers;
        }
    }
    public function updateJourneyDivision($index, $value){
        $last_index = $index+1;
        $this->itinerary_journey_divisions[$index] = $value;
        if($last_index !== count($this->itinerary_journey)){
            $this->night_distribution = CustomHelper::formatDayJourney($this->itinerary_journey_divisions);
        }
    }

    public function submitForm()
    {
        if(!$this->selectedDestination){
            $this->newPresetError = 'Please select destination first.';
            return;
        }elseif(!$this->selectedCategory){
            $this->newPresetError = 'Please select category first.';
            return;
        }elseif ($this->day < $this->night || $this->day==0) {
            $this->newPresetError = 'Days (D) must be greater than Nights (N).';
            $this->active_night_distribution = 0;
            $this->reset(['night_distribution']);
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
        } catch (\Exception $e) {
            $this->newPresetError = 'Invalid date format selected.';
            return;
        }

        // $valid_from_day,$valid_from_month,$valid_to_day,$valid_to_month
        DB::beginTransaction();
        try {
           // Convert arrays to string format
            $itinerary_journey = implode(',', $this->itinerary_journey);
            
            $itinerary_journey_divisions = implode(',', $this->itinerary_journey_divisions);
         
            $formattedString = $this->night_distribution;
            // Find existing record or create a new one
            $itinerary_syntax = $this->day.'D/'.$this->night.'N';
            $create = Itinerary::updateOrCreate(
                [
                    'destination_id' => $this->selectedDestination, 
                    'hotel_category' => $this->selectedCategory,
                    'total_days' => $this->day,
                    'total_nights' => $this->night,
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
            if($this->lead_id){
                $lead_id = Crypt::encrypt($this->lead_id);
                return redirect()->route('admin.itinerary.preset.list',['lead_id'=>$lead_id]);
            }
            $this->reset(['itinerary_journey', 'itinerary_journey_divisions','valid_from_day', 'valid_from_month','valid_to_day','valid_to_month']);
            $this->active_assign_new_modal = 0;
            // $this->preset_itineraries = $this->getItinerary();
            
            session()->flash('success', 'Itinerary saved successfully!');
         
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on error
            $this->newPresetError = $e->getMessage();
            // session()->flash('error', 'Failed to save itinerary: ' . $e->getMessage());
        }
    }
    
    public function updated($property)
    {
        if (in_array($property, [
            'selectedDestination',
            'selectedCategory',
            'selectedType',
            'search'
        ])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $presetitineraries = Itinerary::query()
            ->when($this->selectedDestination, function ($query) {
                $query->where('destination_id', $this->selectedDestination); // Use '=' for exact match
            })
            ->when($this->selectedCategory, function ($query) {
                $query->where('hotel_category', $this->selectedCategory); // Use '=' for exact match
            })
            ->when($this->selectedType, function ($query) {
                $query->where('type', $this->selectedType); // Use '=' for exact match
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('itinerary_syntax', 'like', "%{$this->search}%")
                      ->orWhere('itinerary_journey', 'like', "%{$this->search}%")
                      ->orWhere('type', 'like', "%{$this->search}%");
                });
            })
            // ->orderBy('id', 'DESC')
            ->orderBy('total_days', 'DESC')
            ->paginate(10); //
        return view('livewire.itinerary.preset-itinerary-list', [
            'presetitineraries' => $presetitineraries,
        ]);
    }
}
