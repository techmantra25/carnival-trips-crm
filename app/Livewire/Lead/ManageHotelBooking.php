<?php

namespace App\Livewire\Lead;

use Livewire\Component;
use App\Models\Lead;
use App\Models\City;
 use Carbon\Carbon;

class ManageHotelBooking extends Component
{
    public $leadData, $sent_itinerary =[];
    public $activeTab = 0;
    public $bookingAction = 'availability';
    public function mount($lead_id){
        $this->leadData = Lead::findOrFail($lead_id);
        // $this->sent_itinerary = $this->leadData->sent_itinerary;
        $arrivalDate = Carbon::parse($this->leadData->arrival_date);
        foreach ($this->leadData->sent_itinerary as $key => $item) {
            $data = explode(',', $item->stay_by_journey);
            // remove last index
            array_pop($data);
            // count occurrences
            $divisions = [];
            foreach ($data as $index => $value) {
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
                $details = $item->details()
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
                            'hotel_email' => optional($detail->hotel)->email1,
                            'hotel_image' => optional($detail->hotel)->image,
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
                        'hotel_email'  => $booking['hotel_email'],
                        'hotel_image'  => $booking['hotel_image'],
                        'hotel_address'=> $booking['hotel_address'],

                        'room_id'      => $booking['room_id'],
                        'room_name'    => $booking['room_name'],
                        'no_of_room'   => $booking['no_of_room'],

                        'check_in'     => $checkIn->format('d M Y'),
                        'check_out'    => $checkOut->format('d M Y'),
                        'nights'       => $booking['end_day'] - $booking['start_day'] + 1,
                    ];
                }

                // 🔹 Final Division Result
                $result[] = [
                    'day_of_division' => $days,
                    'division_name'   => $cityNames[$divisionId] ?? null,
                    'division_id'     => (int) $divisionId,
                    'number_of_day'   => $divisionData['division_day'] ?? 0,

                    //  Important output
                    'room_bookings'   => $checkInOut,

                    'rate_plan'       => $this->leadData->meal_type,
                    'guest_name'      => $this->leadData->customer_name,
                    'booking_id'      => $this->leadData->unique_id,
                ];
            }

            // 🔹 Final itinerary item
            $itineraryItem = [
                'day_journey' => $result,
                'details'     => $item,
            ];
            $this->sent_itinerary[] = $itineraryItem;
        }
        $confirmedIndex = collect($this->leadData->sent_itinerary)
        ->search(fn ($item) => $item->is_confirmed == 1);
        $this->activeTab = $confirmedIndex !== false ? $confirmedIndex : 0;
    }
    public function render()
    {
        return view('livewire.lead.manage-hotel-booking');
    }
}
