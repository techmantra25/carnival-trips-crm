<?php

namespace App\Livewire\Lead;

use Livewire\Component;
use App\Models\Lead;
use App\Models\City;
use Carbon\Carbon;
use App\Models\HotelAvailabilityOrBookingLog;
use App\Services\MailTemplateService;

class ManageHotelBooking extends Component
{
    public $leadData, $sent_itinerary =[];
    public $activeTab = 0;
    public $bookingAction = 'availability', $active_checkin = null, $active_hotel_data = null;
    public $whatsapp_modal = false;
    public $email_modal = false;
    public function mount($lead_id){
        $this->leadData = Lead::findOrFail($lead_id);
        // $this->sent_itinerary = $this->leadData->sent_itinerary;
        $this->loadItineraryData();
    }
    public function loadItineraryData(){
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
                $grouped = [];

                foreach ($checkInOut as $booking) {
                    $key = $booking['hotel_id'] . '_' . $booking['room_id'];
                    $mail_tab = $item->id . '_' . $divisionId . '_' . $key;
                    $availability = HotelAvailabilityOrBookingLog::with('sender')->where('itinerary_id', $item->id)
                            ->where('code', $mail_tab)
                            ->where('type', 'availability')
                            ->latest()
                            ->first();

                    $confirmBooking = HotelAvailabilityOrBookingLog::with('sender')->where('itinerary_id', $item->id)
                            ->where('code', $mail_tab)
                            ->where('type', 'Confirm_booking')
                            ->latest()
                            ->first();

                    $logData = collect([$availability, $confirmBooking])
                    ->filter()
                    ->map(function ($log) {
                        return [
                            'id'         => $log->id,
                            'created_at' => $log->created_at,
                            'channel'    => $log->channel,
                            'type'       => $log->type,
                            'sender'     => [
                                'name' => optional($log->sender)->name,
                            ],
                        ];
                    })
                    ->values()
                    ->toArray();

                    if (!isset($grouped[$key])) {
                        // First entry → main check-in/out
                        $grouped[$key] = [
                            'mail_tab'      => $mail_tab,
                            'log_data'      => $logData,
                            'hotel_id'      => $booking['hotel_id'],
                            'hotel_name'    => $booking['hotel_name'],
                            'hotel_email'   => $booking['hotel_email'],
                            'hotel_image'   => $booking['hotel_image'],
                            'hotel_address' => $booking['hotel_address'],

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
                        $grouped[$key]['mail_tab'] = $mail_tab;
                        $grouped[$key]['log_data'] = $logData;
                        $grouped[$key]['re_stays'][] = [
                            're_check_in'  => $booking['check_in'],
                            're_check_out' => $booking['check_out'],
                        ];
                    }
                }

                // Re-index array
                $checkInOut = array_values($grouped);
                $result[] = [
                    'itinerary_id' => $item->id,
                    'day_of_division' => $days,
                    'division_name'   => $cityNames[$divisionId] ?? null,
                    'division_id'     => (int) $divisionId,
                    'number_of_day'   => $divisionData['division_day'] ?? 0,

                    //  Important output
                    'room_bookings'   => $checkInOut,

                    'rate_plan'       => $this->leadData->meal_type,
                    'guest_name'      => $this->leadData->customer_name,
                    'adults'          => $this->leadData->number_of_adults,
                    'children'        => $this->leadData->number_of_children,
                    'extra_mattress'  => $this->leadData->extra_mattress,
                    'children_data'   => $this->leadData->children_data,
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
        if($this->activeTab === 0){
            $this->activeTab = $confirmedIndex !== false ? $confirmedIndex : 0;
        }else{
            $this->activeTab = $this->activeTab;
        }
    }

    public function changebookingAction($action, $checkIn, $index, $dayJourneyKey){
        $this->bookingAction = $action;
        $this->active_checkin = $checkIn;
        $this->activeTab = $index;
        $this->active_hotel_data = $this->sent_itinerary[$index]['day_journey'][$dayJourneyKey];
    }
    public function activeTabChange($index){
        $this->activeTab = $index;
        $this->reset(['bookingAction', 'active_checkin']);
    }
    public function openSendWhatsappModal($modal){
        $this->email_modal = false;
        $this->whatsapp_modal = $modal;
    }
    public function openSendEmailModal($modal){
        $this->whatsapp_modal = false;
        $this->email_modal = $modal;
    }
    public function sendViaWhatsapp(){
        dd('Send via WhatsApp currently not implemented.');
    }
    public function sendViaEmail(){
        $to = $this->active_hotel_data['room_bookings'][0]['hotel_email'];
        // $to = "rajib.techmantra@gmail.com";
        $recipient_name = $this->active_hotel_data['room_bookings'][0]['hotel_name'];
        $room_name = $this->active_hotel_data['room_bookings'][0]['room_name'];
        $code = $this->active_hotel_data['room_bookings'][0]['mail_tab'];
        
        if($to){
            $mailService = app(MailTemplateService::class);
            $nights = $this->active_hotel_data['number_of_day'] ?? 1;
            $division = $this->active_hotel_data['division_name'] ?? '';
            $booking_id = $this->active_hotel_data['booking_id'] ?? '';

            if ($this->bookingAction === 'confirm') {
                $subject = "Booking Confirmation | {$booking_id} | {$nights} Nights | {$room_name} | {$division}";
            } else {
                $subject = "Availability Request | {$nights} Night Stay | {$room_name} | {$division}";
            }

            $response = $mailService->send(
                $to,
                'hotel_availability_check_and_booking_confirmation',
                $subject,
                [
                    'template_type'    => 'hotel_availability_check_and_booking_confirmation',
                    'recipient_name'   => $recipient_name,
                    'mail_type'        => $this->bookingAction,
                    'booking_details'  => $this->active_hotel_data,
                    'company_name'     => "Christmas Tree Hospitality",
                    'sender_name'      => "Reservation Team",
                    'subject'          => $subject,
                ],
                env('MAIL_FROM_ADDRESS'),
                env('MAIL_FROM_NAME'),
                []//attachments
            );
            if($response){
                $log = HotelAvailabilityOrBookingLog::create([
                    'itinerary_id' => $this->active_hotel_data['itinerary_id'],
                    'code'         => $code,
                    'channel'      => 'Email',
                    'type'         => $this->bookingAction === 'confirm' ? 'Confirm_booking' : 'availability',
                    'sent_by'      => auth()->user()->id,
                ]);
                session()->flash('success', 'Email sent successfully to hotel.');
                $this->loadItineraryData();
                $this->dispatch('$refresh');
                $this->email_modal = false;
            }else{
                session()->flash('email_error', 'Failed to send email. Please try again.');
                return;
            }
        }else{
           session()->flash('email_error', 'Hotel email address not found. Unable to send email.');
             return;

        }
        
    }
    public function render()
    {
        return view('livewire.lead.manage-hotel-booking');
    }
}
