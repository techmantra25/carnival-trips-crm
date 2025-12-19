<div>
    <style>
        /* Tabs container */
        .tabs {
            display: flex;
            border-bottom: 1px solid #dee2e6;
        }

        .tabs button {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            font-weight: 500;
            border: none;
            border-bottom: 2px solid transparent;
            background: transparent;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }

        .tabs button.active {
            border-bottom-color: #0d6efd; /* Bootstrap primary blue */
            color: #0d6efd;
            font-weight: 600;
        }

        .tabs button:not(.active):hover {
            color: #0d6efd;
        }

        /* Confirmed badge next to tab */
        .tabs .badge-confirmed {
            margin-left: 0.25rem;
            padding: 0.15rem 0.5rem;
            font-size: 0.7rem;
            font-weight: 600;
            background-color: #d1e7dd; /* green-100 */
            color: #0f5132; /* green-800 */
            border-radius: 9999px;
            vertical-align: middle;
        }

        /* Card style */
        .itinerary-card {
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .itinerary-card p {
            margin: 0.25rem 0;
        }

        /* Highlight total cost */
        .total-cost {
            color: #dc3545;
            font-weight: 600;
            font-size: 1.05rem;
        }

        /* Nested day list */
        .day-list {
            margin: 0.5rem 0 0 0;
            padding-left: 1rem;
        }

        .day-list li {
            list-style-type: disc;
            margin-bottom: 0.25rem;
        }

        /* Tab content spacing */
        .tab-content {
            margin-top: 1rem;
        }

        .day-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: #ffffff;
            overflow: hidden;
        }

        .day-card-header {
            background: #dbeafe; /* light blue */
            padding: 10px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .day-badge {
            font-size: 12px;
            font-weight: 700;
            color: #2563eb;
        }

        .day-title {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
        }


        .itinerary-summary-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: #ffffff;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        /* Header */
        .summary-header {
            background: #eff6ff; /* light blue */
            padding: 12px 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .summary-title {
            font-size: 15px;
            font-weight: 700;
            color: #1e3a8a;
        }

        .summary-cost {
            font-size: 16px;
            font-weight: 800;
            color: #16a34a; /* green highlight */
        }

        /* Body */
        .summary-body {
            padding: 12px 14px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .summary-item {
            font-size: 14px;
        }

        .summary-item .label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
        }

        .summary-item .value {
            font-weight: 600;
            color: #111827;
        }

    </style>

    <div class="box custom-box p-4">
        <div class="box-body">

            <!-- Tabs Navigation -->
            <div class="tabs">
                @foreach($sent_itinerary as $index => $itinerary)
                    <button wire:click="$set('activeTab', {{ $index }})"
                            class="{{ ($activeTab ?? 0) === $index ? 'active' : '' }}">
                        {{ $itinerary->itinerary_code }}
                        @if($itinerary->is_confirmed)
                            <span class="badge-confirmed">✓ Confirmed</span>
                        @endif
                    </button>
                @endforeach

            </div>

            <!-- Tabs Content -->
            <div class="tab-content">
                @foreach($sent_itinerary as $index => $itinerary)
                    <div class="{{ ($activeTab ?? 0) === $index ? 'block' : 'hidden' }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                            <div class="itinerary-card">
                                <div class="itinerary-summary-card">

                                    <!-- Header -->
                                    <div class="summary-header">
                                        <span class="summary-title">
                                            {{ optional($itinerary->destination)->name ?? 'N/A' }}
                                        </span>

                                        <span class="summary-cost">
                                            ₹{{ number_format($itinerary->total_cost, 2) }}
                                        </span>
                                    </div>

                                    <!-- Body -->
                                    <div class="summary-body">
                                        <div class="summary-item">
                                            <span class="label">Hotel Category</span>
                                            <span class="value">{{ optional($itinerary->category)->name ?? 'N/A' }}</span>
                                        </div>

                                        <div class="summary-item">
                                            <span class="label">Days / Nights</span>
                                            <span class="value">{{ $itinerary->total_days }} / {{ $itinerary->total_nights }}</span>
                                        </div>

                                        <div class="summary-item">
                                            <span class="label">Itinerary Route</span>
                                            <span class="value">{{ $itinerary->itinerary_journey }}</span>
                                        </div>
                                    </div>
                                </div>

                                <p class="font-semibold mt-4 mb-2">Stay by Journey:</p>
                                {{-- {{dd($itinerary)}} --}}
                                @php
                                    // $SendedLeadItineraryDetail = SendedLeadItineraryDetail::where('sended_lead_itinerary_id', '')
                                @endphp
                                <div class="space-y-4">
                                    @foreach(explode(',', $itinerary->stay_by_journey) as $key => $day)

                                        @php
                                            $division = App\Models\City::find($day);

                                            // Example placeholders (replace with real data if available)
                                            $date = now()->addDays($key)->format('d M Y'); // dynamic date
                                            $hotelName = 'Hotel Name';
                                            $roomQty =  1;
                                            $roomName = 'Deluxe Room';
                                        @endphp

                                        <div class="day-card">

                                            <!-- Header -->
                                            <div class="day-card-header">
                                                <span class="day-badge">DAY {{ $key + 1 }}</span>
                                                <span class="day-title">
                                                    ({{ $date }})
                                                    {{ $division?->name }}
                                                </span>
                                            </div>

                                            <!-- Body -->
                                           <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #ddd; border-radius:8px; overflow:hidden;">
                                                <tr>

                                                    <!-- LEFT : HOTEL DETAILS -->
                                                    <td width="65%" style="vertical-align:top; padding:12px;">

                                                        <table width="100%" cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td width="220">
                                                                    <img src="http://127.0.0.1:8000/build/assets/images/logo/demo.webp"
                                                                        style="width:100%; max-width:220px; height:120px; object-fit:cover; border-radius:6px;">
                                                                </td>

                                                                <td style="vertical-align:top; padding-left:12px;">
                                                                    <h4 style="margin:0; font-size:15px; color:#031b4e; text-transform:uppercase;">
                                                                        La Vue Resort
                                                                    </h4>

                                                                    <p style="margin:4px 0 8px; font-size:12px; color:#555;">
                                                                        Haldhibari, Bagari N.C., Assam 785609
                                                                    </p>

                                                                    <br><br>

                                                                    <span style="display:inline-block; font-size:12px; padding:5px 8px;
                                                                        border-radius:4px; border:1px solid #ddd; color:#4e4e4e;">
                                                                        Serene Deluxe Room - AC :
                                                                        <strong style="color:#ee335e;">(2)</strong>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        </table>

                                                    </td>

                                                    <!-- RIGHT : ACTIONS -->
                                                    <td width="35%" style="vertical-align:top; padding:12px; background:#f9fafb;">

                                                        <!-- ACTION TABS -->
                                                        <div style="margin-bottom:10px;">
                                                            <span style="display:inline-block; padding:6px 10px; font-size:12px;
                                                                background:#2563eb; color:#fff; border-radius:4px;">
                                                                Availability
                                                            </span>

                                                            <span style="display:inline-block; padding:6px 10px; font-size:12px;
                                                                background:#16a34a; color:#fff; border-radius:4px; margin-left:6px;">
                                                                Confirm Booking
                                                            </span>
                                                        </div>

                                                        <!-- BUTTONS -->
                                                        <div style="margin-bottom:10px;">
                                                            <a href="#"
                                                            style="display:block; text-align:center; text-decoration:none;
                                                            background:#25D366; color:#fff; font-size:13px;
                                                            padding:8px; border-radius:6px; margin-bottom:6px;">
                                                                📲 Send via WhatsApp
                                                            </a>

                                                            <a href="#"
                                                            style="display:block; text-align:center; text-decoration:none;
                                                            background:#0c1b59; color:#fff; font-size:13px;
                                                            padding:8px; border-radius:6px;">
                                                                📩 Send via Email
                                                            </a>
                                                        </div>

                                                        <!-- SENT LOG -->
                                                        <div style="font-size:11px; color:#555; border-top:1px dashed #ccc; padding-top:8px;">
                                                            <p style="margin:2px 0;">
                                                                ✅ Availability Sent : WhatsApp
                                                            </p>
                                                            <p style="margin:2px 0;">
                                                                ✅ Booking Sent : Email
                                                            </p>
                                                            <p style="margin:2px 0;">
                                                                🕒 Last Sent : 12 Sep 2025, 03:45 PM
                                                            </p>
                                                        </div>

                                                    </td>

                                                </tr>
                                            </table>


                                        </div>

                                    @endforeach
                                </div>

                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
    <!-- Loader -->
    <div wire:loading class="loader">
        <div class="spinner">
            <img src="{{ asset('build/assets/images/media/loader.svg') }}" alt="Loading...">
        </div>
    </div>
</div>
