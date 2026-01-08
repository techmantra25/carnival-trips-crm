<?php

namespace App\Livewire\Lead;

use Livewire\Component;
use App\Models\SendedLeadItinerary;
use Illuminate\Support\Facades\Request;
use App\Models\SendedLeadItineraryDetail;
use App\Models\ItineraryTemplate;
use App\Models\City;
use App\Models\ItineraryTemplateDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
class FinalQuotationPdf extends Component
{
    public $sent_lead_itinerary,$leadData,$total_amount,$destinationName;
    public $first_page_bg_image;
    public $second_page_bg_image;
    public $third_page_bg_image;
    public $itinerary = [];
    public $hotel_summary = null;
    public $day_itinerary = [];
    public function mount($code){
       $this->sent_lead_itinerary = SendedLeadItinerary::where('itinerary_code', $code)
        ->firstOrFail();
        $this->leadData = $this->sent_lead_itinerary->lead;

        $destinationTemplate = ItineraryTemplate::where('destination_id', $this->leadData->travel_location)->first();
        $this->destinationName = optional($this->leadData->destination)->name ?? 'this destination';
        if(!$destinationTemplate){
            session()->flash(
                'text_error',
                "Please setup itinerary template for {$this->destinationName}."
            );
        }

        $templateFirstImage = ItineraryTemplateDetail::where('itinerary_template_id', $destinationTemplate->id)->where('header', 'banner_section')->where('field', 'main_banner')->first();
        $this->first_page_bg_image = $templateFirstImage?$templateFirstImage->value:"assets/final_quotation_images/logo8.jpg";

        $templateSecondImage = ItineraryTemplateDetail::where('itinerary_template_id', $destinationTemplate->id)->where('header', 'great_experience')->where('field', 'great_experience_image')->first();
        $this->second_page_bg_image = $templateSecondImage?$templateSecondImage->value:asset('assets/final_quotation_images/logo9.jpg');

        $templatethirdImage = ItineraryTemplateDetail::where('itinerary_template_id', $destinationTemplate->id)->where('header', 'about_destination')->where('field', 'about_destination_image')->first();
        $this->third_page_bg_image = $templatethirdImage?$templatethirdImage->value:asset('assets/final_quotation_images/logo9.jpg');

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
            'trip_highligts' => SendedLeadItineraryDetail::where('sended_lead_itinerary_id', $this->sent_lead_itinerary->id)
                ->where('field', 'like', 'trip_highlights_%')
                ->where('header', 'about_destination')
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

            $this->day_itinerary[$index] = [
                'day'=>$index,
                'division'=>$division->name,
                'division_date'=>$currentDate,
                'route'=> $this->getRoute($index,$this->sent_lead_itinerary->id),
            ];
        }

        // HOTEL
        // remove last index
        $arrivalDate = Carbon::parse($this->leadData->arrival_date);
        array_pop($stay_by_journey);
        // count occurrences
        $divisions = [];
        foreach ($stay_by_journey as $index => $value) {
            if (!isset($divisions[$value])) {
                $divisions[$value] = [
                    'division' => (int) $value,
                    'division_day' => 0,
                    'day' => [],
                ];
            }

            $divisions[$value]['division_day']++;
            $divisions[$value]['day'][] = $index+1;
        }
        $cityNames = City::whereIn('id', array_keys($divisions))
        ->pluck('name', 'id');
        $result = [];

        foreach ($divisions as $divisionId => $divisionData) {

            $days = $divisionData['day'] ?? [];

            if (empty($days)) {
                continue;
            }

            // 🔹 Fetch hotel + room day-wise
            $details = $this->sent_lead_itinerary->details()
                ->whereNotNull('hotel_id')
                ->where('field', 'day_room')
                ->whereIn('day', $days)
                ->orderBy('day', 'ASC')
                ->with('hotel')
                ->get();

            $roomBookings = [];

            $current = null;

            foreach ($details as $detail) {

                $day = (int) $detail->day;

                // Start new booking if:
                // - first record
                // - hotel changes
                // - room changes
                // - day is not continuous
                if (
                    !$current ||
                    $current['hotel_id'] !== $detail->hotel_id ||
                    $current['room_id'] !== $detail->room_id ||
                    $day !== $current['end_day'] + 1
                ) {
                    // Save previous booking
                    if ($current) {
                        $roomBookings[] = $current;
                    }

                    // Start new booking
                    $current = [
                        'hotel_id'    => $detail->hotel_id,
                        'hotel_name'  => optional($detail->hotel)->name,
                        // 'hotel_email' => optional($detail->hotel)->email1,
                        'hotel_address' => optional($detail->hotel)->address,

                        'room_id'     => $detail->room_id,
                        'room_name'   => $detail->value,
                        'no_of_room'  => $detail->value_quantity,

                        'start_day'   => $day,
                        'end_day'     => $day,
                    ];
                } else {
                    // Extend current booking
                    $current['end_day'] = $day;
                }
            }

            // Push last booking
            if ($current) {
                $roomBookings[] = $current;
            }

            // 🔹 Convert day ranges → check-in / check-out dates
            $checkInOut = [];

            foreach ($roomBookings as $booking) {

                $checkIn  = $arrivalDate->copy()->addDays($booking['start_day'] - 1);
                $checkOut = $arrivalDate->copy()->addDays($booking['end_day']);

                $checkInOut[] = [
                    'hotel_id'     => $booking['hotel_id'],
                    'hotel_name'   => $booking['hotel_name'],
                    // 'hotel_email'  => $booking['hotel_email'],
                    // 'hotel_image'  => $booking['hotel_image'],
                    // 'hotel_address'=> $booking['hotel_address'],

                    'room_id'      => $booking['room_id'],
                    'room_name'    => $booking['room_name'],
                    'no_of_room'   => $booking['no_of_room'],

                    'check_in'     => $checkIn->format('d M Y'),
                    'check_out'    => $checkOut->format('d M Y'),
                    'nights'       => $booking['end_day'] - $booking['start_day'] + 1,
                ];
            }

            // 🔹 Final Division Result
            $grouped = [];

            foreach ($checkInOut as $booking) {
                $key = $booking['hotel_id'] . '_' . $booking['room_id'];
                
                if (!isset($grouped[$key])) {
                    // First entry → main check-in/out
                    $grouped[$key] = [
                        'hotel_id'      => $booking['hotel_id'],
                        'hotel_name'    => $booking['hotel_name'],
                        // 'hotel_email'   => $booking['hotel_email'],
                        // 'hotel_image'   => $booking['hotel_image'],
                        // 'hotel_address' => $booking['hotel_address'],

                        'room_id'       => $booking['room_id'],
                        'room_name'     => $booking['room_name'],
                        'no_of_room'    => $booking['no_of_room'],

                        'check_in'      => $booking['check_in'],
                        'check_out'     => $booking['check_out'],
                        'nights'        => $booking['nights'],

                        're_stays'      => [], // store multiple re-checks
                    ];

                } else {
                    // Same hotel & room → add re-check-in/out
                    $grouped[$key]['re_stays'][] = [
                        're_check_in'  => $booking['check_in'],
                        're_check_out' => $booking['check_out'],
                    ];
                }
            }

            // Re-index array
            $checkInOut = array_values($grouped);
            $result[] = [
                'day_of_division' => $days,
                'division_name'   => $cityNames[$divisionId] ?? null,
                'number_of_day'   => $divisionData['division_day'] ?? 0,

                //  Important output
                'room_bookings'   => $checkInOut,
            ];
        }
        $this->hotel_summary = $result;
        // dd($this->hotel_summary);


    }

    protected function getRoute($index, $sended_lead_itinerary_id){
        $data = SendedLeadItineraryDetail::where('sended_lead_itinerary_id',$sended_lead_itinerary_id)->where('field','day_route')->where('header', 'day_'.$index)->get();
      
        $routeWiseData = [];
        foreach($data as $k => $item){
            $routeWiseData[$k] = [
                'name' => $item->value,
                'image' => $item->route_service?->route?->image
                ? asset($item->route_service->route->image)
                : asset('assets/img/travel-route.jpg'),
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
                                ];
                            })
                            ->toArray(),

                'sightseeings' => SendedLeadItineraryDetail::where('sended_lead_itinerary_id', $sended_lead_itinerary_id)
                ->where('route_service_summary_id', $item->route_service_summary_id)
                            ->where('field', 'day_sightseeing')
                            ->where('header', 'day_' . $index)
                            ->get(['value as name', 'value_quantity as quantity'])
                            ->toArray(),
            ];
        }
        return $routeWiseData;
    }
    
    public function render()
    {
        $this->total_amount = SendedLeadItineraryDetail::where('sended_lead_itinerary_id', $this->sent_lead_itinerary->id)->sum('price');
        return view('livewire.lead.final-quotation-pdf');
    }
}
