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
            display: block;
            text-align: center;
            padding: 8px;
            border-radius: 6px;
            color: #fff;
            text-decoration: none;
            margin-bottom: 6px;
        }

        .hotel-booking-master .hb-btn.whatsapp {
            background: #ffffff;
            color: #000;
            border: 1px solid #ddd;
        }

        .hotel-booking-master .hb-btn.email {
            background: #0c1b59;
        }

        .hotel-booking-master .hb-meta {
            font-size: 11px;
            color: #555;
            border-top: 1px dashed #ccc;
            padding-top: 8px;
        }
        .hotel-booking-master .hb-hotel-name {
            font-size: 18px;
            font-weight: 500;
        }
        .hotel-booking-master .hb-room-info, .hb-date-info {
            font-size: 11px;
        }

    </style>

    <div class="hotel-booking-master">
        <div class="box custom-box p-4">
            <div class="box-body">

                <!-- ================= TABS ================= -->
                <div class="hb-tabs">
                    @foreach($leadData->sent_itinerary as $index => $itinerary)
                        <button
                            wire:click="$set('activeTab', {{ $index }})"
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
                                                <span class="hb-label">Hotel Category</span>
                                                <span class="hb-value">{{ optional($itinerary->category)->name ?? 'N/A' }}</span>
                                            </div>

                                            <div>
                                                <span class="hb-label">Days / Nights</span>
                                                <span class="hb-value">{{ $itinerary->total_days }} / {{ $itinerary->total_nights }}</span>
                                            </div>

                                            <div>
                                                <span class="hb-label">Route</span>
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

                                                <div class="hb-room-card">

                                                    <!-- LEFT -->
                                                    <div class="hb-room-left">
                                                        <img
                                                            src="{{ $booking['hotel_image'] ?? asset('build/assets/images/logo/demo.webp') }}"
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
                                                                wire:click="$set('bookingAction', 'availability')"
                                                                class="hb-status availability {{ $bookingAction !== 'availability' ? 'inactive' : '' }}"
                                                            >
                                                                Availability
                                                            </span>

                                                            <span
                                                                wire:click="$set('bookingAction', 'confirm')"
                                                                class="hb-status confirm {{ $bookingAction !== 'confirm' ? 'inactive' : '' }}"
                                                            >
                                                                Confirm Booking
                                                            </span>
                                                        </div>

                                                        
                                                        <!-- META -->
                                                        <div class="hb-meta">

                                                            <p>
                                                                🕒 <strong>Sent at:</strong> 12 Sep 2025, 03:45 PM<br>
                                                                📩 <strong>Channel:</strong> Email<br>
                                                                📌 <strong>Type:</strong> Availability
                                                            </p>

                                                            <hr style="border:none; border-top:1px dashed #ccc; margin:6px 0;">

                                                            <p>
                                                                🕒 <strong>Sent at:</strong> 13 Sep 2025, 10:20 AM<br>
                                                                📩 <strong>Channel:</strong> WhatsApp<br>
                                                                📌 <strong>Type:</strong> Availability
                                                            </p>

                                                            <hr style="border:none; border-top:1px dashed #ccc; margin:6px 0;">

                                                            <p>
                                                                🕒 <strong>Sent at:</strong> 14 Sep 2025, 06:15 PM<br>
                                                                📩 <strong>Channel:</strong> Email<br>
                                                                📌 <strong>Type:</strong> Confirm Booking
                                                            </p>

                                                        </div>
                                                        <hr style="border:none; border-top:1px dashed #ccc; margin:6px 0;">
                                                        <!-- ACTIONS -->
                                                        <a href="#" class="hb-btn whatsapp">
                                                            📲 Send via WhatsApp
                                                        </a>

                                                        <a href="#" class="hb-btn email">
                                                            📩 Send via Email
                                                        </a>


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

        <!-- LOADER -->
        <div wire:loading class="hb-loader">
            <img src="{{ asset('build/assets/images/media/loader.svg') }}">
        </div>

    </div>

</div>
