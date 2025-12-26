<div>
    <style>

        .hotel-booking-master .hb-tabs {
            display: flex;
            border-bottom: 1px solid #ddd;
        }

        .hotel-booking-master .hb-tab-btn {
            padding: 8px 14px;
            cursor: pointer;
            background: none;
            border: none;
        }

        .hotel-booking-master .hb-tab-btn.active {
            border-bottom: 2px solid #2563eb;
            color: #2563eb;
        }

        .hotel-booking-master .hb-badge-confirmed {
            background: #d1e7dd;
            color: #065f46;
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 20px;
        }

        .hotel-booking-master .hb-grid {
            margin-top: 16px;
        }

        .hotel-booking-master .hb-itinerary-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 16px;
        }

        .hotel-booking-master .hb-summary-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .hotel-booking-master .hb-summary-header {
            display: flex;
            justify-content: space-between;
            padding: 12px;
            background: #eff6ff;
        }

        .hotel-booking-master .hb-summary-cost {
            color: #16a34a;
            font-weight: 700;
            font-size: 18px;
        }

        .hotel-booking-master .hb-summary-body {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            padding: 12px;
            gap: 10px;
        }

        .hotel-booking-master .hb-section-title {
            font-weight: 600;
            margin-bottom: 10px;
        }

        .hotel-booking-master .hb-day-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .hotel-booking-master .hb-day-header {
            background: #dbeafe;
            padding: 10px;
            display: flex;
            gap: 10px;
        }

        .hotel-booking-master .hb-room-card {
            display: flex;
            border-top: 1px solid #eee;
        }

        .hotel-booking-master .hb-room-left {
            width: 65%;
            padding: 12px;
            display: flex;
            gap: 12px;
        }

        .hotel-booking-master .hb-room-right {
            width: 35%;
            padding: 12px;
            background: #f9fafb;
        }

        .hotel-booking-master .hb-hotel-img {
            width: 220px;
            height: 120px;
            object-fit: cover;
            border-radius: 6px;
        }

        .hotel-booking-master .hb-status-toggle {
            display: flex;
            gap: 6px;
            margin-bottom: 10px;
        }

        .hotel-booking-master .hb-status {
            padding: 6px 10px;
            font-size: 12px;
            border-radius: 4px;
            cursor: pointer;
            color: #fff;
        }

        .hotel-booking-master .hb-status.availability {
            background: #16a34a;
        }

        .hotel-booking-master .hb-status.confirm {
            background: #16a34a;
        }

        .hotel-booking-master .hb-status.inactive {
            color: #000;
            background: #fff;
            border: 1px solid;
        }

        .hotel-booking-master .hb-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 8px;
            border-radius: 6px;
            text-decoration: none;
            margin-bottom: 6px;
            font-size: 14px;
        }

        .hotel-booking-master .hb-btn img.hb-icon {
            width: 20px;
            height: 20px;
            object-fit: contain;
        }

        /* WhatsApp */
        .hotel-booking-master .hb-btn.whatsapp {
            background: #ffffff;
            color: #000;
            border: 1px solid #ddd;
        }

        /* Email */
        .hotel-booking-master .hb-btn.email {
            background: #0c1b59;
            color: #fff;
        }


        .hotel-booking-master .hb-meta {
            font-size: 11px;
            color: #555;
            /* border-top: 1px dashed #ccc; */
            padding-top: 8px;
        }
        .hotel-booking-master .hb-hotel-name {
            font-size: 18px;
            font-weight: 500;
        }
        .hotel-booking-master .hb-room-info, .hb-date-info {
            font-size: 11px;
        }
        .hotel-booking-master .hb-label {
            font-weight: 700;
        }
        .hotel-booking-master .hb-summary-title {
            font-weight: 700;
            color: #0c1b59;
        }
        .hotel-booking-master .bg-sky-600 {
            color: #fff;    
            background: #0c1b59;
        }
    </style>

    <div class="hotel-booking-master">
        <div class="box custom-box p-4">
            <div class="box-body">

                <!-- ================= TABS ================= -->
                <div class="hb-tabs">
                    @foreach($leadData->sent_itinerary as $index => $itinerary)
                        <button
                            wire:click="activeTabChange({{ $index }})"
                            class="hb-tab-btn {{ ($activeTab ?? 0) === $index ? 'active' : '' }}"
                        >
                            {{ $itinerary->itinerary_code }}

                            @if($itinerary->is_confirmed)
                                <span class="hb-badge-confirmed">✓ Confirmed</span>
                            @endif
                        </button>
                    @endforeach
                </div>

                <!-- ================= TAB CONTENT ================= -->
                <div class="hb-tab-content">
                    @foreach($sent_itinerary as $index => $item_data)

                        @php $itinerary = $item_data['details']; @endphp

                        <div class="{{ ($activeTab ?? 0) === $index ? 'block' : 'hidden' }}">

                            <div class="hb-grid">

                                <!-- ================= SUMMARY ================= -->
                                <div class="hb-itinerary-card">

                                    <div class="hb-summary-card">
                                        <div class="hb-summary-header">
                                            <span class="hb-summary-title">
                                                {{ optional($itinerary->destination)->name ?? 'N/A' }}
                                            </span>

                                            <span class="hb-summary-cost">
                                                ₹{{ number_format($itinerary->total_cost, 2) }}
                                            </span>
                                        </div>

                                        <div class="hb-summary-body">
                                            <div>
                                                <span class="hb-label">Hotel Category:</span>
                                                <span class="hb-value">{{ optional($itinerary->category)->name ?? 'N/A' }}</span>
                                            </div>

                                            <div>
                                                <span class="hb-label">Days / Nights:</span>
                                                <span class="hb-value">{{ $itinerary->total_days }} / {{ $itinerary->total_nights }}</span>
                                            </div>

                                            <div>
                                                <span class="hb-label">Itinerary:</span>
                                                <span class="hb-value">{{ $itinerary->itinerary_journey }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="hb-section-title">Stay by Journey</p>
                                    <!-- ================= DIVISIONS ================= -->
                                    @foreach($item_data['day_journey'] as $day_journey_key => $division)

                                        <div class="hb-day-card">

                                            <div class="hb-day-header">
                                                <span class="hb-day-badge">
                                                    DAY {{ implode(', ', $division['day_of_division']) }}
                                                </span>

                                                <span class="hb-day-title">
                                                    {{ $division['division_name'] }}
                                                </span>
                                            </div>

                                            <!-- ================= ROOM BOOKINGS ================= -->
                                            @foreach($division['room_bookings'] as $booking)
                                                @php
                                                    $check_in = \Carbon\Carbon::parse($booking['check_in'])->toDateString();
                                                @endphp
                                                <div class="hb-room-card">
                                                    <!-- LEFT -->
                                                    <div class="hb-room-left">
                                                        <img
                                                            src="{{ $booking['hotel_image'] ?asset($booking['hotel_image']): asset('build/assets/images/logo/demo.webp') }}"
                                                            class="hb-hotel-img"
                                                        >

                                                        <div>
                                                            <h4 class="hb-hotel-name">
                                                                {{ $booking['hotel_name'] }}
                                                            </h4>

                                                            <p class="hb-hotel-address">
                                                                {{ $booking['hotel_address'] }}
                                                            </p>

                                                            <p class="hb-room-info">
                                                                🛏 {{ $booking['room_name'] }}
                                                                ({{ $booking['no_of_room'] }} Room)
                                                                | 🍽 {{ $division['rate_plan'] }} | {{ $booking['nights'] + count($booking['re_stays']) }} Night
                                                            </p>

                                                           {{-- Main stay --}}
                                                            <p class="hb-date-info">
                                                                📅 <strong>Check-in:</strong> {{ $booking['check_in'] }}
                                                                → <strong>Check-out:</strong> {{ $booking['check_out'] }}
                                                            </p>

                                                            {{-- Re-stays --}}
                                                            @if(!empty($booking['re_stays']))
                                                                @foreach($booking['re_stays'] as $reStay)
                                                                    <p class="hb-date-info re-stay">
                                                                        🔁 <strong>Re-Check-in:</strong> {{ $reStay['re_check_in'] }}
                                                                        → <strong>Check-out:</strong> {{ $reStay['re_check_out'] }}
                                                                    </p>
                                                                @endforeach
                                                            @endif

                                                        </div>
                                                    </div>

                                                    <!-- RIGHT -->
                                                    <div class="hb-room-right">

                                                        <!-- STATUS TOGGLE -->
                                                        <div class="hb-status-toggle">
                                                            <span
                                                                wire:click="changebookingAction('availability', '{{ $check_in }}', {{ $index }}, {{$day_journey_key}})"
                                                                class="hb-status availability
                                                                {{ $bookingAction === 'availability'
                                                                    && $active_checkin === $check_in
                                                                    && $activeTab === $index
                                                                    ? ''
                                                                    : 'inactive' }}"
                                                            >
                                                                Availability
                                                            </span>

                                                            <span
                                                                wire:click="changebookingAction('confirm', '{{ $check_in }}', {{ $index }},{{$day_journey_key}})"
                                                                class="hb-status confirm
                                                                {{ $bookingAction === 'confirm'
                                                                    && $active_checkin === $check_in
                                                                    && $activeTab === $index
                                                                    ? ''
                                                                    : 'inactive' }}"
                                                            >
                                                                Confirm Booking
                                                            </span>
                                                        </div>


                                                        
                                                        <!-- META -->
                                                        <div class="hb-meta">
                                                            <hr style="border:none; border-top:1px dashed #ccc; margin:6px 0;">

                                                            <p>
                                                                🕒 <strong>Last Sent at:</strong> 13 Sep 2025, 10:20 AM<br>
                                                                📩 <strong>Channel:</strong> WhatsApp<br>
                                                                📌 <strong>Type:</strong> Availability
                                                            </p>

                                                            <hr style="border:none; border-top:1px dashed #ccc; margin:6px 0;">

                                                            <p>
                                                                🕒 <strong>Last Sent at:</strong> 14 Sep 2025, 06:15 PM<br>
                                                                📩 <strong>Channel:</strong> Email<br>
                                                                📌 <strong>Type:</strong> Confirm Booking
                                                            </p>

                                                        </div>
                                                        @if($active_checkin === $check_in && $activeTab === $index)
                                                            <hr style="border:none; border-top:1px dashed #ccc; margin:6px 0;">
                                                            <!-- ACTIONS -->
                                                            <a href="javascript:void(0)" wire:click="openSendWhatsappModal(true)" class="hb-btn whatsapp">
                                                                <img src="{{ asset('assets/img/whatsapp.png') }}" class="hb-icon" alt="WhatsApp">
                                                                Send via WhatsApp
                                                            </a>

                                                            <a href="javascript:void(0)" wire:click="openSendEmailModal(true)" class="hb-btn email">
                                                                <img src="{{ asset('assets/img/gmail.png') }}" class="hb-icon" alt="Email">
                                                                Send via Email
                                                            </a>
                                                        @endif

                                                    </div>

                                                </div>

                                            @endforeach

                                        </div>

                                    @endforeach

                                </div>
                            </div>
                        </div>

                    @endforeach
                </div>
            </div>
        </div>
        {{-- Whatsapp Modal --}}
        <div id="whatsapp_modal_section" class="hs-overlay {{$whatsapp_modal?"":"hidden"}} fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="hs-overlay-open:mt-7 ti-modal-box mt-0 ease-out lg:!max-w-4xl lg:w-full m-3 lg:!mx-auto modal_semi_lg_width bg-white rounded-lg">
                <div class="ti-modal-content p-20">
                    <div class="ti-modal-header flex justify-end items-center">
                        <button type="button" class="text-gray-400 hover:text-gray-600 focus:outline-none badge gap-2 bg-danger/10 text-danger" wire:click="openSendWhatsappModal(false)">
                            <i class="fa-solid fa-xmark text-lg text-dark"></i>
                        </button>
                    </div>
                    <div class="ti-modal-body text-start">
                    </div>
                </div>
            </div>
        </div>
        {{-- Whatsapp Modal --}}
        <div id="email_modal_section" class="hs-overlay {{$email_modal?"":"hidden"}} fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="hs-overlay-open:mt-7 ti-modal-box mt-0 ease-out lg:!max-w-4xl lg:w-full m-3 lg:!mx-auto modal_semi_lg_width bg-white rounded-lg">
                @if($active_checkin && $active_hotel_data)
                    <div class="ti-modal-content p-20">
                        <div class="ti-modal-header flex items-center justify-between gap-4">

                            <!-- Title Section -->
                            <div class="flex-1 bg-sky-600 text-white text-center py-3 rounded-lg">
                                <h2 class="text-lg font-semibold">
                                    {{ $active_hotel_data['room_bookings'][0]['hotel_name'] ?? 'Hotel Name' }} -
                                    <span class="text-yellow-300">
                                        {{ $active_hotel_data['division_name'] ?? 'Division Name' }}
                                    </span>
                                </h2>
                                <p class="text-sm mt-1">({{$bookingAction=="availability"?"Availability Mail":"Confirmation Mail"}} )</p>
                            </div>

                        </div>

                        <div class="ti-modal-body text-start">
                        <div class="w-full font-sans text-gray-800">
                                <!-- Header -->
                                <!-- Content -->
                                <div class="bg-white p-6 space-y-4 rounded-b-lg">

                                    <p>Dear Reservation Team,</p>

                                    <div class="bg-sky-50 border-l-4 border-sky-600 p-3">
                                        <strong>Greetings from Christmas Tree Hospitality!!</strong>
                                    </div>
                                    <p>
                                        {{$bookingAction=="availability"?"We have a new query for accommodation arrangement.
                                        Kindly share the availability as soon as possible.":"Congratulations! New booking has been received. kindly find the booking details below."}}
                                        
                                    </p>

                                <table class="w-full border border-gray-300 text-sm text-center border-collapse">

                                        <!-- Title -->
                                        <tr class="bg-gray-100">
                                            <td colspan="4" class="px-4 py-2 font-semibold text-gray-800">
                                                BOOKING DETAILS
                                            </td>
                                        </tr>

                                        <!-- Guest Info -->
                                        @if($bookingAction=="confirm")
                                            <tr>
                                                <td colspan="4" class="px-4 py-2 text-center">
                                                    <span class="font-medium text-gray-600">Guest Name:</span>
                                                    <span class="text-gray-800">
                                                        {{ $active_hotel_data['guest_name'] ?? 'N/A' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endif

                                        <!-- Column Headers -->
                                        <tr class="bg-gray-50 font-semibold">
                                            <td class="px-3 py-2 border">Details of Night Stay</td>
                                            <td class="px-3 py-2 border">Number of Guests</td>
                                            <td class="px-3 py-2 border">Room Type</td>
                                            <td class="px-3 py-2 border">Meal Plan</td>
                                        </tr>
                                        <!-- Data Row -->
                                        @foreach($active_hotel_data['room_bookings'] as $booking)
                                            <tr class="align-top">

                                                <!-- Night Stay -->
                                                <td class="px-3 py-2 border text-left">
                                                    <p class="font-semibold">
                                                        {{ $active_hotel_data['number_of_day'] }} Night{{ $active_hotel_data['number_of_day'] > 1 ? 's' : '' }}
                                                    </p>

                                                    <p>
                                                        <strong>Check In:</strong> {{ $booking['check_in'] }}
                                                    </p>
                                                    <p>
                                                        <strong>Check Out:</strong> {{ $booking['check_out'] }}
                                                    </p>

                                                    @if(!empty($booking['re_stays']))
                                                        <hr class="my-2">
                                                        @foreach($booking['re_stays'] as $reStay)
                                                            <p>
                                                                <strong>Re-Check In:</strong> {{ $reStay['re_check_in'] }}
                                                            </p>
                                                            <p>
                                                                <strong>Re-Check Out:</strong> {{ $reStay['re_check_out'] }}
                                                            </p>
                                                        @endforeach
                                                    @endif
                                                </td>

                                                <!-- Guests -->
                                                <td class="px-3 py-2 border text-center">
                                                    <p>
                                                        <strong>Adults:</strong>
                                                        {{ $active_hotel_data['adults'] ?? 0 }}
                                                    </p>

                                                    <p>
                                                        <strong>Children:</strong>
                                                        {{ $active_hotel_data['children'] ?? 0 }}
                                                    </p>
                                                </td>

                                                <!-- Room -->
                                                <td class="px-3 py-2 border text-center">
                                                    <p>{{ $booking['room_name'] }}</p>
                                                    <p><strong>Rooms:</strong> {{ $booking['no_of_room'] }}</p>
                                                    {{-- <p><strong>Accommodation breakdown</strong></p> --}}
                                                      @if(!empty($active_hotel_data['extra_mattress']) && $active_hotel_data['extra_mattress'] > 0)
                                                        <p>
                                                            <strong>Extra Mattress:</strong>
                                                            {{ $active_hotel_data['extra_mattress'] }}
                                                        </p>
                                                    @endif

                                                    @php
                                                        $childrenData = !empty($active_hotel_data['children_data'])
                                                            ? json_decode($active_hotel_data['children_data'], true)
                                                            : [];
                                                    @endphp

                                                    @if(!empty($childrenData))
                                                        <div class="mt-2 text-xs text-gray-600">
                                                            @foreach($childrenData as $child)
                                                                <p>
                                                                   {{ $child['addon_type'] }} : ({{ $child['quantity'] }}) - ({{ $child['age'] }})
                                                                </p>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </td>

                                                <!-- Meal -->
                                                <td class="px-3 py-2 border text-center">
                                                    {{ $active_hotel_data['rate_plan'] ?? 'N/A' }}
                                                </td>

                                            </tr>
                                            @endforeach


                                    </table>

                                </div>
                            </div>

                        </div>
                        <div class="ti-modal-footer gap-3 mt-6">
                            @if (session()->has('email_error'))
                                <div class="alert alert-danger" role="alert">
                                    {{ session('email_error') }}
                                </div>
                            @endif

                            @if (session()->has('email_success'))
                                <div class="alert alert-success" role="alert">
                                     ✅ {{ session('email_success') }}
                                </div>
                            @endif
                            <div class="flex justify-end items-center">
                                <button type="button" class="ti-btn ti-btn-danger-full !py-1 !px-2 ti-btn-wave me-[0.375rem]" wire:click="openSendEmailModal(false)">Close</button>
                            <button type="button" wire:click="sendViaEmail()" class="ti-btn ti-btn-primary-full !py-1 !px-2 ti-btn-wave me-[0.375rem] bg-sky-600">Send Email</button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- LOADER -->
        <div wire:loading class="loader" wire:target="activeTabChange, changebookingAction,openSendEmailModal,openSendWhatsappModal,sendViaWhatsapp,sendViaEmail">
            <div class="spinner">
                <img src="{{ asset('build/assets/images/media/loader.svg') }}">
            </div>
        </div>

    </div>

</div>
