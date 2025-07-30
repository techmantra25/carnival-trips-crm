<?php

namespace App\Livewire\Hotel;

use Livewire\Component;
use App\Models\Hotel;
use App\Models\City;
use App\Models\Category;

class HotelPrioritySort extends Component
{
    public $hotels = [];
    public $divisions = [];
    public $hotel_categories = [];
    public $selected_divisions = [];
    public $selected_categories = [];
    public $data = [];
    public $priorityData = [];

    public function mount(){
        $this->divisions = City::select('id', 'name')->get();
        $this->hotels = Hotel::select('id', 'name')->where('priority', 0)->get();
        $this->hotel_categories = Category::select('id', 'name')->get();
    }
    public function loadHotels(){

        $divisions = collect($this->selected_divisions)->flatten()->toArray();
        $selected_categories = collect($this->selected_categories)->flatten()->toArray();

        $this->data = City::query()
        ->whereHas('priority_hotels', function($hotelQuery) use ($divisions, $selected_categories){
            $hotelQuery
            ->when($divisions, function($q) use ($divisions){
                $q->whereHas('DivisionData', function($dq) use ($divisions){
                    $dq->whereIn('id', $divisions);
                });
            })
            ->when($selected_categories, function($q) use ($selected_categories){
                $q->whereHas('CategoryData', function($cq) use($selected_categories){
                    $cq->whereIn('id', $selected_categories);
                });
            });
        })
      ->with(['priority_hotels.DivisionData', 'priority_hotels.CategoryData'])->orderBy('name', 'ASC')->get();

    }
    public function getHotel($value){
        {
            $Hotel = Hotel::find($value);
        
            if (!$Hotel) {
                // Handle the case when hotel is not found
                session()->flash('error', 'Hotel not found.');
                return false;
            }
            if ($Hotel->priority>0) {
                // Handle the case when hotel is not found
                session()->flash('error', 'Hotel priority already asssigned.');
                return false;
            }
        
            // Count existing hotels with same division & category and priority > 0
            $count_existing_data = Hotel::where('division', $Hotel->division)
                ->where('hotel_category', $Hotel->hotel_category)
                ->where('priority', '>', 0)
                ->count();
        
            // Set new priority
            $Hotel->priority = $count_existing_data + 1;
        
            // Save the updated hotel
            $Hotel->save();
            session()->flash('success', 'Hotel priority updated successfully');
            // return response()->json(['message' => 'Hotel priority updated successfully.', 'priority' => $Hotel->priority]);
        }
    }
    

    public function getHotelCategory($value){
        $this->selected_categories = $value;
    }
    public function getDivision($value){
        $this->selected_divisions = $value;
    }

    public function ResetPage(){
        $this->reset(['selected_divisions', 'selected_categories']);
    }

    public function updatePriorityOrder($categoryId, $orderedIds){   
        foreach ($orderedIds as $index => $hotelId) {
            Hotel::where('id', $hotelId)->update(['priority' => $index + 1]);
        }
    }
    public function render()
    {
        $this->loadHotels();
        return view('livewire.hotel.hotel-priority-sort');
    }
}
