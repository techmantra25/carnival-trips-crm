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
    public $edit_mode = false;
    public function mount(){
        $this->divisions = City::where('status',1)->orderBy('state_id','ASC')->get();
        $this->destinations = State::where('status',1)->orderBy('name','ASC')->get();
        $this->filter_hotels = NonServicesHotel::orderBy('hotel_name','ASC')->get();
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
            'destination_id' => 'required|exists:states,id',
            'division_id'    => 'required|exists:cities,id',
            'hotel_name'     => 'required|string|max:255',
            'hotel_address'  => 'required|string',
            'image'          => 'nullable|image|max:2048',
        ]);

        $uploadedPath = null;
        if ($this->image) {
            $dynamicText = rand(1111, 9999);
            $uploadedPath = CustomHelper::uploadImage($this->image, 'non-service-hotel', $dynamicText, $this->hotel_name);
        }

        if ($this->edit_mode && $this->hotel_id) {
            // Update
            $hotel = NonServicesHotel::findOrFail($this->hotel_id);
            $set_image = $hotel->image?:'build/assets/images/logo/hotel.jpg';
          
            $hotel->update([
                'city_id'    => $this->division_id,
                'hotel_name' => $this->hotel_name,
                'address'    => $this->hotel_address,
                'image'      => $uploadedPath ?: $set_image,
            ]);
            session()->flash('success', 'Hotel updated successfully!');
        } else {
            // Create
            NonServicesHotel::create([
                'city_id'    => $this->division_id,
                'hotel_name' => $this->hotel_name,
                'address'    => $this->hotel_address,
                'image'      => $uploadedPath,
            ]);
            session()->flash('success', 'Hotel added successfully!');
        }

        $this->ResetFields();
        $this->active_new_hotel_modal = 0;
    }
    public function getDivision($value){
        $this->division_id = $value;
    }
    public function getDestination($value){
        $this->reset(['divisions']);
        $this->destination_id = $value;
        $this->divisions = City::where('status',1)->where('state_id',$value)->get();
        $division_id = $this->divisions->pluck('id')->toArray();
    }
    public function getHotel($value){
        $this->hotel_id = $value;
    }
    public function toggleCreateModal($value){
        $this->ResetFields();
        $this->active_new_hotel_modal = $value;
    }
    public function ResetFields()
    {
        $this->reset(['division_id', 'hotel_id', 'hotel_name', 'hotel_address', 'image', 'destination_id', 'edit_mode']);
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

    public function editHotel($id)
    {
        $hotel = NonServicesHotel::findOrFail($id);

        $this->hotel_id = $hotel->id;
        $this->hotel_name = $hotel->hotel_name;
        $this->hotel_address = $hotel->address;
        $this->division_id = $hotel->city_id;
        $this->destination_id = optional($hotel->city)->state_id; // assuming relation
        $this->divisions = City::where('status',1)->where('state_id',$this->destination_id)->get();
        $this->edit_mode = true;
        $this->active_new_hotel_modal = 1;
        $this->dispatch('FetchContent', ['hotel_name' => $hotel->hotel_name, 'hotel_address'=>$hotel->address]);
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
