<?php

namespace App\Livewire\Hotel;

use Livewire\Component;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\HotelPriceChartType;
use App\Models\HotelPriceChart;
use App\Models\SeasionPlan;
use App\Helpers\CustomHelper;
class HotelPriceChartList extends Component
{
    public $hotels = [];
    public $room_name_list = [];
    public $data = [];
    public $season_main_plan;
    public $season_addon_plan;
    public $selected_hotel_id = [];
    public $selected_room = [];
    public $chart_title = 'Selling Price Chart';
    public $chart_type = 2;
    public $isDisabled = true;
    public function mount(){
        $this->hotels  = $this->GetAllHotel();
        $this->room_name_list  = $this->GetAllRoom();
    }

    public function toggleInputs()
    {
        $this->isDisabled = !$this->isDisabled;
    }
    public function getHotel($hotel_id){
        $this->selected_hotel_id = $hotel_id;
        $this->dispatch('refreshComponent');
    }
    public function getRoom($room_name){
        $this->selected_room = $room_name;
        $this->dispatch('refreshComponent');
    }
    public function GetAllHotel(){
        return Hotel::with('rooms')->where('status', 1)->orderBy('name', 'ASC')->get();
    }
    public function GetAllRoom(){
        return Room::orderBy('positions', 'ASC')
        ->get()
        ->unique('room_name')
        ->values()
        ->pluck('room_name')
        ->toArray();
    }

    public function ChangeType($value){
        $this->chart_title = $value;
        $this->chart_type = $value=="Selling Price Chart"?2:1;
        $this->dispatch('refreshComponent');
    }

    public function hotelSeasonMainPlan(){
        return SeasionPlan::where('status',1)->orderBy('position','ASC')->where('type', 'main')->get()->toArray();
    }
    public function hotelSeasonAddonPlan(){
        return SeasionPlan::where('status',1)->orderBy('position','ASC')->where('type', 'addon')->get()->toArray();
    }
    public function GetAllPrice()
    {
        $this->season_main_plan = $this->hotelSeasonMainPlan();
        $this->season_addon_plan = $this->hotelSeasonAddonPlan();
        $hotel = [];
        $hotel_data = Hotel::query()
        ->when($this->selected_hotel_id, fn($query) => $query->whereIn('id', $this->selected_hotel_id))
        ->with('rooms','DivisionData')
        ->where('status', 1)
        ->orderBy('name', 'ASC')
        ->limit(10)
        ->get();
        foreach($hotel_data as $key=>$item){
            $hotel[$key]= [
                'hotel_id'=>$item->id,
                'hotel_name'=>$item->name,
                'hotel_division'=>optional($item->DivisionData)->name??"N/A",
            ];

            $room_data = Room::query()
            ->when($this->selected_room, fn($query)=>$query->whereIn('room_name', $this->selected_room))
            ->where('hotel_id', $item->id)
            ->orderBy('positions', 'ASC')->get()->toArray();

            // $rooms = optional($room_data)->map(function($room){
            //     return [
            //         'id'=>$room['id'],
            //         'room_name'=>$room['room_name'],
            //     ];
            // });
           
            foreach($room_data as $room_index=>$room_item){
                $hotel[$key]['room'][$room_index]= [
                    'room_id'=>$room_item['id'],
                    'room_name'=>$room_item['room_name'],
                ];
                 // season_prices
                foreach($this->season_main_plan as $mp_index=>$main_plan_item){
                    $main_plan_title = str_replace(' ', '_', strtolower($main_plan_item['title']));
                    $plans = explode(',', $main_plan_item['plan_item']); // ['EPA', 'CP', 'MAP', 'AP']

                    foreach ($plans as $plan) {
                        $plan = trim($plan);
                        $priceInfo =CustomHelper::getHotelPriceChart($item->id, $room_item['id'], $this->chart_title, ucwords($main_plan_item['title']), strtoupper($plan));
                       
                        $price_id = $priceInfo['id'];
                        $item_price = $priceInfo['item_price'];

                        $hotel[$key]['room'][$room_index]['season_prices'][$main_plan_title][$plan] = [
                            'item_id'=>$price_id,
                            'item_price'=>$item_price?env('DEFAULT_CURRENCY_SYMBOL').$item_price:"",
                        ]; // or default value
                    }
                }
                 // addon_prices
                foreach($this->season_addon_plan as $ap_index=>$addon_plan_item){
                    $addon_plan_title = str_replace(' ', '_', strtolower($addon_plan_item['title']));
                    $plans = explode(',', $addon_plan_item['plan_item']); // ['EPA', 'CP', 'MAP', 'AP']
                    foreach ($plans as $plan) {
                        $plan = trim($plan);
                        $priceInfo = CustomHelper::getHotelPriceChart($item->id, $room_item['id'], $this->chart_title, ucwords($addon_plan_item['title']), strtoupper($plan));
                        $price_id = $priceInfo['id'];
                        $item_price = $priceInfo['item_price'];
                        $hotel[$key]['room'][$room_index]['addon_prices'][$addon_plan_title][$plan] = [
                            'item_id'=>$price_id,
                            'item_price'=>$item_price?env('DEFAULT_CURRENCY_SYMBOL').$item_price:"",
                        ]; // or default value
                    }
                }
            }
        }
        // dd($hotel);
        $this->data = $hotel;
    }

    public function ResetPage(){
        return redirect()->route('admin.hotel.price_chart');
    }
    public function render()
    {
        $this->GetAllPrice();
        return view('livewire.hotel.hotel-price-chart-list');
    }
}
