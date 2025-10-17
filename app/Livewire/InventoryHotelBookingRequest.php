<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\HotelAvailabilityRequest;
use App\Models\City;
use App\Models\Hotel;
use Carbon\Carbon;
use App\Helpers\CustomHelper;
use App\Repositories\CommonRepository;

class InventoryHotelBookingRequest extends Component
{
    public $destinations = [];
    public $divisions = [];
    public $hotelCategories = [];
    public $hotels = [];
    public $availabilityRequests  = [];
    public $selectedDestination,$selectedDivision,$selectedCategory,$selectedHotel;
    protected $commonRepository;
    public function mount(CommonRepository $commonRepository){
        $this->commonRepository = $commonRepository;
        $this->destinations = $this->commonRepository->getAllActiveState();
    }
    public function GetDivisions($destination_id){
        $this->selectedDestination = $destination_id;
        $this->reset(['selectedDivision', 'selectedCategory','selectedHotel']);
    }
    public function changeCategory($category_id){
        $this->selectedCategory = $category_id;
        $this->reset(['selectedHotel']);
    }
    public function changeDivision($division_id){
        $this->selectedDivision = $division_id;
        $this->reset(['selectedCategory', 'selectedHotel']);
    }
    public function changeHotel($hotel_id){
        $this->selectedHotel = $hotel_id;
    }
    public function render()
    {
        $this->divisions = City::where('status', 1)
            ->where('state_id', $this->selectedDestination)
            ->pluck('name', 'id')
            ->toArray();

       $this->hotelCategories = Hotel::query()
        ->when($this->selectedDestination, function ($query) {
            $query->where('destination', $this->selectedDestination);
        })
        ->when($this->selectedDivision, function ($query) {
            $query->where('division', $this->selectedDivision);
        })
        ->join('categories', 'hotel_category', '=', 'categories.id')
        ->select('categories.id', 'categories.name')
        ->distinct()
        ->orderBy('categories.name', 'ASC')
        ->pluck('categories.name', 'categories.id')
        ->toArray();

        // Get hotels dynamically based on filters
        $this->hotels = Hotel::query()
            ->whereHas('bookingRequest')
            ->when($this->selectedDestination, function ($query) {
                $query->where('destination', $this->selectedDestination);
            })
            ->when($this->selectedCategory, function ($query) {
                $query->where('hotel_category', $this->selectedCategory);
            })
            ->when($this->selectedDivision, function ($query) {
                $query->where('division', $this->selectedDivision);
            })
            ->orderBy('name', 'ASC')
            ->pluck('name', 'id')
            ->toArray();

        $this->availabilityRequests  = HotelAvailabilityRequest::where('hotel_id', $this->selectedHotel)->orderBy('id', 'DESC')->get();
        return view('livewire.inventory-hotel-booking-request');
    }
}
