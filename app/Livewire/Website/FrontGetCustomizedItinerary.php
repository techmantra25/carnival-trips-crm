<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\SendedLeadItinerary;
use App\Models\SendedLeadItineraryDetail;
use Carbon\Carbon;
use App\Models\City;

class FrontGetCustomizedItinerary extends Component
{
    public $title = "Customized Itinerary";
    public $sent_lead_itinerary, $itinerary = [];
    public $day_itinerary = [];
    public function mount($code){
       $this->sent_lead_itinerary = SendedLeadItinerary::where('itinerary_code', $code)
        ->firstOrFail();

        // dd($this->sent_lead_itinerary->lead);
        $this->itinerary = [
            'name' => $this->sent_lead_itinerary->lead->customer_name ?? 'N/A',
            'mobile' => $this->sent_lead_itinerary->lead->customer_mobile ?? 'N/A',
            'email' => $this->sent_lead_itinerary->lead->customer_email ?? 'N/A',
            'travel_dates' => Carbon::parse($this->sent_lead_itinerary->lead->arrival_date)->format('d M Y') . 
                            ' to ' . 
                            Carbon::parse($this->sent_lead_itinerary->lead->departure_date)->format('d M Y'),
            'travel_duration' => $this->sent_lead_itinerary->lead->travel_duration_text ?? 'N/A',
            'arrival_date' => Carbon::parse($this->sent_lead_itinerary->lead->arrival_date)->format('d M Y'),
            'departure_date' => Carbon::parse($this->sent_lead_itinerary->lead->departure_date)->format('d M Y'),
            'number_of_adults' => $this->sent_lead_itinerary->lead->number_of_adults ?? 0,
            'number_of_children' => $this->sent_lead_itinerary->lead->number_of_children ?? 0,
            'children_data' => json_decode($this->sent_lead_itinerary->lead->children_data, true) ?? [],
            'number_of_travellor' => $this->sent_lead_itinerary->lead->number_of_travellor ?? 0,
            'number_of_rooms' => $this->sent_lead_itinerary->lead->number_of_rooms ?? 0,
            'extra_mattress' => $this->sent_lead_itinerary->lead->extra_mattress ?? 'N/A',
            'meal_type' => $this->sent_lead_itinerary->lead->meal_type ?? 'N/A',
        ];
        
        $stay_by_journey = explode(',',$this->sent_lead_itinerary->stay_by_journey);
        foreach($stay_by_journey as $key=>$item){
            $index = $key+1;
            $division = City::findOrFail($item);
            $this->day_itinerary[$index] = [
                'day'=>$index,
                'division'=>$division->name,
                'route'=> $this->getRoute($index,$this->sent_lead_itinerary->id),
                'cab'=> SendedLeadItineraryDetail::where('sended_lead_itinerary_id', $this->sent_lead_itinerary->id)
                            ->where('field', 'per_day_cab')
                            ->where('header', 'day_' . $index)
                            ->get(['value as name', 'value_quantity as quantity', 'price as total_price'])
                            ->toArray(),
                'hotel' => SendedLeadItineraryDetail::where('sended_lead_itinerary_id', $this->sent_lead_itinerary->id)
                            ->where('field', 'day_hotel')
                            ->where('header', 'day_' . $index)
                            ->select('value as name')
                            ->first()?->toArray() ?? ['name' => null],

                'hotel_room' => SendedLeadItineraryDetail::where('sended_lead_itinerary_id', $this->sent_lead_itinerary->id)
                            ->where('field', 'day_room')
                            ->where('header', 'day_' . $index)
                            ->select('value as name')
                            ->first()?->toArray() ?? ['name' => null],

                'pax_with_adults' => SendedLeadItineraryDetail::where('sended_lead_itinerary_id', $this->sent_lead_itinerary->id)
                            ->where('field', 'day_room_main_plan')
                            ->where('header', 'day_' . $index)
                            ->select('value as name', 'value_quantity as quantity', 'price as total_price')
                            ->first()?->toArray() ?? ['name' => null, 'quantity' => 0, 'total_price' => 0],
                'cwnb'=>SendedLeadItineraryDetail::where('sended_lead_itinerary_id', $this->sent_lead_itinerary->id)
                            ->where('field', 'day_room_addon_plan_cwnb')
                            ->where('header', 'day_' . $index)
                            ->get(['value as name','value_quantity as quantity', 'price as total_price'])
                            ->toArray(),
                'cwm'=>SendedLeadItineraryDetail::where('sended_lead_itinerary_id', $this->sent_lead_itinerary->id)
                            ->where('field', 'day_room_addon_plan_cwm')
                            ->where('header', 'day_' . $index)
                            ->get(['value as name','value_quantity as quantity', 'price as total_price'])
                            ->toArray(),
                'extra_mattress'=>SendedLeadItineraryDetail::where('sended_lead_itinerary_id', $this->sent_lead_itinerary->id)
                            ->where('field', 'day_room_addon_plan_mattress')
                            ->where('header', 'day_' . $index)
                            ->get(['value as name','value_quantity as quantity', 'price as total_price'])
                            ->toArray(),
            ];
        }
        
    }
    protected function getRoute($index, $sended_lead_itinerary_id){
        $data = SendedLeadItineraryDetail::where('sended_lead_itinerary_id',$sended_lead_itinerary_id)->where('field','day_route')->where('header', 'day_'.$index)->get();
        $routeWiseData = [];
        foreach($data as $k => $item){
            $routeWiseData[$k] = [
                'name' => $item->value,
                'cabs' => SendedLeadItineraryDetail::where('sended_lead_itinerary_id', $sended_lead_itinerary_id)
                            ->where('field', 'day_cab')
                            ->where('header', 'day_' . $index)
                            ->get(['value as name', 'value_quantity as quantity', 'price as total_price', 'rate as piece_price'])
                            ->toArray(),
                'activitys' => SendedLeadItineraryDetail::where('sended_lead_itinerary_id', $sended_lead_itinerary_id)
                            ->where('field', 'day_activity')
                            ->where('header', 'day_' . $index)
                            ->get(['value as name', 'value_quantity as quantity', 'price as total_price', 'rate as piece_price','ticket_price'])
                            ->toArray(),
                'sightseeings' => SendedLeadItineraryDetail::where('sended_lead_itinerary_id', $sended_lead_itinerary_id)
                            ->where('field', 'day_sightseeing')
                            ->where('header', 'day_' . $index)
                            ->get(['value as name', 'value_quantity as quantity', 'price as total_price', 'rate as piece_price','ticket_price'])
                            ->toArray(),
            ];
        }
        return $routeWiseData;
    }
    public function render()
    {
        return view('livewire.website.front-get-customized-itinerary')->layout('layouts.frontend.master', ['title' => $this->title]);
    }
}
