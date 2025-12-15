<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\SendedLeadItinerary;
use Illuminate\Support\Facades\Request;
use App\Models\SendedLeadItineraryDetail;
use Carbon\Carbon;
use App\Models\City;
use App\Models\LeadUrlShare;
use App\Models\LeadUrlClick;

class FrontGetCustomizedItinerary extends Component
{
    public $title = "Customized Itinerary";
    public $sent_lead_itinerary, $itinerary = [],$clickLogId,$lead_url_share,$leadData;
    public $day_itinerary = [];
    public $day_wise_amount_data = [];
    public $total_amount = 0;
    public function mount($code){
       $this->sent_lead_itinerary = SendedLeadItinerary::where('itinerary_code', $code)
        ->firstOrFail();
        $this->leadData = $this->sent_lead_itinerary->lead;
        $this->lead_url_share = LeadUrlShare::where('sended_lead_itinerary_id', $this->sent_lead_itinerary->id)->firstOrFail();
        $this->itinerary = [
            'name' => $this->sent_lead_itinerary->lead->customer_name ?? 'N/A',
            'mobile' => isset($this->sent_lead_itinerary->lead) 
            ? $this->sent_lead_itinerary->lead->country_code . $this->sent_lead_itinerary->lead->customer_mobile
            : 'N/A',

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
            'destination' => $this->sent_lead_itinerary->destination->name ?? 'N/A',
            'inclusions' => SendedLeadItineraryDetail::where('sended_lead_itinerary_id', $this->sent_lead_itinerary->id)
                            ->where('field', 'like', 'inclusions_%')
                            ->where('header', 'inclusion_exclusions')
                            ->pluck('value')
                            ->toArray(),
            'exclusions' => SendedLeadItineraryDetail::where('sended_lead_itinerary_id', $this->sent_lead_itinerary->id)
                ->where('field', 'like', 'exclusions_%')
                ->where('header', 'inclusion_exclusions')
                ->pluck('value')
                ->toArray(),
        ];
        $stay_by_journey = explode(',',$this->sent_lead_itinerary->stay_by_journey);
        
        $startDate = Carbon::parse($this->leadData->arrival_date);
        $endDate   = Carbon::parse($this->leadData->departure_date);
        foreach($stay_by_journey as $key=>$item){
            $index = $key+1;

            $currentDate = $startDate->copy()->addDays($key)->format('d-m-Y');
            // If exceeds arrival date, keep arrival date or blank
            if ($currentDate > $endDate->format('d-m-Y')) {
                $currentDate = $endDate->format('d-m-Y');
            }

            $division = City::findOrFail($item);
            $hotel_detail = SendedLeadItineraryDetail::where('sended_lead_itinerary_id', $this->sent_lead_itinerary->id)
            ->where('field', 'day_hotel')
            ->where('header', 'day_' . $index)
            ->first();

            $this->day_itinerary[$index] = [
                'day'=>$index,
                'division'=>$division->name,
                'division_date'=>$currentDate,
                'route'=> $this->getRoute($index,$this->sent_lead_itinerary->id),
                'cab'=> SendedLeadItineraryDetail::where('sended_lead_itinerary_id', $this->sent_lead_itinerary->id)
                            ->where('field', 'per_day_cab')
                            ->where('header', 'day_' . $index)
                            ->get(['value as name', 'value_quantity as quantity', 'price as total_price'])
                            ->toArray(),
                'hotel' =>  $hotel_detail?->hotel?->toArray() ?? null,

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
                'cnb'=>SendedLeadItineraryDetail::where('sended_lead_itinerary_id', $this->sent_lead_itinerary->id)
                            ->where('field', 'day_room_addon_plan_cnb')
                            ->where('header', 'day_' . $index)
                            ->get(['value as name','value_quantity as quantity', 'price as total_price'])
                            ->toArray(),
                'cwm'=>SendedLeadItineraryDetail::where('sended_lead_itinerary_id', $this->sent_lead_itinerary->id)
                            ->where('field', 'day_room_addon_plan_cwm')
                            ->where('header', 'day_' . $index)
                            ->get(['value as name','value_quantity as quantity', 'price as total_price'])
                            ->toArray(),
                'extra_mattress'=>SendedLeadItineraryDetail::where('sended_lead_itinerary_id', $this->sent_lead_itinerary->id)
                            ->where('field', 'day_room_addon_plan_extra_mattress')
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
                'image' => $item->value,
                'cabs' => SendedLeadItineraryDetail::with('cab')
                            ->where('sended_lead_itinerary_id', $sended_lead_itinerary_id)
                            ->where('route_service_summary_id', $item->route_service_summary_id)
                            ->where('field', 'day_cab')
                            ->where('header', 'day_' . $index)
                            ->get()
                            ->map(function ($item) {
                                return [
                                    'name'        => $item->value,
                                    'quantity'    => $item->value_quantity,
                                    'total_price' => $item->price,
                                    'piece_price' => $item->rate,
                                    'image'       => optional($item->cab)->image 
                                        ? asset($item->cab->image)
                                        : asset('assets/img/cab.png'),
                                ];
                            })
                            ->toArray(),

                'activitys' => SendedLeadItineraryDetail::with(['activity.firstImage'])
                            ->where('sended_lead_itinerary_id', $sended_lead_itinerary_id)
                            ->where('route_service_summary_id', $item->route_service_summary_id)
                            ->where('field', 'day_activity')
                            ->where('header', 'day_' . $index)
                            ->get()
                            ->map(function ($item) {
                                return [
                                    'name'        => $item->value,
                                    'quantity'    => $item->value_quantity,
                                    'total_price' => $item->price,
                                    'piece_price' => $item->rate,
                                    'ticket_price'=> $item->ticket_price,
                                    'image'       => optional($item->activity->firstImage)->file_path
                                        ? asset($item->activity->firstImage->file_path)
                                        : asset('assets/img/default.jpg'),
                                ];
                            })
                            ->toArray(),

                'sightseeings' => SendedLeadItineraryDetail::where('sended_lead_itinerary_id', $sended_lead_itinerary_id)
                ->where('route_service_summary_id', $item->route_service_summary_id)
                            ->where('field', 'day_sightseeing')
                            ->where('header', 'day_' . $index)
                            ->get(['value as name', 'value_quantity as quantity', 'price as total_price', 'rate as piece_price','ticket_price'])
                            ->toArray(),
            ];
        }
        return $routeWiseData;
    }

    public function incrementClick()
    {
        if ($this->clickLogId) {
            LeadUrlClick::where('id', $this->clickLogId)->increment('click_count');
        }
    }

     public function setExitTime()
    {
        if ($this->clickLogId) {
            LeadUrlClick::where('id', $this->clickLogId)->update([
                'exit_time' => now()->toDateTimeString()
            ]);
        }
    }

    public function startNewClickLog()
    {
        // Close old session if still active
        if ($this->clickLogId) {
            LeadUrlClick::where('id', $this->clickLogId)->update([
                'exit_time' => now()->toDateTimeString()
            ]);
        }

        // Start new session
        $log = LeadUrlClick::create([
            'lead_id' => $this->sent_lead_itinerary->lead_id,
            'lead_url_share_id' => $this->lead_url_share->id,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'referrer' => Request::server('HTTP_REFERER'),
            'entry_time' => now()->toDateTimeString(),
        ]);

        $this->clickLogId = $log->id;
    }
    public function closeClickLog()
    {
        if ($this->clickLogId) {
            LeadUrlClick::where('id', $this->clickLogId)->update([
                'exit_time' => now()->toDateTimeString()
            ]);

            $this->clickLogId = null; // reset for next session
        }
    }
    public function updateExitTime()
    {
        if ($this->clickLogId) {
            LeadUrlClick::where('id', $this->clickLogId)->update([
                'exit_time' => now()->toDateTimeString()
            ]);
        }
    }
    public function GetAllQuantity(){
        $stay_by_journey = explode(',',$this->sent_lead_itinerary->stay_by_journey);
        if(count($stay_by_journey)>0){
            foreach($stay_by_journey as $k=>$journey){
              
                $this->day_wise_amount_data[$k+1] = SendedLeadItineraryDetail::where('sended_lead_itinerary_id', $this->sent_lead_itinerary->id)
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
            }
        }
    }
    public function render()
    {
         $this->GetAllQuantity();
        //  dd($this->day_wise_amount_data);
        $this->total_amount = SendedLeadItineraryDetail::where('sended_lead_itinerary_id', $this->sent_lead_itinerary->id)->sum('price');
        return view('livewire.website.front-get-customized-itinerary')->layout('layouts.frontend.master2', ['title' => $this->title]);
    }
}
