<?php

namespace App\Livewire\Hotel;

use Livewire\Component;
use App\Models\NonServicesHotel;
use App\Models\City;
use App\Models\State;
use Livewire\WithFileUploads;
use App\Helpers\CustomHelper;

class NonServiceHotelList extends Component
{
    use WithFileUploads;
    public $hotels = [];
    public $filter_hotels = [];
    public $divisions = [];
    public $destinations = [];
    public $division_id = '';
    public $destination_id = '';
    public $hotel_id = '';
    public $hotel_name, $hotel_address, $image;
    public $active_new_hotel_modal = 0;
    public function mount(){
        $this->divisions = City::where('status',1)->orderBy('state_id','ASC')->get();
        $this->destinations = State::where('status',1)->orderBy('name','ASC')->get();
    }
    public function updatedImage()
    {
        // Optional: validate or resize images here
        $this->validate([
            'image' => 'image|max:2048', // max 2MB each
        ]);
    }
     public function saveHotel()
    {
        $this->validate([
            'destination_id'   => 'required|exists:states,id',
            'division_id'   => 'required|exists:cities,id',
            'hotel_name'    => 'required|string|max:255',
            'hotel_address' => 'required|string',
            'image'       => 'nullable|image|max:2048',
        ]);

        $uploadedPath = null;
        if (isset($this->image)) {
            // Generate a unique filename
            $dynamicText = rand(1111, 9999);
            $uploadedPath = CustomHelper::uploadImage($this->image, $dynamicText, $this->hotel_name, 'non-service-hotel');
        }
        $hotel = NonServicesHotel::create([
            'city_id'   => $this->division_id,
            'hotel_name'          => $this->hotel_name,
            'address'       => $this->hotel_address,
            'image'       => $uploadedPath,
        ]);


        session()->flash('success', 'Hotel added successfully!');
        $this->ResetFields();
        $this->active_new_hotel_modal = 0;
    }
    public function getDivision($value){
        $this->division_id = $value;
        $this->filter_hotels = NonServicesHotel::where('city_id',$value)->get();
    }
    public function getDestination($value){
        $this->reset(['divisions']);
        $this->destination_id = $value;
        $this->divisions = City::where('status',1)->where('state_id',$value)->get();
        $division_id = $this->divisions->pluck('id')->toArray();
        $this->filter_hotels = NonServicesHotel::whereIn('city_id',$division_id)->get();
    }
    public function getHotel($value){
        $this->hotel_id = $value;
    }
    public function toggleCreateModal($value){
        $this->ResetFields();
        $this->active_new_hotel_modal = $value;
    }
    public function ResetFields(){
        $this->reset(['division_id','hotel_id','filter_hotels','hotel_name','hotel_address','image','destination_id']);
    }
    public function removeExistingHotel($room_id){
        $this->dispatch('showConfirm', ['itemId' => $room_id]);
    }
    public function deleteItem($id){
        $hotel = NonServicesHotel::find($id);
        if ($hotel) {
            $hotel->delete();
            session()->flash('success', 'hotel deleted successfully.');
        } else {
            session()->flash('error', 'hotel not found.');
        }
    }
    public function render()
    {
         $this->hotels = NonServicesHotel::when($this->division_id, function ($query) {
            $query->where('city_id', $this->division_id);
        })->when($this->hotel_id, function ($query){
            $query->where('id',$this->hotel_id);
        })->get();
        return view('livewire.hotel.non-service-hotel-list',[
            'hotels' => $this->hotels,
        ]);
    }
}
