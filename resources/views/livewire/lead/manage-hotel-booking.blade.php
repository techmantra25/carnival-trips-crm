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
                                    @foreach($item_data['day_journey'] as $division)

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
                                                                | 🍽 {{ $division['rate_plan'] }}
                                                            </p>

                                                            <p class="hb-date-info">
                                                                📅 {{ $booking['check_in'] }}
                                                                → {{ $booking['check_out'] }}
                                                                ({{ $booking['nights'] }} Night)
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <!-- RIGHT -->
                                                    <div class="hb-room-right">

                                                        <!-- STATUS TOGGLE -->
                                                        <div class="hb-status-toggle">
                                                            <span
                                                                wire:click="changebookingAction('availability', '{{ $check_in }}', {{ $booking['room_id'] }})"
                                                                class="hb-status availability
                                                                {{ $bookingAction === 'availability'
                                                                    && $active_checkin === $check_in
                                                                    && $active_roomId === $booking['room_id']
                                                                    ? ''
                                                                    : 'inactive' }}"
                                                            >
                                                                Availability
                                                            </span>

                                                            <span
                                                                wire:click="changebookingAction('confirm', '{{ $check_in }}', {{ $booking['room_id'] }})"
                                                                class="hb-status confirm
                                                                {{ $bookingAction === 'confirm'
                                                                    && $active_checkin === $check_in
                                                                    && $active_roomId === $booking['room_id']
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
                                                        @if($active_checkin === $check_in && $active_roomId === $booking['room_id'])
                                                            <hr style="border:none; border-top:1px dashed #ccc; margin:6px 0;">
                                                            <!-- ACTIONS -->
                                                            <a href="javascript:void(0)" wire:click="sendViaWhatsapp(true)" class="hb-btn whatsapp">
                                                                <img src="{{ asset('assets/img/whatsapp.png') }}" class="hb-icon" alt="WhatsApp">
                                                                Send via WhatsApp
                                                            </a>

                                                            <a href="javascript:void(0)" wire:click="sendViaEmail(true)" class="hb-btn email">
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
                        <button type="button" class="text-gray-400 hover:text-gray-600 focus:outline-none badge gap-2 bg-danger/10 text-danger" wire:click="sendViaWhatsapp(false)">
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
                <div class="ti-modal-content p-20">
                    <div class="ti-modal-header flex justify-end items-center">
                        <button type="button" class="text-gray-400 hover:text-gray-600 focus:outline-none badge gap-2 bg-danger/10 text-danger" wire:click="sendViaEmail(false)">
                            <i class="fa-solid fa-xmark text-lg text-dark"></i>
                        </button>
                    </div>
                    <div class="ti-modal-body text-start">
                    </div>
                </div>
            </div>
        </div>

        <!-- LOADER -->
        <div wire:loading class="loader" wire:target="activeTabChange, changebookingAction,sendViaWhatsapp,sendViaEmail">
            <div class="spinner">
                <img src="{{ asset('build/assets/images/media/loader.svg') }}">
            </div>
        </div>

    </div>

</div>
