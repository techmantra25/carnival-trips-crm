<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Final Quotation</title>

    <style>
        .table{
                width:100%;
                background:#fff;
            }
            table{
                width:100%;
            }
            .table tr td {
                font-size:15px;
                color:#000;
            }
            .table thead i {
                color:#1e58a3;
            }
            .route-style {
                position:relative;
            }
            .route-style:after {
                content: "";
                position: absolute;
                left: 0;
                top: 0;
                width: 1px;
                height: 100%;
                background: #0162e8;
            }
            .route-style li {
                position: relative;
                padding: 3px 12px;
                color:#838383;
            }
            .route-style li:after {
                content: "";
                width: 7px;
                height: 7px;
                position: absolute;
                border-radius: 50%;
                background: #0162e8;
                left: -3px;
                top: 8px;
                z-index: 2;
                border: 2px solid #fff;
            }

            .total-ex-list {
                list-style:none;
            }
            .total-ex-list li {
                color:rgb(0, 0, 0);
                font-size: 11px;
                margin-bottom: 7px;
            }
            .total-ex-list li i {
                color: #f2a144;
                margin-right:10px;
            }

            .hotel-list {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(297px, 1fr));
                gap:8px;
            }
            .hotel-list li {
                border:1px solid #ccc;
                border-top-left-radius:6px;
                border-top-right-radius:6px;
            }
            .hotel-list li figure {
                overflow:hidden;
                border-top-left-radius:6px;
                border-top-right-radius:6px;
                height: 200px;
            }
            .hotel-list li figcaption {
                padding: 12px;
            }

            .hotel-list li figcaption h3 {
                font-size: 12px;
                text-transform: uppercase;
                color: #1e58a3;
                font-weight: 500;
                margin-bottom: 8px;
            }
            .hotel-list li figcaption h2 {
                font-size: 13px;
                font-weight: 500;
                margin-bottom: 8px;
            }
            .hotel-list li figcaption p {
                font-size: 12px;
            }

            .activity-list {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap:8px;
            }

            .activity-list li {
                border-radius:6px;
                overflow:hidden;
                position:relative;
                z-index: 1;
                height: 187px;
            }

            .activity-list li:after {
                content:"";
                position:absolute;
                top:0;
                left:0;
                width:100%;
                height:100%;
                background: linear-gradient(1deg,rgba(0, 0, 0, 1) 14%, rgba(0, 0, 0, 0) 100%);
                z-index: -1;
            }
            .activity-list li figcaption {
                padding: 22px;
                color: #fff;
                position: absolute;
                bottom: 0;
                left: 0;
                width: 100%;
            }

            .activity-list li figcaption h2 {
                color:#fff;
                font-size:13px;
            }

            .keep-together {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .page-break-after {
            page-break-after: always;
            break-after: page;
            }
    </style>
       
</head>
<body>

<div>
    <div class="card">
        <table class="table" id="print-section">
            <tr>
                <td>
                    <table style="background: rgba(254, 215, 170, 0.2);  border: 1px solid #fed7aa;">
                        <tr>
                            <td>
                                <table>
                                    <tr>
                                        <td style="font-size:16px; color:#000; text-transform:uppercase; padding-left:15px !important; padding-right:15px !important; padding-bottom:0px !important;">
                                            Customized Trip Itinerary
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:15px !important;">
                                            <p style="font-size:13px; color:#000; margin-bottom:9px;"><strong>Client:</strong>{{$itinerary['name']}} </p>
                                            <p style="font-size:13px; color:#000; margin-bottom:9px;"><strong>Client Email:</strong> {{$itinerary['email']}} </p>
                                            <p style="font-size:13px; color:#000; margin-bottom:9px;"><strong>Client Mobile:</strong> +{{$itinerary['mobile']}} </p>
                                            <p style="font-size:13px; color:#000; margin-bottom:9px;"><strong>Travel Date:</strong> {{$itinerary['travel_dates']}} </p>
                                            
                                        </td>
                                        <td style="padding:15px !important;">
                                            <p style="font-size:13px; color:#000; margin-bottom:9px;"><strong>PAX:</strong> {{$itinerary['number_of_travellor']}} </p>
                                            <p style="font-size:13px; color:#000; margin-bottom:9px;"><strong>Adults:</strong> {{$itinerary['number_of_adults']}} </p>
                                            <p style="font-size:13px; color:#000; margin-bottom:9px;"><strong>Child:</strong> {{$itinerary['number_of_children']}} </p>
                                            <p style="font-size:13px; color:#000; margin-bottom:9px;"><strong>Extra Mattress:</strong> {{$itinerary['extra_mattress']}} </p>
                                        </td>
                                        <td style="padding:15px !important;">
                                            <p style="font-size:13px; color:#000; margin-bottom:9px;"><strong>{{$itinerary['destination']}} Trip:</strong> {{$itinerary['travel_duration']}} </p>
                                            <p style="font-size:13px; color:#000; margin-bottom:9px;"><strong>Total Room:</strong> {{$itinerary['number_of_rooms']}} </p>
                                            <p style="font-size:13px; color:#000; margin-bottom:9px;"><strong>Meal Type:</strong> {{$itinerary['meal_type']}} </p>
                                            
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="vertical-align: top; text-align: center;">
                                <a class="navbar-brand py-0" href="#" style="display: inline-block;">
                                    <img src="{{asset('front_assets/images/logo.png')}}" 
                                        alt="logo"
                                        style="max-width: 120px; width: 100%; height: auto; display: block; margin: 0 auto;">
                                </a>

                                <table style="background:#1e58a3; color:#fff; text-align:center; width:100%; margin-top:10px;">
                                    <tr>
                                        <td style="text-align:center; padding:10px;">
                                            <h4 style="color:#fff; text-transform:uppercase; font-size: 13px; margin:0;">
                                                Total Amount
                                            </h4>
                                            <strong style="color:#fff; font-size:18px;">{{env('DEFAULT_CURRENCY_SYMBOL')}}{{$total_amount}}</strong>

                                            <p style="color:#fff; font-size: 12px; margin:5px 0 0 0;">
                                                Itinerary Code : <strong style="color:#fff;">{{$sent_lead_itinerary->itinerary_code}}</strong>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>


                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    @foreach($day_itinerary as $key=> $day)
                    {{-- {{dd($day_itinerary)}} --}}
                        <table style="border:1px solid rgba(1, 98, 232, 0.5) !important">
                            <thead class="bg-color" style="background-color:#d2e8ff;">
                                <tr>
                                    <td><h4 style="font-size:22px; color:#031b4e; text-align:center;"> <i class="fa-solid fa-circle-check" style="font-size:20px; color:#1e58a3; text-transform: uppercase;"></i>  DAY {{ $day['day'] }} ({{ $day['division'] }})({{$day['division_date']}})<h4></td>
                                </tr>
                            </thead>
                            <tbody>

                                @if($key < count($day_itinerary))
                                <tr>
                                    <td >
                                        <table>
                                            <tr>
                                                <td>
                                                    <span style="background:rgba(238, 51, 94, 0.2); color:rgb(238, 51, 94); border:1px solid rgb(238, 51, 94); display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:7px; line-height: 1;">
                                                        <i class="fas fa-hotel"></i> Hotel | in {{ $day['division'] }}
                                                    </span>
                                                </td>
                                            </tr>
                                           
                                            <tr>
                                                <td style="width:70%; vertical-align:top;">
                                                    <table style="border:1px solid #ccc;">
                                                        <tr>
                                                            <td>
                                                                <img src="{{ optional($day['hotel'])['image'] 
                                                                    ? asset(optional($day['hotel'])['image']) 
                                                                    : asset('build/assets/images/logo/demo.webp') }}"
                                                                    style="width:225px; height:120px; object-fit:cover;">
                                                            </td>


                                                            <td style="vertical-align:top;">
                                                                <h5 style="font-size:15px; text-transform: uppercase; color: #031b4e;">{{$day['hotel']['name']}}<h5>
                                                                <p style="font-size:12px; color:#000; margin-bottom:15px;">{{$day['hotel']['address']}}</p>
                                                            <p>
                                                                <span style="background:rgba(238, 51, 94, 0.2); color:rgb(238, 51, 94); border:1px solid rgb(238, 51, 94); display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:4px; line-height: 1;">
                                                                    ROOMS
                                                                </span>
                                                            </p>

                                                            <span style=" color:#4e4e4e; border:1px solid #ddd; display: inline-block; font-size: 12px; text-transform: uppercase; border-radius: 5px; padding:4px; line-height: 1;">{{$day['hotel_room']['name']}} : 
                                                                <i style="color:#ee335e;">
                                                                  <strong>({{$itinerary['number_of_rooms']}})</strong>
                                                                </i>
                                                            </span>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            @php
                                                                $hasMealPlan = !empty($day['pax_with_adults']);
                                                                $hasCNB = !empty($day['cnb']);
                                                                $hasCWM = !empty($day['cwm']);
                                                                $hasExtraMattress = !empty($day['extra_mattress']);
                                                            @endphp

                                                            <td colspan="2">
                                                                <table style="border: 1px solid rgba(1, 98, 232, 0.5) !important; width:100%;">
                                                                    <tr>
                                                                        <td style="padding:0 !important;">
                                                                            <table style="table-layout:fixed;">
                                                                                <thead style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1);
                                                                                    color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important;">
                                                                                    <tr>
                                                                                        @if($hasMealPlan)
                                                                                            <th style="font-size:11px; text-transform:uppercase; color:#031b4e; padding:10px;">
                                                                                                Meal Plan
                                                                                            </th>
                                                                                        @endif

                                                                                        @if($hasCNB)
                                                                                            <th style="font-size:11px; text-transform:uppercase; color:#031b4e; padding:10px;">
                                                                                                CNB
                                                                                            </th>
                                                                                        @endif

                                                                                        @if($hasCWM)
                                                                                            <th style="font-size:11px; text-transform:uppercase; color:#031b4e; padding:10px;">
                                                                                                CWM
                                                                                            </th>
                                                                                        @endif

                                                                                        @if($hasExtraMattress)
                                                                                            <th style="font-size:11px; text-transform:uppercase; color:#031b4e; padding:10px;">
                                                                                                Extra Mattress
                                                                                            </th>
                                                                                        @endif
                                                                                    </tr>
                                                                                </thead>

                                                                                <tbody>
                                                                                    <tr style="text-align:center">
                                                                                        @if($hasMealPlan)
                                                                                            <td style="vertical-align:top;">
                                                                                                <p>
                                                                                                    <span style="color:#031b4e; border:1px solid #ddd; display:inline-block;
                                                                                                        font-size:11px; text-transform:uppercase; border-radius:5px;
                                                                                                        padding:4px 6px; line-height:1;">
                                                                                                        {{ $day['pax_with_adults']['name'] }}
                                                                                                    </span>
                                                                                                </p>
                                                                                            </td>
                                                                                        @endif

                                                                                        @if($hasCNB)
                                                                                            <td style="vertical-align:top;">
                                                                                                @foreach($day['cnb'] as $cnb)
                                                                                                    <p>
                                                                                                        <span style="color:#031b4e; border:1px solid #ddd; display:inline-block;
                                                                                                            font-size:11px; text-transform:uppercase; border-radius:5px;
                                                                                                            padding:4px 6px; line-height:1;">
                                                                                                            {{ $cnb['name'] }}
                                                                                                            <i style="color:#ee335e;">
                                                                                                                ({{ $cnb['quantity'] }})
                                                                                                            </i>
                                                                                                        </span>
                                                                                                    </p>
                                                                                                @endforeach
                                                                                            </td>
                                                                                        @endif

                                                                                        @if($hasCWM)
                                                                                            <td style="vertical-align:top;">
                                                                                                @foreach($day['cwm'] as $cwm)
                                                                                                    <p>
                                                                                                        <span style="color:#031b4e; border:1px solid #ddd; display:inline-block;
                                                                                                            font-size:11px; text-transform:uppercase; border-radius:5px;
                                                                                                            padding:4px 6px; line-height:1;">
                                                                                                            {{ $cwm['name'] }}
                                                                                                            <i style="color:#ee335e;">
                                                                                                                ({{ $cwm['quantity'] }})
                                                                                                            </i>
                                                                                                        </span>
                                                                                                    </p>
                                                                                                @endforeach
                                                                                            </td>
                                                                                        @endif

                                                                                        @if($hasExtraMattress)
                                                                                            <td style="vertical-align:top;">
                                                                                                @foreach($day['extra_mattress'] as $extra)
                                                                                                    <p>
                                                                                                        <span style="color:#031b4e; border:1px solid #ddd; display:inline-block;
                                                                                                            font-size:11px; text-transform:uppercase; border-radius:5px;
                                                                                                            padding:4px 6px; line-height:1;">
                                                                                                            {{ $extra['name'] }}
                                                                                                            <i style="color:#ee335e;">
                                                                                                                ({{ $extra['quantity'] }})
                                                                                                            </i>
                                                                                                        </span>
                                                                                                    </p>
                                                                                                @endforeach
                                                                                            </td>
                                                                                        @endif
                                                                                    </tr>
                                                                                </tbody>

                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                               
                                                <td style="vertical-align: top;">
                                                    <div style="background: rgba(254, 215, 170, 0.2); border: 1px solid #fed7aa; padding: 20px; max-width: 419px;">
                                                        
                                                        <ul class="total-ex-list">
                                                            @php 
                                                                $day_total_price = 0; 
                                                            @endphp
                                                            
                                                            @foreach ($day_wise_amount_data[$key] as $day_wise_amount)
                                                                @if($day_wise_amount->total_price > 0)
                                                                    @php
                                                                        $day_total_price += $day_wise_amount->total_price;
                                                                        $fieldLabel = ucwords(str_replace('_', ' ', $day_wise_amount->field));
                                                                        $unitPrice = $day_wise_amount->total_quantity > 0 
                                                                            ? round($day_wise_amount->total_price / $day_wise_amount->total_quantity, 2) 
                                                                            : 0;
                                                                    @endphp

                                                                    @if(!in_array($day_wise_amount->field, ['day_sightseeing', 'day_activity', 'day_cab']))
                                                                        <li>
                                                                            <i class="fa-solid fa-circle-arrow-right"></i>
                                                                            @if($fieldLabel === "Day Room Main Plan")
                                                                                @php
                                                                                    $unitPrice = $day_wise_amount->total_quantity > 0 
                                                                                        ? $day_wise_amount->total_price / $leadData->number_of_rooms
                                                                                        : 0;
                                                                                @endphp
                                                                                {{ $fieldLabel }} ({{ $leadData->number_of_rooms }} * {{ $unitPrice }}) = {{ env('DEFAULT_CURRENCY_SYMBOL') }}{{ $day_wise_amount->total_price }}
                                                                            @else
                                                                                {{ $fieldLabel }} ({{ $day_wise_amount->total_quantity }} * {{ $unitPrice }}) = {{ env('DEFAULT_CURRENCY_SYMBOL') }}{{ $day_wise_amount->total_price }}
                                                                            @endif
                                                                        </li>
                                                                    @else
                                                                        <li>
                                                                            <i class="fa-solid fa-circle-arrow-right"></i>
                                                                            {{ $fieldLabel }} = {{ env('DEFAULT_CURRENCY_SYMBOL') }}{{ $day_wise_amount->total_price }}
                                                                        </li>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        </ul>

                                                        @if($day_total_price > 0)
                                                            <hr style="border: 1px solid #fed7aa; background-color:#fed7aa; margin-top: 16px; margin-bottom: 10px;">

                                                            <h3 style="font-size: 13px;">
                                                                Total Amount: {{ env('DEFAULT_CURRENCY_SYMBOL') }}{{ number_format($day_total_price) }}
                                                            </h3>
                                                        @endif
                                                    </div>

                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                @endif

                                <tr >
                                    <td style="vertical-align:top;" >
                                        <table>
                                            <tr>
                                                <td>
                                                    <span style="background:rgba(238, 51, 94, 0.2); color:rgb(238, 51, 94); border:1px solid rgb(238, 51, 94); display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:7px; line-height: 1;">
                                                        <i class="fas fa-route"></i> Route | <i class="fas fa-binoculars"></i> Sight | <i class="fas fa-running"></i> Activity |  seen in Port Blair
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align:top;">
                                                    <table style="border: 1px solid rgba(1, 98, 232, 0.5) !important;">
                                                        <thead style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); color:#031b4e; width:180px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; text-align:left;">
                                                            <tr>
                                                                <th style="font-size:11px; text-transform:uppercase; color:#031b4e; padding: 10px; width:65px; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">SL.No</th>
                                                                <th style="font-size:11px; text-transform:uppercase; color:#031b4e; padding: 10px; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Route</th>
                                                                <th style="font-size:11px; text-transform:uppercase; color:#031b4e; padding: 10px; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">sightseeing</th>
                                                                <th style="font-size:11px; text-transform:uppercase; color:#031b4e; padding: 10px;">Activity</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($day['route'] ?? [] as $routeIndex => $route)
                                                                {{-- MAIN ROUTE ROW --}}
                                                                <tr style="border-bottom:1px solid #ccc !important;">
                                                                    {{-- SL NO --}}
                                                                    <td rowspan="{{ (!empty($route['cabs']) ? 2 : 1) }}"
                                                                        style="vertical-align:top; border-right:1px solid #ccc !important;">
                                                                        <span style="border:1px solid rgba(34,192,60,.8);
                                                                            background:rgba(34,192,60,.1); color:#22c03c;
                                                                            border-radius:4px; padding:2px 3px; font-size:11px;">
                                                                            {{ $routeIndex + 1 }}
                                                                        </span>
                                                                    </td>

                                                                    {{-- ROUTE NAME --}}
                                                                    <td style="vertical-align:top; border-right:1px solid #ccc !important;">
                                                                        <h6 style="font-size:14px; text-transform:uppercase; color:#031b4e; margin-bottom:5px;">
                                                                            {{ $route['name'] ?? '-' }}
                                                                        </h6>
                                                                    </td>

                                                                    {{-- SIGHTSEEING --}}
                                                                    <td style="vertical-align:top; border-right:1px solid #ccc !important;">
                                                                        @forelse($route['sightseeings'] ?? [] as $sight)
                                                                            <p>
                                                                                <span style="color:#031b4e; border:1px solid #ddd;
                                                                                    display:inline-block; font-size:11px;
                                                                                    border-radius:5px; padding:4px 6px;">
                                                                                    {{ $sight['name'] }}
                                                                                    <i style="color:#ee335e;">({{ $sight['quantity'] }})</i>
                                                                                </span>
                                                                            </p>
                                                                        @empty
                                                                            —
                                                                        @endforelse
                                                                    </td>

                                                                    {{-- ACTIVITY --}}
                                                                    <td style="vertical-align:top;">
                                                                        @forelse($route['activitys'] ?? [] as $activity)
                                                                            <p>
                                                                                <span style="color:#031b4e; border:1px solid #ddd;
                                                                                    display:inline-block; font-size:11px;
                                                                                    border-radius:5px; padding:4px 6px;">
                                                                                    {{ $activity['name'] }}
                                                                                    <i style="color:#ee335e;">({{ $activity['quantity'] }})</i>
                                                                                </span>
                                                                            </p>
                                                                        @empty
                                                                            —
                                                                        @endforelse
                                                                    </td>
                                                                </tr>

                                                                {{-- CAB ROW --}}
                                                                @if(!empty($route['cabs']))
                                                                <tr style="border-bottom:1px solid #ccc;">
                                                                    <td colspan="4">
                                                                        <table>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td>
                                                                                        <ul style="display:flex; align-items:center; flex-wrap:wrap; padding:0; margin:0;">
                                                                                            @foreach($route['cabs'] as $cab)
                                                                                                <li style="list-style:none; text-align:center; width:180px;
                                                                                                    border:1px solid #ccc; margin:6px; padding:18px 0;">
                                                                                                    
                                                                                                    <img width="80"
                                                                                                        src="{{ $cab['image'] ?? asset('assets/img/cab.png') }}"
                                                                                                        alt="{{ $cab['name'] }}"
                                                                                                        style="margin:auto;">

                                                                                                    <p>
                                                                                                        <span style="color:#22c03c; border:1px solid #ddd;
                                                                                                            display:inline-block; font-size:11px;
                                                                                                            text-transform:uppercase; border-radius:5px;
                                                                                                            padding:4px 6px; background:rgba(34,192,60,.1);">
                                                                                                            {{ $cab['name'] }}
                                                                                                            <i style="color:#ee335e;">({{ $cab['quantity'] }})</i>
                                                                                                        </span>
                                                                                                    </p>
                                                                                                </li>
                                                                                            @endforeach
                                                                                        </ul>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </td>
                                                 @if($key == count($day_itinerary))
                                                    <td style="vertical-align: top;">
                                                        <div style="background: rgba(254, 215, 170, 0.2); border: 1px solid #fed7aa; padding: 20px; max-width: 419px;">
    
                                                            <ul class="total-ex-list">
                                                                @php 
                                                                    $day_total_price = 0; 
                                                                @endphp
                                                                
                                                                @foreach ($day_wise_amount_data[$key] as $day_wise_amount)
                                                                    @if($day_wise_amount->total_price > 0)
                                                                        @php
                                                                            $day_total_price += $day_wise_amount->total_price;
                                                                            $fieldLabel = ucwords(str_replace('_', ' ', $day_wise_amount->field));
                                                                            $unitPrice = $day_wise_amount->total_quantity > 0 
                                                                                ? round($day_wise_amount->total_price / $day_wise_amount->total_quantity, 2) 
                                                                                : 0;
                                                                        @endphp

                                                                        @if(!in_array($day_wise_amount->field, ['day_sightseeing', 'day_activity', 'day_cab']))
                                                                            <li>
                                                                                <i class="fa-solid fa-circle-arrow-right"></i>
                                                                                @if($fieldLabel === "Day Room Main Plan")
                                                                                    @php
                                                                                        $unitPrice = $day_wise_amount->total_quantity > 0 
                                                                                            ? $day_wise_amount->total_price / $leadData->number_of_rooms
                                                                                            : 0;
                                                                                    @endphp
                                                                                    {{ $fieldLabel }} ({{ $leadData->number_of_rooms }} * {{ $unitPrice }}) = {{ env('DEFAULT_CURRENCY_SYMBOL') }}{{ $day_wise_amount->total_price }}
                                                                                @else
                                                                                    {{ $fieldLabel }} ({{ $day_wise_amount->total_quantity }} * {{ $unitPrice }}) = {{ env('DEFAULT_CURRENCY_SYMBOL') }}{{ $day_wise_amount->total_price }}
                                                                                @endif
                                                                            </li>
                                                                        @else
                                                                            <li>
                                                                                <i class="fa-solid fa-circle-arrow-right"></i>
                                                                                {{ $fieldLabel }} = {{ env('DEFAULT_CURRENCY_SYMBOL') }}{{ $day_wise_amount->total_price }}
                                                                            </li>
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                            </ul>

                                                            @if($day_total_price > 0)
                                                                <hr style="border: 1px solid #fed7aa; background-color:#fed7aa; margin-top: 16px; margin-bottom: 10px;">

                                                                <h3 style="font-size: 13px;">
                                                                    Total Amount: {{ env('DEFAULT_CURRENCY_SYMBOL') }}{{ number_format($day_total_price) }}
                                                                </h3>
                                                            @endif
                                                        </div>

                                                    </td>
                                                @endif
                                            </tr>
                                        </table>
                                    </td>
                                      {{-- {{dd($day)}} --}}
                                </tr>
                               
                            </tbody>
                        </table>
                    @endforeach
                </td>
            </tr>

            <tr>
                <td>
                    <table style="border:1px solid rgba(1, 98, 232, 0.5) !important">
                        <thead style="background-color:#d2e8ff;">
                            <tr>
                                <th>
                                    <h4 style="font-size:22px; color:#031b4e; text-align:center;"> <i class="fa-solid fa-circle-check" style="font-size:20px; color:#1e58a3;"></i> Hotel Summery </h4>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <ul class="hotel-list">
                                        @foreach($day_itinerary as $key=> $day)
                                            @if($key < count($day_itinerary))
                                                <li>
                                                    <figure>
                                                        <img src="{{ optional($day['hotel'])['image'] 
                                                                        ? asset(optional($day['hotel'])['image']) 
                                                                        : asset('build/assets/images/logo/demo.webp') }}">
                                                    </figure>
                                                    <figcaption>
                                                        <h3>Day {{$key}}</h3>
                                                        <h2>{{$day['hotel']['name']}}</h2>
                                                        <p>{{$day['hotel']['address']}}</p>
                                                    </figcaption>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>


            <tr>
                <td>
                    <table style="border:1px solid rgba(1, 98, 232, 0.5) !important; width:100%;">
                        <thead style="background-color:#d2e8ff;">
                            <tr>
                                <th>
                                    <h4 style="font-size:22px; color:#031b4e; text-align:center;">
                                        <i class="fa-solid fa-circle-check"
                                        style="font-size:20px; color:#1e58a3;"></i>
                                        Activities Summary
                                    </h4>
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                        @foreach($day_itinerary as $dayData)
                            @php
                                $day = $dayData['day'];

                                // Check if this day has at least one activity
                                $hasActivity = collect($dayData['route'])
                                    ->pluck('activitys')
                                    ->flatten(1)
                                    ->isNotEmpty();
                            @endphp

                            @if($hasActivity)
                                {{-- DAY HEADING --}}
                                <tr>
                                    <td style="padding:10px; background:#f4f8ff;">
                                        <h3 style="margin:0; color:#1e58a3;">
                                            Day {{ $day }}
                                        </h3>
                                    </td>
                                </tr>

                                {{-- ACTIVITIES --}}
                                <tr>
                                    <td>
                                        <ul class="activity-list">
                                            @foreach($dayData['route'] as $route)
                                                @foreach($route['activitys'] as $activity)
                                                    <li style="
                                                        background:url('{{ $activity['image'] }}');
                                                        background-repeat:no-repeat;
                                                        background-size:cover;
                                                        background-position:center center;
                                                    ">
                                                        <figcaption>
                                                            <h2>{{ $activity['name'] }}</h2>
                                                        </figcaption>
                                                    </li>
                                                @endforeach
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @endif
                        @endforeach

                        </tbody>
                    </table>
                </td>
            </tr>


            <tr>
                <td>
                    <table style="border:1px solid rgba(1, 98, 232, 0.5) !important">
                        <thead style="background-color:#d2e8ff;">
                            <tr>
                                <th>
                                    <h4 style="font-size:22px; color:#031b4e; text-align:center;">
                                        <i class="fa-solid fa-circle-check" style="font-size:20px; color:#1e58a3;"></i>
                                        Exclusions
                                    </h4>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    @foreach($itinerary['exclusions'] as $index => $exclusion)
                                        <p>
                                            <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; border-radius: 5px; padding:4px 6px; line-height: 1; margin-bottom:5px;">
                                                {{ $index + 1 }}. {{ $exclusion }}
                                            </span>
                                        </p>
                                    @endforeach
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>


            <tr class="keep-together">
                <td>
                    <table style="border:1px solid rgba(1, 98, 232, 0.5) !important">
                        <thead style="background-color:#d2e8ff;">
                            <tr>
                                <th>
                                    <h4 style="font-size:22px; color:#031b4e; text-align:center;">
                                        <i class="fa-solid fa-circle-check" style="font-size:20px; color:#1e58a3;"></i>
                                        Inclusions
                                    </h4>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    @foreach($itinerary['inclusions'] as $index => $inclusion)
                                        <p>
                                            <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; border-radius: 5px; padding:4px 6px; line-height: 1; margin-bottom:5px;">
                                                {{ $index + 1 }}. {{ $inclusion }}
                                            </span>
                                        </p>
                                    @endforeach
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>

</body>
</html>
