<div>
    <style>
        .overlay{
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index:1000;
        }
        /* 26-06-2025 */
        .itinerary_box_header_custom {
            position: relative;
            top: auto;
        }
        .itinerary_box_header_custom .right-column .logo {
            width: 100%;
            max-width: 120px;
        }
        .itinerary_box_header_custom .right-column .logo img {
            width: 100%;
        }
        .bg-opacity-0 {
           background: transparent; 
        }
        .text-white {
            color: #ffffff !important;
        }
        .draggable-list li + li {
            margin-left: 0 !important;
            margin-top: 4px !important;
        }
        .new-activity tr th.text-white {
            color: #ffffff !important;
        }
        .room-capacity-table thead tr th {
            padding-left: 8.8px !important;
            padding-right: 8.8px !important;
        }
        .room-capacity-table thead tr th:last-child {
            border-top-right-radius: .5rem; 
        }
        .room-capacity-table tbody tr td:last-child {
            border-right: 1px solid rgba(0, 185, 255) !important;
        }
        .room-capacity-table tbody tr:last-child td:first-child {
            border-bottom-left-radius: 0.5rem;
        }
        .room-capacity-table tbody tr:last-child td:last-child {
            border-bottom-right-radius: 0.5rem;
        }
        .room-capacity-table .cab-card {
            margin: 0 !important;   
        }
        .change_button {
            top: 7px;
            right: 8px;
            font-size: 10px;
            z-index: 2;
        }
        .custom-hotel-details {
            margin-left: 0;
        }
        .package_budget{
            padding: 3px 8px;
            color: #032970 !important;
            background: #fff;
            font-size: 13px !important;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('build/assets/libs/dragula/dragula.min.css') }}">
    <div class="grid grid-cols-12 gap-6">
        <div class="xl:col-span-12 col-span-12">
            <div class="box custom-box">
                <div class="mt-2 mx-2 mb-1">
                    @if (session('success'))
                        <div id="success-message" class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {!! session('error') !!}
                        </div>
                    @endif

                </div>
                <div class="box-header itinerary_box_header itinerary_box_header_custom">
                    {{-- <div>
                        <a href="{{route('admin.itinerary.preset.builder',[$destinationId,$categoryId])}}" class="ti-btn ti-btn-sm ti-btn-soft-danger !border !border-danger/20">
                            <i class="ri-refresh-line"></i>
                        </a>
                    </div> --}}
                    <div class="flex justify-between pb-2">
                        <div class="flex left-column">
                            <div class="bg-orange-200 rounded-lg shadow-md w-fit p-2 text-black-600 text-[13px] italic">
                                <div class="px-2 flex justify-between">
                                    @php
                                    $encryptedId = Crypt::encrypt($itinerary_id);
                                    @endphp
                                    <p class="text-lg font-semibold uppercase">Itinerary is for:</p>
                                    @if($leadData->status!=="Confirmed")
                                        <a href="{{route('admin.cost_calculator.query_edit',$encryptedId)}}" class="ti-btn ti-btn-sm ti-btn-teal !border !border-success" title="Edit Itinerary">
                                            <i class="ti ti-pencil"></i>
                                        </a>
                                    @endif
                                </div>
                                <div class="flex">
                                    <div class="px-2">
                                        <p class="text-md font-semibold mt-2">Client: <span class="font-normal">{{$leadData->customer_name}}({{$leadData->nationality_type}})</span></p>
                                        <p class="text-md font-semibold mt-2">Client Email: <span class="font-normal">{{$leadData->customer_email}}</span></p>
                                        <p class="text-md font-semibold mt-2">Client Mobile: <span class="font-normal">+{{$leadData->country_code}}{{$leadData->customer_mobile}}</span></p>
                                        <p class="text-md font-semibold mt-2">Travel Date: <span class="font-normal">{{date('d-m-Y',strtotime($leadData->arrival_date))}}</span></p>
                                    </div>
                                    <div class="px-2">
                                        <p class="text-md font-semibold mt-2">{{$destinationName}} Trip: <span class="font-normal">{{$itinerary_syntax}}</span></p>
                                        <p class="text-md font-semibold">PAX: <span class="font-normal">{{$leadData->number_of_travellor}}</span></p>
                                        <p class="text-md font-semibold">Adults: <span class="font-normal">{{$leadData->number_of_adults}}</span></p>
                                        <p class="text-md font-semibold">Childs: <span class="font-normal">{{$leadData->number_of_children}}</span></p>
                                        @if($leadData->extra_mattress)
                                            <p class="text-md font-semibold">Extra Mattress: <span class="font-normal">{{$leadData->extra_mattress}}</span></p>
                                        @endif
                                        <p class="text-md font-semibold">Rooms: <span class="font-normal">{{$leadData->number_of_rooms}}</span></p>
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                        <div class="flex flex-col right-column">
                            <div class="logo">
                                <img src="https://christmastree.quickdemo.in/assets/img/CarnivalTripsLogo.png" alt="">
                            </div>
                            <div class="flex flex-col border !border-success rounded-sm w-full mt-3">
                                <p class="text-xs text-white uppercase bg-teal-500 rounded-t-sm w-full pt-1 pb-1 ps-3 pe-3">Destination</p>
                                <div class="!text-sm !font-normal text-primary uppercase w-full cursor-pointer pt-1 pb-1 ps-3 pe-3">
                                    <span> {{$destinationName}}</span>
                                </div>
                            </div>
                            <div class="flex flex-col border !border-success rounded-sm w-full mt-2">
                                <p class="text-xs text-white uppercase bg-teal-500 rounded-t-sm w-full pt-1 pb-1 ps-3 pe-3">Hotel Category</p><hr>
                                <div class="!text-sm !font-normal text-primary uppercase w-full cursor-pointer pt-1 pb-1 ps-3 pe-3">
                                    <span> {{$categoryName}}</span>
                                </div>
                            </div>
                            {{-- <div class="mx-2">
                                <p class="badge gap-2 bg-danger/10 text-danger uppercase">Hotel Category</p><hr>
                                <div class="badge bg-outline-primary cursor-pointer !font-normal !text-sm uppercase">
                                    <span>    {{$categoryName}}</span>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <form wire:submit.prevent="submitForm">
                        <div class="table-responsive mb-2">
                            <table class="table min-w-full new-activity !mb-3">
                                <thead class="uppercase">
                                    <tr>
                                        <th scope="col" class="bg-blue-500 rounded-s-md">
                                            <div class="flex items-center whitespace-nowrap ps-2">
                                                <div class="text-white">
                                                    Name Of Itinerary
                                                </div>
                                                <div class="nd-field">
                                                    <input type="text" wire:model="itinerary_syntax" class="form-control form-control-sm text-center bg-opacity-0 text-white" readonly>
                                                </div>
                                            </div>
                                        </th>
                                        <th class="bg-blue-500">
                                            <div class="flex items-center whitespace-nowrap">
                                                <div class="text-white">Night Halt Details</div>
                                                <div class="w-2/3 ms-3">
                                                    <input type="text" wire:model="selected_night_halt" class="form-control form-control-sm text-center bg-opacity-0 text-white w-full" readonly>
                                                </div>
                                            </div>
                                        </th>
                                        <th scope="col" class="bg-blue-500 rounded-e-md w-1/5">
                                           
                                            <div class="flex">
                                                <div class="text-right w-full pe-2">
                                                     <button type="button" class="badge bg-outline-secondary badge-custom-outline-secondary package_budget" wire:click="changePackageBudget">
                                                        Package budget
                                                        @if($package_budget==="max")
                                                            <span class="badge bg-danger ms-2 text-white">Maximum</span>
                                                        @else
                                                            <span class="badge bg-success ms-2 text-white">Minimum</span>
                                                        @endif
                                                    </button>
                                                </div>
                                                <div class="text-right w-full pe-2">
                                                    <a href="javascript:void(0)" wire:click="ResetItinerary" class="inline-block font-normal text-xs text-primary uppercase border border-white bg-white cursor-pointer rounded-sm px-4 py-1">RESET</a>
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                  
                                    @foreach($day_by_divisions as $division_index=>$division_item)
                                        <table class="table min-w-full new-activity !mb-3" style="border-collapse: separate;border-spacing: 0;">
                                            <thead class="bg-primary/20">
                                                <tr>
                                                    <th class="border !border-warning bg-orange-300 rounded-t-md !text-center uppercase" style="border-top-left-radius: .5rem; border-top-right-radius: .5rem;"><span class="text-xl text-defaulttextcolor">Day {{$division_index}} ({{$division_item['division_name']}})({{$division_item['division_date']}})</span></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="border !border-warning rounded-b-md">
                                                        <div class="grid grid-cols-12 sm:gap-x-4 sm:gap-y-4">
                                                            <div class="md:col-span-9 col-span-12">
                                                                <table class="table min-w-full mt-4" style="border-collapse: separate;border-spacing: 0;">
                                                                <thead class="bg-primary/5">
                                                                    <tr>
                                                                        <th class="!text-left uppercase text-white bg-teal-500 border !border-success" width="3%" style="border-top-left-radius: .5rem; padding-left: 8.8px !important; padding-right: 8.8px !important;">SL.</th>
                                                                        <th class="!text-left uppercase text-white bg-teal-500 border !border-success !border-s-0" width="20%" style="padding-left: 8.8px !important; padding-right: 8.8px !important;">ROUTE</th>
                                                                        <th class="!text-left uppercase text-white bg-teal-500 border !border-success !border-s-0" width="37%" style="padding-left: 8.8px !important; padding-right: 8.8px !important;">SIGHTSEEINGS</th>
                                                                        <th class="!text-left uppercase text-white bg-teal-500 border !border-success !border-s-0" width="37%" style="padding-left: 8.8px !important; padding-right: 8.8px !important;">ACTIVITY</th>
                                                                        <th class="!text-left uppercase text-white bg-teal-500 border !border-success !border-s-0" width="3%" style="border-top-right-radius: .5rem; padding-left: 8.8px !important; padding-right: 8.8px !important;">
                                                                            <button type="button" wire:click="OpenAssignRouteModal({{$division_index}})" class="ti-btn ti-btn-sm ti-btn-soft-light !border !border-light/20 mb-0">
                                                                                <i class="fa-solid fa-plus text-lg text-dark"></i>
                                                                            </button>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                 
                                                                    @forelse ($division_item['day_route'] as $route_index=>$division_route_item)
                                                                        <tr wire:key="day-{{ $division_index }}-route-{{ $division_route_item['route_service_summary_id'] }}">
                                                                            <td class="align-top border !border-success !border-t-0" rowspan="2">
                                                                                <span class="badge bg-primary/10 text-primary">{{$route_index+1}}</span>
                                                                            </td>
                                                                            <td class="align-top border !border-success !border-s-0 !border-t-0">
                                                                               <span class="uppercase"> {{ $division_route_item['route_name'] }}</span>
                                                                               {{-- @if (!empty($division_route_item['route_way_points']))
                                                                                    <div class="sortable-list border-2 border-dashed border-gray-400 p-2 rounded-lg bg-white dark:bg-gray-800 shadow-md">
                                                                                        <ul class="list-none space-y-2 space-x-1 text-xs">
                                                                                            @foreach ($division_route_item['route_way_points'] as $way_index => $waypoint)
                                                                                                <li class="draggable-item flex items-center justify-start bg-gray-100 dark:bg-gray-700 p-1 rounded-md cursor-move">
                                                                                                    @if ($way_index == 0)
                                                                                                        <span class="text-green-600 mx-2">
                                                                                                            <i class="ri-map-pin-2-fill"></i>
                                                                                                        </span>
                                                                                                    @endif
                                                                                                    @if (!$loop->last && $way_index != 0)
                                                                                                        <span class="mx-2 text-red-600">→</span> <!-- Arrow between waypoints -->
                                                                                                    @endif
                                                                                                    @if ($loop->last)
                                                                                                        <span class="text-blue-600 mx-2">
                                                                                                            <i class="ri-flag-fill"></i> <!-- Remix Icon for End -->
                                                                                                        </span>
                                                                                                    @endif
                                                                                                    <span class="text-gray-600 dark:text-white/70 text-[10px] italic"> {{ ucwords($waypoint['point_name']) }}</span>
                                                                                                </li>
                                                                                            @endforeach
                                                                                        </ul>
                                                                                    </div>
                                                                                @endif --}}
                                                                            </td>
                                                                            <td class="align-top border !border-success !border-s-0 !border-t-0">
                                                                                <div class="flex flex-col w-full">
                                                                                    <div class="flex w-full mb-1">
                                                                                        <div class="w-full">
                                                                                            @php
                                                                                                $daySightseeings = isset($division_route_item['day_sightseing'])?collect($division_route_item['day_sightseing'])->pluck('value')->toArray():[];
                                                                                            @endphp
                                                                                            <select wire:key="existing-sightseeing-{{$route_index}}-{{ $division_index }}-100" 
                                                                                            wire:change="getActivityOrSightseeing('sightseeing',{{$division_index}},{{ $route_index }}, {{ $division_route_item['route_service_summary_id'] }}, $event.target.value, '0', $event.target.selectedOptions[0].getAttribute('data-ticket_price'),'')" class="text-xs rounded-sm w-full py-0">
                                                                                                 <option value="">Choose an Existing Sightseeing</option>
                                                                                                 @forelse ($division_route_item['existing_sightseeings'] as $existing_sightseeing)
                                                                                                    @if(!in_array($existing_sightseeing['name'], $daySightseeings))
                                                                                                        <option value="{{$existing_sightseeing['name']}}" data-ticket_price="{{round($existing_sightseeing['ticket_price'])}}">{{$existing_sightseeing['name']}} - TP: {{env('DEFAULT_CURRENCY_SYMBOL')}}{{round($existing_sightseeing['ticket_price']) }}</option>
                                                                                                    @endif
                                                                                                 @empty
                                                                                                     <option value="" disabled>No sightseeings available</option>
                                                                                                 @endforelse
                                                                                            </select>
                                                                                        </div>
                                                                                        @if(isset($errorSightSeeing[$division_index][$route_index]))
                                                                                            <span class="text-red-500">{{ $errorSightSeeing[$division_index][$route_index] }}</span>
                                                                                        @endif
                                                                                    </div>
                                                                                    <div class="flex w-full mb-2">
                                                                                        <div class="small-btm my-0 cursor-pointer">
                                                                                           <span class="badge gap-2 bg-primary/10 text-primary uppercase" wire:click="OpenNewSightseeingModal({{$division_index}},{{$division_route_item['route_service_summary_id']}},{{$division_item['division_id']}})"> <i class="fas fa-plus-circle text-blue-500 text-base"></i> New Sightseeing</span>
                                                                                        </div>
                                                                                        {{-- Model For New Sightseeing  --}}
                                                                                        <div class="hs-overlay {{ ($active_new_sightseeing_modal == $division_index && $active_route_new_sightseeing_modal == $division_route_item['route_service_summary_id']) ? '' : 'hidden' }} fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                                                                            <div class="hs-overlay-open:mt-7 ti-modal-box mt-0 ease-out lg:!max-w-4xl lg:w-full m-3 lg:!mx-auto modal_lg_sm_width bg-white rounded-lg">
                                                                                                <div class="ti-modal-content p-20">
                                                                                                    <div class="ti-modal-header flex justify-between items-center">
                                                                                                        <span class="uppercase"> {{ $division_route_item['route_name'] }}</span>
                                                                                                        <button type="button" class="text-gray-400 hover:text-gray-600 focus:outline-none badge gap-2 bg-danger/10 text-danger" wire:click="OpenNewSightseeingModal({{$division_index}},{{$division_route_item['route_service_summary_id']}},{{$division_item['division_id']}})">
                                                                                                            <i class="fa-solid fa-xmark text-lg text-dark"></i>
                                                                                                        </button>
                                                                                                    </div>
                                                                                                    <div class="ti-modal-body text-start">
                                                                                                        <div class="flex items-center mb-2">
                                                                                                            <div class="mx-1">
                                                                                                                <label>
                                                                                                                    <span class="badge gap-2 bg-primary/10 text-primary uppercase">
                                                                                                                        Assign New sightseeings
                                                                                                                    </span>
                                                                                                                </label>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        
                                                                                                            <div class="table-responsive">
                                                                                                                <table class="table whitespace-nowrap table-bordered table-bordered-primary border-primary/10 min-w-full new-activity">
                                                                                                                    <thead class="uppercase">
                                                                                                                        <tr class="border-b !border-primary/30">
                                                                                                                            <th scope="col" class="!text-center w-1/2">Sightseeing Point</th> <!-- Example for 50% width -->
                                                                                                                            <th scope="col" class="!text-center w-1/10">Ticket Price (PP)</th> <!-- Example for 10% width -->
                                                                                                                            <th scope="col" class="!text-center w-1/20">
                                                                                                                                <button type="button" wire:click.prevent="addSightseeing" class="ti-btn ti-btn-sm ti-btn-soft-success !border !border-success/20">
                                                                                                                                    <i class="fa-solid fa-plus text-lg text-dark"></i>
                                                                                                                                </button>
                                                                                                                            </th>
                                                                                                                        </tr>
                                                                                                                    </thead>
                                                                                                                    <tbody>
                                                                                                                        @foreach ($new_sightseeings as $new_sightseeing_index => $new_sightseeing)
                                                                                                                            <tr wire:key="new_sightseeing-{{ $new_sightseeing_index }}">
                                                                                                                                <td class="!text-center">
                                                                                                                                    <div>
                                                                                                                                        <textarea type="text" wire:model="new_sightseeings.{{ $new_sightseeing_index }}.name" class="form-control form-control-sm text-center" placeholder="Enter sightseeing point">
        
                                                                                                                                        </textarea>
                                                                                                                                    </div>
                                                                                                                                    @error('new_sightseeings.' . $new_sightseeing_index . '.name') <span class="text-danger">{{ $message }}</span> @enderror
                                                                                                                                </td>
                                                                                                                                <td class="!text-center border-x-0">
                                                                                                                                    <div class="table-responsive" style="margin: -8px;">
                                                                                                                                        <table class="table whitespace-nowrap min-w-full">
                                                                                                                                            <thead class="bg-warning/10">
                                                                                                                                                <tr class="border-b border-defaultborder">
                                                                                                                                                    <th class="text-center uppercase route_details">INDIAN</th>
                                                                                                                                                    <th class="text-center uppercase route_details">Foreign National</th>
                                                                                                                                                </tr>
                                                                                                                                            </thead>
                                                                                                                                            <tbody>
                                                                                                                                                <tr>
                                                                                                                                                    <td class="text-center route_details">
                                                                                                                                                        <div>
                                                                                                                                                            <input type="text" wire:model="new_sightseeings.{{ $new_sightseeing_index }}.ticket_price" class="form-control form-control-sm text-center {{ isset($new_sightseeings[$new_sightseeing_index]['type']) && $new_sightseeings[$new_sightseeing_index]['type'] === 'UNPAID' ? 'placeholder:text-textmuted' : '' }}" placeholder="Price" onkeyup="validateNumber(this)" {{ isset($new_sightseeings[$new_sightseeing_index]['type']) && $new_sightseeings[$new_sightseeing_index]['type'] === 'UNPAID' ? 'disabled' : '' }}>
                                                                                                                                                        </div>
                                                                                                                                                        @error('new_sightseeings.' . $new_sightseeing_index . '.ticket_price') <span class="text-danger">{{ $message }}</span> @enderror
                                                                                                                                                    </td>
                                                                                                                                                <td class="text-center route_details">
                                                                                                                                                        <div>
                                                                                                                                                            <input type="text" wire:model="new_sightseeings.{{ $new_sightseeing_index }}.ticket_price_nri" class="form-control form-control-sm text-center {{ isset($new_sightseeings[$new_sightseeing_index]['type']) && $new_sightseeings[$new_sightseeing_index]['type'] === 'UNPAID' ? 'placeholder:text-textmuted' : '' }}" placeholder="Price" onkeyup="validateNumber(this)" {{ isset($new_sightseeings[$new_sightseeing_index]['type']) && $new_sightseeings[$new_sightseeing_index]['type'] === 'UNPAID' ? 'disabled' : '' }}>
                                                                                                                                                        </div>
                                                                                                                                                        @error('new_sightseeings.' . $new_sightseeing_index . '.ticket_price_nri') <span class="text-danger">{{ $message }}</span> @enderror
                                                                                                                                                    </td>
                                                                                                                                                </tr>
                                                                                                                                            </tbody>
                                                                                                                                        </table>
                                                                                                                                    </div>
                                                                                                                                    </td>
                                                                                                                                <td class="!text-center border-l-0">
                                                                                                                                    <button type="button" wire:click.prevent="removeSightSeing({{ $new_sightseeing_index }})" class="ti-btn ti-btn-sm ti-btn-soft-danger !border !border-danger/20">
                                                                                                                                        <i class="ti ti-trash"></i>
                                                                                                                                    </button>
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                            <tr  wire:key="new_sightseeing_desc-{{ $new_sightseeing_index }}">
                                                                                                                                <td colspan="3">
                                                                                                                                    <div wire:ignore>
                                                                                                                                        <textarea rows="3"
                                                                                                                                            name="sightseeing_description"
                                                                                                                                            class="form-control form-control-sm text-center sightseeing_description_{{$active_route_new_sightseeing_modal}}_{{$new_sightseeing_index}}"
                                                                                                                                            placeholder="Description">
                                                                                                                                        </textarea>
                                                                                                                                    </div>
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                        @endforeach
                                                                                                                    </tbody>
                                                                                                                </table>
                                                                                                            </div>
                                                                                                        
                                                                                                            @if (session('new-sightseeing-error'))
                                                                                                                <div class="alert alert-danger">
                                                                                                                    {{ session('new-sightseeing-error') }}
                                                                                                                </div>
                                                                                                            @endif
                                                                                                        
                                                                                                            <div class="text-end mt-3">
                                                                                                                <button type="button" wire:click="submitNewSightSeeingForm({{$division_index}},{{$division_route_item['route_service_summary_id']}},{{$division_item['division_id']}})" class="ti-btn ti-btn-primary-full !py-1 pt-0 ti-btn-wave me-[0.375rem]">
                                                                                                                    <i class="fa-solid fa-save"></i> Save
                                                                                                                </button>
                                                                                                            </div>
                                                                                                        
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        {{--End Model For New Sightseeing  --}}
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <!-- Drag & Drop List -->
                                                                                @if(isset($division_route_item['day_sightseing']) && count($division_route_item['day_sightseing'])>0)
                                                                                    <div class="sortable-list border-2 border-dashed border-gray-400 p-1 rounded-lg bg-white dark:bg-gray-800 shadow-md">
                                                                                        <ul id="draggable-list" class="draggable-list space-y-2 space-x-1 text-xs">
                                                                                            @foreach ($division_route_item['day_sightseing'] as $sight_index=>$day_sightseing)
                                                                                                <li class="draggable-item flex items-center justify-between bg-gray-100 dark:bg-gray-700 p-1 rounded-md cursor-move"  wire:key="day-{{ $division_index }}-route-{{ $route_index }}-sightseeing-{{$day_sightseing['id']}}">
                                                                                                    <span class="text-gray-600 dark:text-white/70 text-[10px] italic">{{ucfirst($day_sightseing['value'])}} <strong class="text-blue-600">@if($day_sightseing['price']>0)({{env('DEFAULT_CURRENCY_SYMBOL')}}{{$day_sightseing['price']}})@endif</strong> </span>
                                                                                                    
                                                                                                    <div class="flex items-center space-x-2 justify-center ms-auto">
                                                                                                        <i class="fas fa-minus-circle text-red-500 cursor-pointer text-base"
                                                                                                            wire:click="decreaseQuantity({{$division_index}},{{$day_sightseing['id']}})"></i>
                                                                                                            
                                                                                                            <input type="number"
                                                                                                            id="custom_quantity_input{{$day_sightseing['id']}}" 
                                                                                                            class="custom-quantity-input text-xs leading-none py-0" 
                                                                                                            wire:model="itemQuantity.{{ $division_index }}.{{$day_sightseing['id']}}" 
                                                                                                            wire:keyup="updateQuantity({{$division_index}},{{$day_sightseing['id']}})" 
                                                                                                            min="1" value="{{$day_sightseing['value_quantity']}}"/>
                                                                                                        
                                                                                                        <i class="fas fa-plus-circle text-green-500 cursor-pointer text-base"
                                                                                                            wire:click="increaseQuantity({{$division_index}},{{$day_sightseing['id']}})"></i>
                                                                                                    </div>
                                                                                                    <i class="far fa-times-circle text-primary cursor-pointer ps-1 ms-3" wire:click="RemoveDayRouteItem({{$division_index}},{{$day_sightseing['id']}})"></i>
                                                                                                </li>
                                                                                            @endforeach
                                                                                        </ul>
                                                                                    </div>
                                                                                @endif
                                                                                
                                                                            </td>
                                                                            <td class="align-top border !border-success !border-s-0 !border-t-0">
                                                                                <div class="flex flex-col w-full">
                                                                                    <div class="flex mb-1 w-full">
                                                                                        <div class="w-full">
                                                                                            @php
                                                                                                $dayActivities = isset($division_route_item['day_activity'])?collect($division_route_item['day_activity'])->pluck('value')->toArray():[];
                                                                                            @endphp
                                                                                            <select wire:key="existing-activity-{{$route_index}}-{{ $division_index }}-100" 
                                                                                            wire:change="getActivityOrSightseeing('activity', {{$division_index}},{{ $route_index }}, {{ $division_route_item['route_service_summary_id'] }}, $event.target.value, $event.target.selectedOptions[0].getAttribute('data-price'), $event.target.selectedOptions[0].getAttribute('data-ticket_price'),'')" class="text-xs rounded-sm w-full py-0">
                                                                                                <option value="" hidden>Choose an Existing Activity</option>
                                                                                                @forelse ($division_route_item['existing_activities'] as $existing_activity)
                                                                                                @if(!in_array($existing_activity['name'], $dayActivities))
                                                                                                    <option value="{{ $existing_activity['name'] }}" data-price="{{$existing_activity['price']}}" data-ticket_price="{{$existing_activity['ticket_price']}}">
                                                                                                        {{ $existing_activity['name'] }} - PP: {{env('DEFAULT_CURRENCY_SYMBOL')}}{{ round($existing_activity['price'] )}} - TP: {{env('DEFAULT_CURRENCY_SYMBOL')}}{{round($existing_activity['ticket_price']) }}
                                                                                                    </option>
                                                                                                    @endif
                                                                                                @empty
                                                                                                    <option value="" disabled>No activities available</option>
                                                                                                @endforelse
                                                                                           </select>
                                                                                           {{-- <p>cgsjsdshj</p> --}}
                                                                                        </div>
                                                                                        @if(isset($errorActivity[$division_index][$route_index]))
                                                                                            <span class="text-red-500">{{ $errorActivity[$division_index][$route_index] }}</span>
                                                                                        @endif
                                                                                    </div>
                                                                                    <div class="flex mb-2 w-full">
                                                                                        <div class="small-btm my-0 cursor-pointer">
                                                                                           <span class="badge gap-2 bg-success/10 text-success uppercase" wire:click="OpenNewActivityModal({{$division_index}},{{$division_route_item['route_service_summary_id']}},{{$division_item['division_id']}})"> <i class="fas fa-plus-circle text-blue-500 text-base"></i> New Activity</span>
                                                                                        </div>
                                                                                        {{-- Model For New activity  --}}
                                                                                        <div class="hs-overlay {{ ($active_new_activity_modal == $division_index && $active_route_new_activity_modal == $division_route_item['route_service_summary_id']) ? '' : 'hidden' }} fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                                                                            <div class="hs-overlay-open:mt-7 ti-modal-box mt-0 ease-out lg:!max-w-4xl lg:w-full m-3 lg:!mx-auto modal_semi_lg_width bg-white rounded-lg">
                                                                                                <div class="ti-modal-content p-20">
                                                                                                    <div class="ti-modal-header flex justify-between items-center">
                                                                                                        <span class="uppercase"> {{ $division_route_item['route_name'] }}</span>
                                                                                                        <button type="button" class="text-gray-400 hover:text-gray-600 focus:outline-none badge gap-2 bg-danger/10 text-danger" wire:click="OpenNewActivityModal({{$division_index}},{{$division_route_item['route_service_summary_id']}},{{$division_item['division_id']}})">
                                                                                                            <i class="fa-solid fa-xmark text-lg text-dark"></i>
                                                                                                        </button>
                                                                                                    </div>
                                                                                                    <div class="ti-modal-body text-start">
                                                                                                        <div class="flex items-center mb-2">
                                                                                                            <div class="mx-1">
                                                                                                               <label for="">
                                                                                                                    <span class="badge gap-2 bg-primary/10 text-primary uppercase">
                                                                                                                        Assign New Activities
                                                                                                                    </span>
                                                                                                                </label>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                            <div class="table-responsive">
                                                                                                                <table class="table whitespace-nowrap table-bordered table-bordered-primary border-primary/10 min-w-full new-activity">
                                                                                                                    <thead class="uppercase">
                                                                                                                        <tr class="border-b !border-primary/30">
                                                                                                                            <th scope="col" class="!text-center w-1/3">Activity Name</th> <!-- Example for 50% width -->
                                                                                                                            <th scope="col" class="!text-center w-1/5">Type</th> <!-- Example for 20% width -->
                                                                                                                            <th scope="col" class="!text-center w-1/8">Rate</th> <!-- Example for 12.5% width -->
                                                                                                                            <th scope="col" class="!text-center w-1/10">Ticket Price(PP)</th> <!-- Example for 10% width -->
                                                                                                                            <th scope="col" class="!text-center w-1/20">
                                                                                                                                <button type="button" wire:click.prevent="addActivity" class="ti-btn ti-btn-sm ti-btn-soft-success !border !border-success/20">
                                                                                                                                    <i class="fa-solid fa-plus text-lg text-dark"></i>
                                                                                                                                </button>
                                                                                                                            </th>
                                                                                                                        </tr>
                                                                                                                    </thead>
                                                                                                                    <tbody>
                                                                                                                            @foreach ($new_activities as $new_activity_index => $activity)
                                                                                                                                <tr wire:key="new_activity-{{ $new_activity_index }}">
                                                                                                                                    <td class="!text-center">
                                                                                                                                        <div>
                                                                                                                                            <textarea wire:model="new_activities.{{ $new_activity_index }}.name" class="form-control form-control-sm text-center" placeholder="Enter activity name">
                                                                                                                                            </textarea>
                                                                                                                                        </div>
                                                                                                                                        @error('new_activities.' . $new_activity_index . '.name') <span class="text-danger">{{ $message }}</span> @enderror
                                                                                                                                    </td>
                                                                                                                                    <td class="!text-center border-x-0">
                                                                                                                                        <div>
                                                                                                                                            <label class="badge !rounded-full bg-outline-secondary cursor-pointer {{ isset($new_activities[$new_activity_index]['type']) && $new_activities[$new_activity_index]['type'] === 'PAID' ? 'active' : '' }}">
                                                                                                                                                <input 
                                                                                                                                                    type="radio"
                                                                                                                                                    name="type" 
                                                                                                                                                    value="PAID" 
                                                                                                                                                    class="contents" 
                                                                                                                                                    wire:change="updateType('{{ $new_activity_index }}', 'PAID')"
                                                                                                                                                    wire:key="radio-{{ $new_activity_index }}-PAID"> 
                                                                                                                                                PAID
                                                                                                                                            </label>
                                                                                                                                            <label class="badge !rounded-full bg-outline-secondary cursor-pointer {{ isset($new_activities[$new_activity_index]['type']) && $new_activities[$new_activity_index]['type'] === 'UNPAID' ? 'active' : '' }}">
                                                                                                                                                <input 
                                                                                                                                                    type="radio"
                                                                                                                                                    name="type" 
                                                                                                                                                    value="UNPAID" 
                                                                                                                                                    class="contents" 
                                                                                                                                                    wire:change="updateType('{{ $new_activity_index }}', 'UNPAID')" 
                                                                                                                                                    wire:key="radio-{{ $new_activity_index }}-UNPAID"> 
                                                                                                                                                UNPAID
                                                                                                                                            </label>
                                                                                                                                        </div>
                                                                                                                                        @error('new_activities.' . $new_activity_index . '.type') 
                                                                                                                                            <span class="text-danger">{{ $message }}</span> 
                                                                                                                                        @enderror
                                                                                                                                    </td>
                                                                                                                                    <td class="!text-center border-x-0">
                                                                                                                                        <div>
                                                                                                                                            <input type="text" wire:model="new_activities.{{ $new_activity_index }}.price" class="form-control form-control-sm text-center {{ isset($new_activities[$new_activity_index]['type']) && $new_activities[$new_activity_index]['type'] === 'UNPAID' ? 'placeholder:text-textmuted' : '' }}" placeholder="Price" 
                                                                                                                                            onkeyup="validateNumber(this)" {{ isset($new_activities[$new_activity_index]['type']) && $new_activities[$new_activity_index]['type'] === 'UNPAID' ? 'disabled' : '' }} >
                                                                                                                                        </div>
                                                                                                                                        @error('new_activities.' . $new_activity_index . '.price') <span class="text-danger">{{ $message }}</span> @enderror
                                                                                                                                    </td>
                                                                                                                                    <td class="!text-center border-x-0">
                                                                                                                                        <div class="table-responsive" style="margin: -8px;">
                                                                                                                                            <table class="table whitespace-nowrap min-w-full">
                                                                                                                                                <thead class="bg-warning/10">
                                                                                                                                                    <tr class="border-b border-defaultborder">
                                                                                                                                                        <th class="text-center uppercase route_details">INDIAN</th>
                                                                                                                                                        <th class="text-center uppercase route_details">Foreign National</th>
                                                                                                                                                    </tr>
                                                                                                                                                </thead>
                                                                                                                                                <tbody>
                                                                                                                                                    <tr>
                                                                                                                                                        <td class="text-center route_details">
                                                                                                                                                            <div>
                                                                                                                                                                <input type="text" wire:model="new_activities.{{ $new_activity_index }}.ticket_price" class="form-control form-control-sm text-center {{ isset($new_activities[$new_activity_index]['type']) && $new_activities[$new_activity_index]['type'] === 'UNPAID' ? 'placeholder:text-textmuted' : '' }}" placeholder="Price" onkeyup="validateNumber(this)" {{ isset($new_activities[$new_activity_index]['type']) && $new_activities[$new_activity_index]['type'] === 'UNPAID' ? 'disabled' : '' }}>
                                                                                                                                                            </div>
                                                                                                                                                            @error('new_activities.' . $new_activity_index . '.ticket_price') <span class="text-danger">{{ $message }}</span> @enderror
                                                                                                                                                        </td>
                                                                                                                                                    <td class="text-center route_details">
                                                                                                                                                            <div>
                                                                                                                                                                <input type="text" wire:model="new_activities.{{ $new_activity_index }}.ticket_price_nri" class="form-control form-control-sm text-center {{ isset($new_activities[$new_activity_index]['type']) && $new_activities[$new_activity_index]['type'] === 'UNPAID' ? 'placeholder:text-textmuted' : '' }}" placeholder="Price" onkeyup="validateNumber(this)" {{ isset($new_activities[$new_activity_index]['type']) && $new_activities[$new_activity_index]['type'] === 'UNPAID' ? 'disabled' : '' }}>
                                                                                                                                                            </div>
                                                                                                                                                            @error('new_activities.' . $new_activity_index . '.ticket_price_nri') <span class="text-danger">{{ $message }}</span> @enderror
                                                                                                                                                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                </tbody>
                                                                                                                                            </table>
                                                                                                                                        </div>
                                                                                                                                    </td>
                                                                                                                                    <td class="!text-center border-l-0">
                                                                                                                                        <button type="button" wire:click.prevent="removeActivity({{ $new_activity_index }})" class="ti-btn ti-btn-sm ti-btn-soft-danger !border !border-danger/20" wire:key="delete-new-{{$new_activity_index}}">
                                                                                                                                            <i class="ti ti-trash"></i>
                                                                                                                                        </button>
                                                                                                                                    </td>
                                                                                                                                </tr>
                                                                                                                                <tr  wire:key="new_activity_desc-{{ $new_activity_index }}">
                                                                                                                                    <td colspan="5">
                                                                                                                                        <div wire:ignore>
                                                                                                                                            <textarea rows="3"
                                                                                                                                                name="activity_description"
                                                                                                                                                class="form-control form-control-sm text-center activity_description_{{$active_route_new_activity_modal}}_{{$new_activity_index}}"
                                                                                                                                                placeholder="Description">
                                                                                                                                            </textarea>
                                                                                                                                        </div>
                                                                                                                                    </td>
                                                                                                                                </tr>
                                                                                                                            @endforeach
                                                                                                                    </tbody>
                                                                                                                </table>
                                                                                                            </div>
                                                                                                        
                                                                                                            @if (session('new-activity-error'))
                                                                                                                <div class="alert alert-danger">
                                                                                                                    {{ session('new-activity-error') }}
                                                                                                                </div>
                                                                                                            @endif
                                                                                                        
                                                                                                            <div class="text-end mt-3">
                                                                                                                <button type="button"  wire:click="submitNewActivityForm({{$division_index}},{{$division_route_item['route_service_summary_id']}},{{$division_item['division_id']}})" class="ti-btn ti-btn-primary-full !py-1 pt-0 ti-btn-wave me-[0.375rem]">
                                                                                                                    <i class="fa-solid fa-save"></i> Save
                                                                                                                </button>
                                                                                                            </div>
                                                                                                        
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        {{--End Model For New activity  --}}
                                                                                    </div>    
                                                                                </div>
                                                                                
                                                                                
                                                                                <!-- Drag & Drop List -->
                                                                                @if(isset($division_route_item['day_activity']) && count($division_route_item['day_activity'])>0)
                                                                                    <div class="sortable-list border-2 border-dashed border-gray-400 p-1 rounded-lg bg-white dark:bg-gray-800 shadow-md">
                                                                                        <ul id="draggable-list" class="draggable-list space-y-2 space-x-1 text-xs">
                                                                                            @foreach ($division_route_item['day_activity'] as $act_index=>$day_activity)
                                                                                                <li class="draggable-item flex items-center justify-between bg-gray-100 dark:bg-gray-700 p-1 rounded-md cursor-move"  wire:key="day-{{ $division_index }}-route-{{ $route_index }}-activity-{{$day_activity['id']}}">
                                                                                                    <span class="text-gray-600 dark:text-white/70 text-[10px] italic">{{ucfirst($day_activity['value'])}} <strong class="block text-green-600">@if($day_activity['price']>0)({{env('DEFAULT_CURRENCY_SYMBOL')}}{{$day_activity['rate']}} + {{env('DEFAULT_CURRENCY_SYMBOL')}}{{$day_activity['ticket_price']}})@endif</strong> </span>
    
                                                                                                    <div class="flex items-center space-x-2 justify-center ms-auto">
                                                                                                        <i class="fas fa-minus-circle text-red-500 cursor-pointer text-base"
                                                                                                            wire:click="decreaseQuantity({{$division_index}},{{$day_activity['id']}})"></i>
                                                                                                            
                                                                                                            <input type="number"
                                                                                                            id="custom_quantity_input{{$day_activity['id']}}" 
                                                                                                            class="custom-quantity-input text-xs leading-none py-0" 
                                                                                                            wire:model="itemQuantity.{{ $division_index }}.{{$day_activity['id']}}" 
                                                                                                            wire:keyup="updateQuantity({{$division_index}},{{$day_activity['id']}})" 
                                                                                                            min="1" value="{{$day_activity['value_quantity']}}"/>
                                                                                                        
                                                                                                        <i class="fas fa-plus-circle text-green-500 cursor-pointer text-base"
                                                                                                            wire:click="increaseQuantity({{$division_index}},{{$day_activity['id']}})"></i>
                                                                                                    </div>
                                                                                                    <i class="far fa-times-circle text-success cursor-pointer ps-1 ms-3" wire:click="RemoveDayRouteItem({{$division_index}},{{$day_activity['id']}})"></i>
                                                                                                </li>
                                                                                            @endforeach
                                                                                        </ul>
                                                                                    </div>
                                                                                @endif
                                                                                
                                                                                
                                                                            </td>
                                                                            
                                                                            <td class="align-top border !border-success !border-s-0 !border-t-0" rowspan="2">
                                                                                <button type="button" wire:click="RemoveDayRoute({{$division_index}},'day_route', {{$division_route_item['route_service_summary_id']}})" class="ti-btn ti-btn-sm ti-btn-soft-danger !border !border-danger/20">
                                                                                    <i class="ti ti-trash"></i>
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="3" class="border !border-success !border-s-0 !border-t-0 !py-1">
                                                                                <div class="small-btm !mb-0">
                                                                                    @php
                                                                                        $dayCabs = isset($division_route_item['day_cab'])?collect($division_route_item['day_cab'])->pluck('value')->toArray():[];
                                                                                    @endphp
                                                                                    <span class="badge gap-2 bg-warning/10 text-warning uppercase">Existing Cabs</span>
                                                                                    <select wire:key="existing-cab-{{$route_index}}-{{ $division_index }}-100" 
                                                                                    wire:change="getActivityOrSightseeing('cab',{{$division_index}},{{ $route_index }}, {{ $division_route_item['route_service_summary_id'] }}, $event.target.value, $event.target.selectedOptions[0].getAttribute('data-price'), '0', $event.target.selectedOptions[0].getAttribute('data-id'))">
                                                                                        <option value="">Choose an Existing Cab</option>
                                                                                        @forelse ($division_route_item['existing_cabs'] as $existing_cabs)
                                                                                            @if(!in_array($existing_cabs['name'], $dayCabs))
                                                                                                <option value="{{$existing_cabs['name']}}" data-id="{{$existing_cabs['id']}}" data-price="{{$existing_cabs['price']}}">{{$existing_cabs['name']}} </option>
                                                                                            @endif
                                                                                        @empty
                                                                                            <option value="" disabled>No cabs available</option>
                                                                                        @endforelse
                                                                                    </select>
                                                                                </div>
                                                                                @if(isset($errorCab[$division_index][$route_index]))
                                                                                    <span class="text-red-500">{{ $errorCab[$division_index][$route_index] }}</span>
                                                                                @endif
                                                                                <div class="transport_cab">
                                                                                    {{-- <span class="badge gap-2 bg-primary/10 text-primary uppercase">Cab</span> --}}
                                                                                    <div class="image-preview-container">
                                                                                        @if(isset($division_route_item['day_cab']) && count($division_route_item['day_cab'])>0)
                                                                                            @forelse ($division_route_item['day_cab'] as $cab_index=> $day_cab)
                                                                                            <div class="custom-card cab-card w-1/3 !p-0" wire:key="day-{{ $division_index }}-route-{{ $route_index }}-cab-{{$day_cab['id']}}">
                                                                                                <div class="custom-hotel-container relative !overflow-visible w-full">
                                                                                                    <div class="custom-cab-content items-center">
                                                                                                        <div class="custom-hotel-image-container flex-1 h-full" style="max-height: 62.75px;">
                                                                                                            <div class="custom-image-wrapper h-full w-full flex items-center justify-center">
                                                                                                                <img class="custom-hotel-image max-h-full max-w-full !w-auto" src="{{ isset($day_cab['cab']['image']) && $day_cab['cab']['image'] ? asset($day_cab['cab']['image']) : asset('assets/img/cab.png') }}" 
                                                                                                                alt="Cab Image">
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="custom-cab-details bg-orange-100 rounded-sm p-1" style="margin-left: auto; margin-right: 0;">
                                                                                                            <div class="custom-hotel-details-top border !border-success rounded-sm">
                                                                                                                <p class="flex text-white uppercase bg-teal-500 rounded-t-sm w-full p-1">
                                                                                                                    <span>{{ $day_cab['value'] }}</span>
                                                                                                                    <i class="far fa-times-circle text-white cursor-pointer text-xs ms-auto" wire:click="RemoveDayRouteItem({{$division_index}},{{$day_cab['id']}})"></i>
                                                                                                                </p>
                                                                                                                <div class="flex items-center p-1">
                                                                                                                    <!-- Quantity Controls -->
                                                                                                                    <div class="flex items-center space-x-1 justify-center me-2">
                                                                                                                        <i class="fas fa-minus-circle text-red-500 cursor-pointer text-base"
                                                                                                                            wire:click="decreaseQuantity({{$division_index}},{{$day_cab['id']}})"></i>
                                                                                                                            <input type="number"
                                                                                                                            id="custom_quantity_input{{$day_cab['id']}}" 
                                                                                                                            class="custom-quantity-input text-xs leading-none !py-1" 
                                                                                                                            wire:model="itemQuantity.{{ $division_index }}.{{$day_cab['id']}}" 
                                                                                                                            wire:keyup="updateQuantity({{$division_index}},{{$day_cab['id']}})" 
                                                                                                                            min="1" value="{{$day_cab['value_quantity']}}"/>
                                                                                                                        
                                                                                                                        <i class="fas fa-plus-circle text-green-500 cursor-pointer text-base"
                                                                                                                            wire:click="increaseQuantity({{$division_index}},{{$day_cab['id']}})"></i>
                                                                                                                    </div>
                                                                                                                    <label class="cab-preview-label relative cursor-pointer">
                                                                                                                        <div class="cab-card card-price">
                                                                                                                            <span class="hotel-name italic text-yellow-600">{{ env('DEFAULT_CURRENCY_SYMBOL') }}{{ $day_cab['price'] }}</span>
                                                                                                                        </div>
                                                                                                                    </label>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            @empty
                                                                                                <div class="alert alert-warning text-sm italic">
                                                                                                    Cab not added yet!
                                                                                                </div>
                                                                                            @endforelse
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                            </td>
                                                                        </tr>
                                                                    @empty
                                                                        <tr>
                                                                            <td colspan="5">
                                                                                <div class="alert alert-warning text-sm italic">
                                                                                    Routes not added yet!
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    @endforelse
                                                                </tbody>
                                                            </table>
    
                                                                {{-- Model For Assign Route  --}}
                                                                    <div class="hs-overlay {{$active_assign_route_modal==$division_index?"":"hidden"}} fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                                                        <div class="hs-overlay-open:mt-7 ti-modal-box mt-0 ease-out lg:!max-w-4xl lg:w-full m-3 lg:!mx-auto modal_md_width bg-white rounded-lg">
                                                                            <div class="ti-modal-content p-20">
                                                                                <div class="ti-modal-header flex justify-between items-center">
                                                                                    <span class="badge gap-2 bg-danger/10 text-danger uppercase">
                                                                                        <span class="w-1.5 h-1.5 inline-block bg-danger rounded-full "></span>Day {{$division_index}} in {{$division_item['division_name']}}</span>
                                                                                    </span> 
                                                                                    <button type="button" class="text-gray-400 hover:text-gray-600 focus:outline-none" wire:click="OpenAssignRouteModal(0)">
                                                                                        <i class="fa-solid fa-xmark text-lg"></i>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="ti-modal-body text-start new-activity">
                                                                                    <div class="custom-hotel-container">
                                                                                        {{-- <label for="">
                                                                                            <span class="badge gap-2 bg-primary/10 text-primary uppercase">
                                                                                                Routes
                                                                                            </span>
                                                                                        </label> --}}
                                                                                        <select
                                                                                            class="placeholder:text-textmuted text-sm selected_seasion_type route_select2"
                                                                                            wire:key="routes-{{ $division_index }}-100" wire:change="getRoute({{$division_index}},$event.target.value)">
                                                                                            <option value="" hidden>Choose route</option>
                                                                                            @if (!empty($division_item['division_routes']) && count($division_item['division_routes']) > 0)
                                                                                                @foreach ($division_item['division_routes'] as $routes_index => $route_item)
                                                                                                    <option 
                                                                                                        value="{{ $route_item['id'] ?? '' }}"
                                                                                                        wire:key="routes-{{ $division_index }}-{{ $routes_index }}" {{$route_item['route']?"":"disabled"}}>
                                                                                                        {{ $route_item['route'] ? ucwords($route_item['route']['route_name']): 'No route found'}}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            @else
                                                                                                <option value="" disabled>No routes available</option>
                                                                                            @endif
                                                                                        </select>
                                                                                        @if(isset($errorRoute[$division_index]))
                                                                                            <span class="text-red-500">{{ $errorRoute[$division_index] }}</span>
                                                                                        @endif
                                                                                        <div class="small-btm mt-2">
                                                                                            <span class="badge gap-2 bg-success/10 text-success uppercase cursor-pointer" wire:click="OpenNewRouteModal({{$division_index}})"><i class="fas fa-plus-circle text-green-500 cursor-pointer text-base"></i> New Route</span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                {{--End Model For Assign Route  --}}
                                                                
                                                                {{-- Model For New Route  --}}
                                                                    <div class="hs-overlay {{$active_new_route_modal==$division_index?"":"hidden"}} fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                                                        <div class="hs-overlay-open:mt-7 ti-modal-box mt-0 ease-out lg:!max-w-4xl lg:w-full m-3 lg:!mx-auto modal_md_width bg-white rounded-lg">
                                                                            <div class="ti-modal-content p-20">
                                                                                <div class="ti-modal-header flex justify-between items-center">
                                                                                    <p></p>
                                                                                    <button type="button" class="text-gray-400 hover:text-gray-600 focus:outline-none" wire:click="OpenNewRouteModal(0)">
                                                                                        <i class="fa-solid fa-xmark text-lg"></i>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="ti-modal-body text-start new-activity">
                                                                                    <div class="custom-hotel-container">
                                                                                        <label for="">
                                                                                            <span class="badge gap-2 bg-primary/10 text-primary uppercase">
                                                                                                Create New Route
                                                                                            </span>
                                                                                        </label>
                                                                                         <div class="mt-1">
                                                                                            <input type="text" wire:model="new_route_name" class="form-control form-control-sm refresh_component" placeholder="Enter route name">
                                                                                            @if(isset($errorNewRoute[$division_index]))
                                                                                                <span class="text-red-500">{{ $errorNewRoute[$division_index] }}</span>
                                                                                            @endif
                                                                                         </div>
                                                                                    </div>
                                                                                    <div class="text-end mt-3">
                                                                                        <button type="button" class="ti-btn ti-btn-primary-full !py-1 pt-0 ti-btn-wave me-[0.375rem]" wire:click="CreateNewRoute({{$division_index}},{{$division_item['division_id']}})">
                                                                                            <i class="fa-solid fa-save"></i> Save
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                {{--End Model For New Route  --}}
                                                            </div>
                                                            <div class="md:col-span-3 col-span-12">
                                                                @if(isset($division_item['day_hotel']))
                                                                <div class="flex flex-col border !border-success rounded-sm w-full mt-2">
                                                                    <p class="text-xs text-white uppercase bg-teal-500 rounded-t-sm w-full pt-1 pb-1 ps-3 pe-3">
                                                                        Day Hotel Category
                                                                    </p>
                                                                    <hr>
                                                                    <div class="pt-1 pb-1 ps-3 pe-3 w-full">
                                                                        <select 
                                                                            name="hotel_category_id"
                                                                             wire:model="day_hotel_category.{{ $division_index }}"
                                                                            class="w-full text-primary uppercase text-sm font-normal bg-white border-none focus:ring-0 focus:outline-none cursor-pointer" wire:change="changeDayHotelCategory($event.target.value,{{$division_index}},{{$division_item['division_id']}})">
                                                                            @foreach($allCategories[$division_index] as $hotel_category_item)
                                                                                <option value="{{ $hotel_category_item->id }}">
                                                                                    {{ $hotel_category_item->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                @endif
                                                                <div class="itinerary-build h-full">
                                                                    <div class="bg-orange-200 rounded-lg shadow-md w-fit p-2 text-black-600 text-[13px] italic" style="margin-top: 16px">
                                                                        <h2 class="text-lg font-bold text-gray-700 mb-4">Day {{$division_index}}</h2>
                                                                        @php
                                                                            $day_total_price = 0;
                                                                        @endphp
                                                                        @foreach ($day_wise_amount_data[$division_index] as $day_wise_amount)
                                                                            @php
                                                                            $day_total_price += $day_wise_amount->total_price;
                                                                                $fieldLabel = ucwords(str_replace('_', ' ', $day_wise_amount->field));
                                                                                $unitPrice = $day_wise_amount->total_quantity > 0 
                                                                                    ? round($day_wise_amount->total_price / $day_wise_amount->total_quantity, 2) 
                                                                                    : 0;
                                                                            @endphp
                                                                            
                                                                            @if(!in_array($day_wise_amount->field, ['day_sightseeing', 'day_activity', 'day_cab']))
                                                                                <p class="text-xs font-semibold">
                                                                                    @if($fieldLabel==="Day Room Main Plan")
                                                                                    @php
                                                                                        $unitPrice = $day_wise_amount->total_price/$leadData->number_of_rooms;
                                                                                    @endphp
                                                                                        ➤ Day Rooms(Adults: {{$day_wise_amount->total_quantity}}) ({{ $leadData->number_of_rooms }} * {{ $unitPrice }}) = {{ env('DEFAULT_CURRENCY_SYMBOL') }}{{ $day_wise_amount->total_price }}
                                                                                    @else
                                                                                        ➤ {{ $fieldLabel }} ({{ $day_wise_amount->total_quantity }} * {{ $unitPrice }}) = {{ env('DEFAULT_CURRENCY_SYMBOL') }}{{ $day_wise_amount->total_price }}
                                                                                    @endif
                                                                                    
                                                                                </p>
                                                                            @else
                                                                                <p class="text-xs font-semibold">
                                                                                    ➤ {{ $fieldLabel }} = {{ env('DEFAULT_CURRENCY_SYMBOL') }}{{ $day_wise_amount->total_price }}
                                                                                </p>
                                                                            @endif
                                                                            
                                                                        @endforeach
                                                                        
                                                                        
                                                                        <hr class="my-4 border-gray-500">
                                                                    
                                                                        <p class="text-lg font-bold text-red-700">
                                                                            Total Amount: <span class="text-black">{{ env('DEFAULT_CURRENCY_SYMBOL') }}{{number_format($day_total_price)}}</span>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="grid grid-cols-12 sm:gap-x-2 sm:gap-y-4">
                                                            <div class="col-span-12 mb-4">
                                                                
                                                                 {{-- If Itinerary is Post Lead then will show Hotel --}}
                                                                    <div class="mt-4"  wire:key="dayselectedHotel-{{ $division_index }}">
                                                                        {{-- <span class="badge gap-2 bg-danger/10 text-danger uppercase text-small m-2"><i class="fas fa-hotel"></i> Hotel  <span class="custom-header-separator">|</span> 1 Night<span class="custom-header-separator">|</span> in {{$division_item['division_name']}}</span> --}}
                                                                        <div class="grid grid-cols-12 sm:gap-x-4 sm:gap-y-4">
                                                                            
                                                                            @if(isset($division_item['day_hotel']))
                                                                                <div class="md:col-span-9 col-span-12 mb-4 itinerary-build">
                                                                                    <div class="w-full max-w-3xl mx-auto">
                                                                                        <!-- Tab Headers -->
                                                                                        <div class="flex justify-end mb-2">
                                                                                            @foreach ($division_item['day_hotel'] as $division_hotel_item_index=>$division_hotel_item)
                                                                                         
                                                                                                @if($activeTab[$division_index]==$division_hotel_item_index)
                                                                                                    @php
                                                                                                        $planTitle = $hotel_room_price[$division_index]['main_seasion_plan'][0]['title'] ?? null;
                                                                                                    @endphp
                                                                                                    @if (
                                                                                                            isset($division_item['division_id']) &&
                                                                                                            isset($division_hotel_item['hotel_id']) &&
                                                                                                            isset($division_hotel_item['selected_room']) &&
                                                                                                            isset($division_hotel_item['selected_room_name']) &&
                                                                                                            optional($division_hotel_item['selected_room_main_plan'])->value &&
                                                                                                            $planTitle
                                                                                                        )
                                                                                                        <a href="javascript:void(0)" class="badge gap-2 !bg-primary text-white rounded-md" onclick="ChangeSimilarHotelData(
                                                                                                            {{$division_index}}, 
                                                                                                            {{$division_item['division_id']}}, 
                                                                                                            {{$division_hotel_item['hotel_id']}}, 
                                                                                                            {{$division_hotel_item['selected_room']}}, 
                                                                                                            '{{$division_hotel_item['selected_room_name']}}', 
                                                                                                            '{{ optional($division_hotel_item['selected_room_main_plan'])->value }}',
                                                                                                            '{{$planTitle}}')"
                                                                                                                title="Searching for:&#10;- Same division&#10;- Same hotel category&#10;- Same room name&#10;- Equal or higher room capacity"> SIMILAR HOTELS</a>
                                                                                                    @endif
                                                                                                @endif
                                                                                            @endforeach
                                                                                            
                                                                                        </div>
                                                                                        <!-- Tab Content -->
                                                                                        <div class="bg-white p-2 border border-gray-300 rounded-md shadow-sm">
                                                                                            <div class="flex justify-center space-x-8 border-b border-gray-200 pb-4 mb-4">
                                                                                            @foreach ($division_item['day_hotel'] as $division_hotel_item_index=>$division_hotel_item)
                                                                                         
                                                                                                @if($activeTab[$division_index]==$division_hotel_item_index)
                                                                                                    <div class="custom-hotel-container relative !overflow-visible w-1/4 border border-primary rounded-md p-1">
                                                                                                        @php
                                                                                                            $planTitle = $hotel_room_price[$division_index]['main_seasion_plan'][0]['title'] ?? null;
                                                                                                        @endphp
                    
                                                                                                        @if (
                                                                                                            isset($division_item['division_id']) &&
                                                                                                            isset($division_hotel_item['hotel_id']) &&
                                                                                                            isset($division_hotel_item['selected_room']) &&
                                                                                                            isset($division_hotel_item['selected_room_name']) &&
                                                                                                            optional($division_hotel_item['selected_room_main_plan'])->value &&
                                                                                                            $planTitle
                                                                                                        )
                                                                                                            <a href="javascript:void(0)" 
                                                                                                            class="change_button badge gap-2 !bg-success text-white rounded-md">
                                                                                                                SELECTED HOTEL
                                                                                                            </a>
                                                                                                            {{-- <a href="javascript:void(0)" 
                                                                                                            class="change_button badge gap-2 !bg-primary text-white rounded-md"
                                                                                                            title="Searching for:&#10;- Same division&#10;- Same hotel category&#10;- Same room name&#10;- Equal or higher room capacity">
                                                                                                                Selected Hotel
                                                                                                            </a> --}}
                                                                                                        @endif
                                                                                                        <div class="custom-hotel-content flex flex-col">
                                                                                                            <div class="custom-hotel-image-container">
                                                                                                                {{-- <p class="custom-hotel-rating">4.2<small>/5</small></p> --}}
                                                                                                                <div class="custom-image-carousel cursor-pointer">
                                                                                                                    <div class="custom-image-wrapper w-full" style="height: 120px;">
                                                                                                                        <img class="custom-hotel-image w-full h-full object-cover"  
                                                                                                                            src="{{$division_hotel_item['hotel_image']?asset($division_hotel_item['hotel_image']):asset('build/assets/images/logo/hotel.jpg')}}" 
                                                                                                                            alt="Hotel Image">
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        
                                                                                                            <div class="custom-hotel-details !ml-0">
                                                                                                                <div class="custom-hotel-details-top">
                                                                                                                    <p class="text-black-600 text-base italic">{{$division_hotel_item['hotel_name']}} </p>
                                                                                                                    <p class="badge gap-2 bg-primary/10 text-primary uppercase text-small my-2">Rooms</p>
                                                                                                                    @php
                                                                                                                        $stock = App\Helpers\CustomHelper::checkRoomStockByDate(
                                                                                                                            $division_item['division_date'],
                                                                                                                            $division_hotel_item['selected_room'],
                                                                                                                            max((int)$leadData->number_of_rooms, 1)
                                                                                                                        );
                                                                                                                    @endphp

                                                                                                                    @if($stock == 0)
                                                                                                                        <p class="badge gap-2 bg-danger/10 text-danger uppercase text-small my-2">
                                                                                                                            Out of Stock
                                                                                                                        </p>
                                                                                                                    @else
                                                                                                                        <p class="badge gap-2 bg-success/10 text-success uppercase text-small my-2">
                                                                                                                            {{ $stock }} Rooms Remaining
                                                                                                                        </p>
                                                                                                                    @endif

                                                                                                                    
                                                                                                                    <div class="small-btm">
                                                                                                                        <select class="w-full" 
                                                                                                                                wire:change="updateSelectedRoom({{ $division_hotel_item['hotel_id'] }}, {{ $division_index }}, $event.target.value)" 
                                                                                                                                wire:key="select-room-{{ $division_index }}-{{ $division_hotel_item['hotel_id'] }}"
                                                                                                                            >
                                                                                                                                <option disabled selected value="">Select Room</option>

                                                                                                                                @forelse ($division_hotel_item['hotel_rooms'] as $room_index => $room)
                                                                                                                                    <option 
                                                                                                                                        value="{{ $room->id }}" 
                                                                                                                                        {{ $room->id == $division_hotel_item['selected_room'] ? 'selected' : '' }}
                                                                                                                                    >
                                                                                                                                        {{ $room->room_name }}
                                                                                                                                    </option>
                                                                                                                                @empty
                                                                                                                                    <option disabled>No rooms available</option>
                                                                                                                                @endforelse
                                                                                                                            </select>

                                                                                                                    </div>

                                                                                                                    {{-- <p class="text-gray-500 text-small">{{$division_hotel_item['hotel_address']}}</p> --}}
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        {{-- <div class="border border-gray-300 p-2 text-center bg-yellow-50"><span id="change">Change</span></div> --}}
                                                                                                        
                                                                                                    </div>
                                                                                                @else
                                                                                                    <div class="custom-hotel-container relative !overflow-visible w-1/4 border border-light rounded-md p-1" onclick="ChangeHotelData({{$division_index}},{{$division_hotel_item['id']}},{{$division_hotel_item['division']}},{{$division_hotel_item_index}})">
                                                                                                        <div class="custom-hotel-content flex flex-col">
                                                                                                            <div class="custom-hotel-image-container">
                                                                                                                {{-- <p class="custom-hotel-rating">4.2<small>/5</small></p> --}}
                                                                                                                <div class="custom-image-carousel cursor-pointer">
                                                                                                                    <div class="custom-image-wrapper w-full" style="height: 120px;">
                                                                                                                        <img class="custom-hotel-image w-full h-full object-cover"  
                                                                                                                            src="{{$division_hotel_item['image']?asset($division_hotel_item['image']):asset('build/assets/images/logo/hotel.jpg')}}" 
                                                                                                                            alt="Hotel Image">
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="custom-hotel-details">
                                                                                                                <div class="custom-hotel-details-top">
                                                                                                                    <p class="text-black-600 text-base italic">{{$division_hotel_item['name']}}</p>
                                                                                                                   @if(isset($division_hotel_item['hotel_category_name']))
                                                                                                                        <p class="text-black-500 text-small text-danger">
                                                                                                                            {{ $division_hotel_item['hotel_category_name'] }}
                                                                                                                        </p>
                                                                                                                    @endif
                                                                                                                    <p class="text-gray-500 text-small">{{$division_hotel_item['address']}}</p>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                @endif
                                                                                            @endforeach
                                                                                        </div>
                                                                                            @forelse ($division_item['day_hotel'] as $division_hotel_item_index=>$division_hotel_item)
                                                                                                @if($activeTab[$division_index]==$division_hotel_item_index)
                                                                                                    <div class="custom-card mt-2">
                                                                                                        {{-- <div>
                                                                                                            <p class="badge gap-2 bg-danger/10 text-danger uppercase text-small my-2">Rooms</p>
                                                                                                            @forelse ($division_hotel_item['hotel_rooms'] as $room_index => $room)
                                                                                                                <label class="hotel-preview-label relative cursor-pointer" wire:key="hotel-room-{{ $division_index }}-{{ $division_hotel_item['hotel_id'] }}-{{ $room->id }}">
                                                                                                                    <input 
                                                                                                                        type="radio"
                                                                                                                        name="selected_day_wise_itinerary_hotel.{{ $division_index }}.room"
                                                                                                                        value="{{ $room->id ?? '' }}" 
                                                                                                                        class="hidden peer"
                                                                                                                        wire:change="updateSelectedRoom({{ $division_hotel_item['hotel_id'] }}, {{ $division_index }}, {{ $room->id }})" 
                                                                                                                        {{ $room->id == $division_hotel_item['selected_room'] ? 'checked' : '' }}
                                                                                                                    >
                                                                                                                    <!-- Hotel Selection Box -->
                                                                                                                    <div class="hotel-card">
                                                                                                                        <span class="hotel-name">{{ $room->room_name }}</span>
                                                                                                                        <!-- Selected Indicator -->
                                                                                                                       @if((int)$room->id === (int)$division_hotel_item['selected_room'])
                                                                                                                            <span class="checkmark">✓</span>
                                                                                                                        @endif
                                                                                                                        
                                                                                                                    </div>
                                                                                                                </label>
                                                                                                            @empty
                                                                                                                <div class="alert alert-warning text-sm italic">
                                                                                                                    Rooms not added for this hotel
                                                                                                                </div>
                                                                                                            @endforelse

                                                                                                        </div> --}}
                                                                                                        @if(isset($selected_rooms[$division_index]))
                                                                                                            @php
                                                                                                                $selectedRoomId = $selected_rooms[$division_index] ?? null;
                                                                                                                $selectedRoom = collect($division_hotel_item['hotel_rooms'])->firstWhere('id', $selectedRoomId);
                                                                                                            @endphp
                                                                                                            @if ($selectedRoom)
                                                                                                                <div class="room-details-card mt-2" wire:key="day-{{ $division_index }}-selectedRoom-{{ $selectedRoomId }}">
                                                                                                                    <div class="text-center uppercase">
                                                                                                                        {{-- {{dd($hotel_room_price[$division_index]['room_details'])}} --}}
                                                                                                                        <p class="">Available Room Capacity <span class="text-gray-500">(No. of Bed : <span class="text-danger"> {{$hotel_room_price[$division_index]['room_details']?$hotel_room_price[$division_index]['room_details']->no_of_beds:"N/A"}}</span>| Extra Bed :<span class="text-danger">{{$hotel_room_price[$division_index]['room_details']?$hotel_room_price[$division_index]['room_details']->extra_bed:"N/A"}}</span>| Extra Mattress :<span class="text-danger">{{$hotel_room_price[$division_index]['room_details']?$hotel_room_price[$division_index]['room_details']->mattress:"N/A"}}</span>| Total :<span class="text-danger">{{$hotel_room_price[$division_index]['room_details']?$hotel_room_price[$division_index]['room_details']->capacity:"N/A"}}P</span>)</span></p>
                                                                                                                    </div>
                        
                                                                                                                    {{-- Error  --}}
                                                                                                                    @if(!empty($errorRoom[$division_index])) 
                                                                                                                        <div class="alert alert-danger text-sm italic">
                                                                                                                            {{ $errorRoom[$division_index] }}
                                                                                                                        </div>
                                                                                                                    @endif
                        
                                                                                                                    <table class="table min-w-full mt-1 room-capacity-table" style="border-collapse: separate; border-spacing: 0">
                                                                                                                        <thead class="bg-primary/5">
                                                                                                                            <tr>
                                                                                                                                <th class="!text-left uppercase bg-info border !border-info !border-e-0 text-white" style="width: 150px; border-top-left-radius: .5rem;">Plan</th>
                                                                                                                                @foreach ($hotel_room_price[$division_index]['addon_seasion_plan'] as $addon_seasion_plan)
                                                                                                                                    <th class="!text-left uppercase bg-info border !border-info !border-e-0 text-white">{{$addon_seasion_plan['title']}}
                                                                                                                                    </th>
                                                                                                                                @endforeach
                                                                                                                            </tr>
                                                                                                                        </thead>
                                                                                                                        <tbody class="">
                                                                                                                            <tr>
                                                                                                                                <td class="align-top border !border-info !border-t-0 !border-e-0">
                                                                                                                                    <span class="badge gap-2 bg-danger/10 text-danger">
                                                                                                                                        <span class="w-1.5 h-1.5 inline-block bg-danger rounded-full">
                                                                                                                                        </span>
                                                                                                                                        {{$hotel_room_price[$division_index]['main_seasion_plan'][0]['title']}}
                                                                                                                                    </span> |
                                                                                                                                    @foreach ($hotel_room_price[$division_index]['main_seasion_plan'][0]['plan_type'] as $plan_type_index=> $plan_type)
                                                                                                                                        @if(optional($division_hotel_item['selected_room_main_plan'])->value == $plan_type['name'])
                                                                                                                                        <input type="radio" class="hidden peer" name="selected_day_wise_itinerary_hotel.{{ $division_index }}.room_plan"
                                                                                                                                                value="{{ $plan_type['name'] }}">
                                                                                                                                            <span class="badge gap-2 bg-primary/10 text-primary">
                                                                                                                                                {{$plan_type['name']}}
                                                                                                                                            </span>
                                                                                                                                        @endif
                                                                                                                                    @endforeach
                                                                                                                                    
                                                                                                                                    
                                                                                                                                    {{-- <div class="mt-2">
                                                                                                                                        @foreach ($hotel_room_price[$division_index]['main_seasion_plan'][0]['plan_type'] as $plan_type_index=> $plan_type)
                                                                                                                                            @if(optional($division_hotel_item['selected_room_main_plan'])->value == $plan_type['name'])
                                                                                                                                                <label class="plan-type-preview-label relative cursor-pointer" wire:key="day-{{ $division_index }}-room-plan-{{ $plan_type_index }}">
                                                                                                                                                    <input type="radio" class="hidden peer" name="selected_day_wise_itinerary_hotel.{{ $division_index }}.room_plan"
                                                                                                                                                    value="{{ $plan_type['name'] }}"
                                                                                                                                                    wire:change="updateSelectedRoomPlan({{$division_hotel_item['hotel_id']}}, {{$selectedRoomId}}, {{ $division_index }}, '{{ $plan_type['name'] }}','{{$plan_type['price']}}')"
                                                                                                                                                    @checked(optional($division_hotel_item['selected_room_main_plan'])->value == $plan_type['name'])
                                                                                                                                                    >
                                                                                                                                                    <div class="hotel-card">
                                                                                                                                                        <span class="hotel-name">{{$plan_type['name']}}</span>
                                                                                                                                                        <span class="checkmark">✓</span>
                                                                                                                                                    </div>
                                                                                                                                                </label>
                                                                                                                                            @endif
                                                                                                                                        @endforeach
                                                                                                                                    </div> --}}
                                                                                                                                    <div class="custom-hotel-details-top bg-orange-100 rounded-sm p-1 mt-2">
                                                                                                                                        @if($division_hotel_item['selected_room_main_plan'])
                                                                                                                                        {{-- {{dd($division_hotel_item)}} --}}
                                                                                                                                            @php
                                                                                                                                            $room_rent = isset($division_hotel_item['selected_room_main_plan']->price) 
                                                                                                                                                            ? $division_hotel_item['selected_room_main_plan']->price / max((int)$leadData->number_of_rooms, 1)
                                                                                                                                                            : 0;
                                                                                                                                            @endphp
                                                                                                                                            <div class="text-white uppercase bg-teal-500 rounded-t-sm px-2 pt-1" style="font-size: 12px;">Room Rent</div>
                                                                                                                                            <div class="flex justify-center border !border-success rounded-b-sm px-2 py-1">
                                                                                                                                                <label class="flex items-center justify-between w-full">
                                                                                                                                                    <span class="text-danger" style="font-size: 11px;">{{env('DEFAULT_CURRENCY_SYMBOL')}}{{$room_rent}} × {{$leadData->number_of_rooms}}</span>
                                                                                                                                                    <div class="cab-card card-price">
                                                                                                                                                        <span class="hotel-name italic text-yellow-600 cursor-pointer">{{env('DEFAULT_CURRENCY_SYMBOL')}}{{$division_hotel_item['selected_room_main_plan']->price}}</span>
                                                                                                                                                    </div>
                                                                                                                                                </label>
                                                                                                                                            </div>
                                                                                                                                            
                                                                                                                                            <div class="flex items-center justify-between w-full mt-1">
                                                                                                                                                <div class="text-defaulttextcolor" style="font-size: 11px;">NO OF ADULTS</div>
                                                                                                                                                <!-- Quantity Controls -->
                                                                                                                                                <div class="flex items-center space-x-2 justify-center">
                                                                                                                                                    {{-- <i class="fas fa-minus-circle text-red-500 cursor-pointer text-base" wire:click="decreaseQuantity({{$division_index}},{{$division_hotel_item['selected_room_main_plan']->id}})"></i> --}}
                                                                                                                                                    <input type="number"
                                                                                                                                                    id="custom_quantity_input{{$division_hotel_item['selected_room_main_plan']->id}}" 
                                                                                                                                                    class="custom-quantity-input text-xs leading-none !py-1" 
                                                                                                                                                    wire:model="itemQuantity.{{ $division_index }}.{{$division_hotel_item['selected_room_main_plan']->id}}" 
                                                                                                                                                    wire:keyup="updateQuantity({{$division_index}},{{$division_hotel_item['selected_room_main_plan']->id}})" 
                                                                                                                                                    min="1" value="{{$division_hotel_item['selected_room_main_plan']->value_quantity}}" readonly/>
                                                                                                                                                    {{-- <i class="fas fa-plus-circle text-green-500 cursor-pointer text-base"  wire:click="increaseQuantity({{$division_index}},{{$division_hotel_item['selected_room_main_plan']->id}})"></i> --}}
                                                                                                                                                </div>
                                                                                                                                            </div>
                                                                                                                                        @endif
                                                                                                                                    </div>
                                                                                                                                </td>
                                                                                                                                @foreach ($hotel_room_price[$division_index]['addon_seasion_plan'] as $index_addon_season_plan=>$addon_seasion_plan)
                                                                                                                            
                                                                                                                                    <td class="!text-left uppercase align-top border !border-info !border-t-0 !border-e-0">
                                                                                                                                        <div class="small-btm">
                                                                                                                                            <select wire:key="existing-addon-plan-{{$division_index}}-{{$index_addon_season_plan}}-100" wire:change="GetRoomAddonPlan('{{$addon_seasion_plan['title']}}',{{$division_index}}, {{$division_hotel_item['hotel_id']}}, {{$selectedRoomId}}, $event.target.value, $event.target.selectedOptions[0].getAttribute('data-price'))" class="w-full">
                                                                                                                                                <option value="" selected>Choose {{-- $addon_seasion_plan['title'] --}}</option>
                                                                                                                                                @foreach($addon_seasion_plan['plan_type'] as $addon_plan_index=> $addon_plan_type)
                                                                                                                                                    <option value="{{$addon_plan_type['name']}}" data-price="{{$addon_plan_type['price']}}" wire:key="existing-addon-plan-{{$division_index}}-{{$index_addon_season_plan}}-{{$addon_plan_index}}">{{$addon_plan_type['name']}}</option>
                                                                                                                                                @endforeach
                                                                                                                                            </select>
                                                                                                                                        </div>
                                                                                                                                        @foreach ($addon_seasion_plan['selected_addon_plan'] as $index_selected_addon_plan =>$selected_addon_plan)
                                                                                                                                            
                                                                                                                                            <div class="addon_plan_item !border-0" wire:key="day-{{$division_index}}-route--cab-{{$selected_addon_plan['id']}}">
                                                                                                                                                <div class="custom-hotel-container relative !overflow-visible w-full bg-orange-100 rounded-sm mb-1">
                                                                                                                                                    <div class="custom-cab-content w-full items-center">
                                                                                                                                                        <div class="custom-cab-details flex-1 !m-0">
                                                                                                                                                            <div class="custom-hotel-details-top flex items-center">
                                                                                                                                                                {{-- <p class="text-black-600 italic" style="font-size:10px;"></p> --}}
                                                                                                                                                               
                                                                                                                                                                @if($selected_addon_plan['field']==='day_room_addon_plan_extra_mattress')
                                                                                                                                                                    @php
                                                                                                                                                                    $extra_mattress_price = isset($selected_addon_plan['price']) 
                                                                                                                                                                                    ? $selected_addon_plan['price'] / max((int)$leadData->extra_mattress, 1)
                                                                                                                                                                                    : 0;
                                                                                                                                                                    @endphp
                                                                                                                                                                    <div class="flex items-center">
                                                                                                                                                                        <span style="font-size: 11px; font-weight: 700; color: #0a336b;">{{$selected_addon_plan['value']}} :&nbsp;</span> <br>
                                                                                                                                                                        <span class="text-danger" style="font-size: 11px;">{{env('DEFAULT_CURRENCY_SYMBOL')}}{{$extra_mattress_price}} × {{$leadData->extra_mattress}}</span>
                                                                                                                                                                    </div>
                                                                                                                                                                @endif
                                                                                                                                                                
                                                                                                                                                                <!-- Quantity Controls -->
                                                                                                                                                                @if($selected_addon_plan['field']!=="day_room_addon_plan_extra_mattress")
                                                                                                                                                                    <div class="flex items-center space-x-2 justify-center">
                                                                                                                                                                        <span style="font-size: 11px; font-weight: 700; color: #0a336b;">{{$selected_addon_plan['value']}} :&nbsp;</span> 
                                                                                                                                                                        <input type="number"
                                                                                                                                                                            id="custom_quantity_input{{$selected_addon_plan['id']}}" 
                                                                                                                                                                            class="custom-quantity-input text-xs leading-none !py-1" 
                                                                                                                                                                            wire:model="itemQuantity.{{ $division_index }}.{{$selected_addon_plan['id']}}" 
                                                                                                                                                                            wire:keyup="updateQuantity({{$division_index}},{{$selected_addon_plan['id']}})" 
                                                                                                                                                                            min="1" value="{{$selected_addon_plan['value_quantity']}}" readonly/>
                                                                                                                                                                    </div>
                                                                                                                                                                @endif
                                                                                                                                                                
                                                                                                                                                                <div class="ms-auto">
                                                                                                                                                                    <label class="cab-preview-label relative cursor-pointer">
                                                                                                                                                                        <div class="cab-card card-price">
                                                                                                                                                                            <span class="hotel-name italic text-yellow-600">{{env('DEFAULT_CURRENCY_SYMBOL')}}{{$selected_addon_plan['price']}}</span>
                                                                                                                                                                        </div>
                                                                                                                                                                    </label>
                                                                                                                                                                </div>
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                        <i class="far fa-times-circle text-danger cursor-pointer text-xs ms-2" wire:click="RemoveDayRouteItem({{$division_index}},{{$selected_addon_plan['id']}})"></i>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                            </div>
                                                                                                                                        @endforeach
                                                                                                                                    </td>
                                                                                                                                
                                                                                                                                @endforeach
                                                                                                                            </tr>
                                                                                                                        </tbody>
                                                                                                                    </table>
                                                                                                                </div>
                                                                                                            @endif
                                                                                                        @endif
                                                                                                    </div>
                                                                                                @endif
                                                                                            @empty
                                                                                                <div class="custom-card">
                                                                                                    <div class="alert alert-warning text-sm italic">
                                                                                                        Hotel not added yet!
                                                                                                    </div>
                                                                                                </div>
                                                                                            @endforelse
                                                                                        </div>
                                                                                    </div>
                                                                                    {{-- {{dd($activeTab[$division_index])}} --}}
                                                                                </div>
                                                                            @else
                                                                                <div class="md:col-span-9 col-span-12 mb-4 itinerary-build">
                                                                                </div> 
                                                                            @endif
                                                                                {{-- <div class="md:col-span-3 col-span-12 mb-4 itinerary-build">
                                                                                    <div class="p-1 border border-gray-300 rounded-md shadow-sm">
                                                                                        <table class="table w-full" style="border-collapse: separate; border-spacing: 0">
                                                                                            <thead class="bg-primary/5">
                                                                                                <tr>
                                                                                                    <th class="text-center text-white bg-info border !border-info rounded-t-sm" style="padding-left: 5px !important; padding-right: 5px !important;">Cab</th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                @forelse ($division_item['per_day_cab'] as $per_day_index=>$division_per_day_cab)
                                                                                                    <tr>
                                                                                                        <td colspan="3" class="border !border-info !border-t-0 rounded-b-sm" style="padding-left: 5px !important; padding-right: 5px !importantl;">
                                                                                                            <div class="flex flex-col">
                                                                                                                @php
                                                                                                                    $dayCabs = isset($division_per_day_cab['day_cab'])?collect($division_per_day_cab['day_cab'])->pluck('value')->toArray():[];
                                                                                                                @endphp
                                                                                                                <span class="badge gap-2 bg-warning/10 text-warning uppercase w-full mb-1">Existing Per Day Cabs</span>
                                                                                                                <select wire:key="existing-per-day-cab-{{ $division_index }}-100" 
                                                                                                                wire:change="confirm_for_all_day_cab('per_day_cab',{{$division_index}}, $event.target.value, $event.target.selectedOptions[0].getAttribute('data-price'), $event.target.selectedOptions[0].getAttribute('data-id'))"
                                                                                                                
                                                                                                                class="text-xs rounded-sm py-0 mb-2">
                                                                                                                    <option value="">Choose an Existing Per Day Cab</option>
                                                                                                                    @forelse ($division_per_day_cab['per_day_existing_cabs'] as $per_day_existing_cabs)
                                                                                                                        @if(!in_array($per_day_existing_cabs['name'], $dayCabs))
                                                                                                                            <option value="{{$per_day_existing_cabs['name']}}" data-id="{{$per_day_existing_cabs['id']}}" data-price="{{$per_day_existing_cabs['price']}}">{{$per_day_existing_cabs['name']}} </option>
                                                                                                                        @endif
                                                                                                                    @empty
                                                                                                                        <option value="" disabled>No cabs available</option>
                                                                                                                    @endforelse
                                                                                                                </select>
                                                                                                            </div>
                                                                                                            @if(isset($errorPerDayCab[$division_index]))
                                                                                                                <span class="text-red-500">{{ $errorPerDayCab[$division_index] }}</span>
                                                                                                            @endif
                                                                                                            <div class="transport_cab">
                                                                                                                <div class="image-preview-container">
                                                                                                                    @if(isset($division_per_day_cab['day_cab']) && count($division_per_day_cab['day_cab'])>0)
                                                                                                                        @forelse ($division_per_day_cab['day_cab'] as $per_day_cab_index=> $per_day_cab)
                                                                                                                            <div class="custom-card cab-card w-full !p-0" wire:key="per-day-{{ $division_index }}-cab-{{$per_day_cab['id']}}" style="margin-right: 0 !important">
                                                                                                                                <div class="custom-hotel-container relative !overflow-visible w-full">
                                                                                                                                    <div class="custom-cab-content items-center">
                                                                                                                                        <div class="custom-hotel-image-container flex-1 h-full" style="max-height: 62.75px;">
                                                                                                                                            <div class="custom-image-wrapper h-full w-full flex items-center justify-center">
                                                                                                                                                <img class="custom-hotel-image max-h-full max-w-full !w-auto" src="{{ isset($per_day_cab['cab']['image']) && $per_day_cab['cab']['image'] ? asset($per_day_cab['cab']['image']) : asset('assets/img/cab.png') }}" 
                                                                                                                                                alt="Cab Image">
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                        <div class="custom-cab-details bg-orange-100 rounded-sm p-1" style="margin-left: auto; margin-right: 0;">
                                                                                                                                            <div class="custom-hotel-details-top border !border-success rounded-sm">
                                                                                                                                                <p class="flex text-white uppercase bg-teal-500 rounded-t-sm w-full p-1">
                                                                                                                                                    <span >{{ $per_day_cab['value'] }}</span>
                                                                                                                                                    <i class="far fa-times-circle text-white cursor-pointer text-xs ms-auto" wire:click="RemovePerDayCabItem({{$division_index}},{{$per_day_cab['id']}})"></i>
                                                                                                                                                </p>
                                                                                                                                                <div class="flex items-center p-1">
                                                                                                                                                    <!-- Quantity Controls -->
                                                                                                                                                    <div class="flex items-center space-x-1 justify-center me-2">
                                                                                                                                                        <i class="fas fa-minus-circle text-red-500 cursor-pointer text-base"
                                                                                                                                                            wire:click="decreaseQuantity({{$division_index}},{{$per_day_cab['id']}})"></i>
                                                                                                                                                            <input type="number"
                                                                                                                                                            id="custom_quantity_input{{$per_day_cab['id']}}" 
                                                                                                                                                            class="custom-quantity-input text-xs leading-none !py-1" 
                                                                                                                                                            wire:model="itemQuantity.{{ $division_index }}.{{$per_day_cab['id']}}" 
                                                                                                                                                            wire:keyup="updateQuantity({{$division_index}},{{$per_day_cab['id']}})" 
                                                                                                                                                            min="1" value="{{$per_day_cab['value_quantity']}}"
                                                                                                                                                            style="width:30px"/>
                                                                                                                                                        
                                                                                                                                                        <i class="fas fa-plus-circle text-green-500 cursor-pointer text-base"
                                                                                                                                                            wire:click="increaseQuantity({{$division_index}},{{$per_day_cab['id']}})"></i>
                                                                                                                                                    </div>
                                                                                                                                                    <label class="cab-preview-label relative cursor-pointer">
                                                                                                                                                        <div class="cab-card card-price" style="margin: 0 !important;">
                                                                                                                                                            <span class="hotel-name italic text-yellow-600">{{ env('DEFAULT_CURRENCY_SYMBOL') }}{{ $per_day_cab['price'] }}</span>
                                                                                                                                                        </div>
                                                                                                                                                    </label>
                                                                                                                                                </div>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        @empty
                                                                                                                            <div class="alert alert-warning text-sm italic">
                                                                                                                                Per Day Cab not added yet!
                                                                                                                            </div>
                                                                                                                        @endforelse
                                                                                                                    @endif
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @empty
                                                                                                    <tr>
                                                                                                        <td colspan="5">
                                                                                                            <div class="alert alert-warning text-sm italic">
                                                                                                                Per day cab not added yet!
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @endforelse
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </div> --}}
                                                                        </div>
                                                                    </div>
                                                                {{-- <div class="mt-4"  wire:key="dayselectedCab-{{ $division_index }}">
                                                                    <span class="badge gap-2 bg-danger/10 text-danger uppercase text-small m-2"><i class="fas fa-taxi"></i> Per Day Cab  <span class="custom-header-separator">|</span> in {{$division_item['division_name']}}</span>
                                                                </div> --}}
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-span-12 md:col-span-6 xxl:!col-span-4">
                <div class="box">
                    <div class="box-body">

                        <div class="hidden sm:block border-b border-gray-200 dark:border-white/10">
                           <nav class="flex space-x-4 rtl:space-x-reverse text-sm font-medium" aria-label="Tabs" role="tablist">
                                <button 
                                    type="button" 
                                    wire:click="$set('activeInExTab', 'inclusion')" 
                                    class="px-4 py-2 rounded-t-md border-b-2 transition-all duration-200
                                        {{ $activeInExTab === 'inclusion' 
                                            ? 'text-primary border-primary bg-white dark:bg-gray-800 dark:text-white font-extrabold font-mono' 
                                            : 'text-gray-500 hover:text-primary border-transparent' }}">
                                    Inclusions
                                </button>

                                <button
                                    type="button"
                                    wire:click="$set('activeInExTab', 'exclusion')"
                                    class="px-4 py-2 rounded-t-md border-b-2 transition-all duration-200
                                        {{ $activeInExTab === 'exclusion' 
                                            ? 'text-primary border-primary bg-white dark:bg-gray-800 dark:text-white font-extrabold font-mono' 
                                            : 'text-gray-500 hover:text-primary border-transparent' }}">
                                    Exclusions
                                </button>

                                <button type="button"
                                        wire:click="$set('activeInExTab', 'trip_highlight')"
                                        class="px-4 py-2 rounded-t-md border-b-2 transition-all duration-200
                                            {{ $activeInExTab === 'trip_highlight' 
                                                ? 'text-primary border-primary bg-white dark:bg-gray-800 dark:text-white font-extrabold font-mono' 
                                                : 'text-gray-500 hover:text-primary border-transparent' }}">
                                    Trip Highlights
                                </button>
                            </nav>

                        </div>


                        <div class="">
                            {{-- Inclusions Tab --}}
                            @if($activeInExTab === 'inclusion')
                                <div class="p-4">
                                    <ul class="space-y-3">
                                        @foreach ($inclusions as $in_index => $inclusion)
                                            <li class="flex items-start gap-3 p-4 border rounded-lg bg-white dark:bg-gray-800 dark:border-gray-700 shadow-sm addon_list_item">
                                                <p class="text-gray-700 dark:text-white text-base leading-snug">
                                                    {{ $inclusion }}
                                                </p>
                                                <input 
                                                    type="checkbox" 
                                                    wire:click="toggleInclusion('{{ $inclusion }}', {{ $in_index }})" 
                                                    @if(in_array($inclusion, $selectedInclusions)) checked @endif
                                                    class="mt-1 cursor-pointer"
                                                >
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- Exclusions Tab --}}
                            @if($activeInExTab === 'exclusion')
                                <div class="p-4">
                                    <ul class="space-y-3">
                                        @foreach ($exclusions as $ex_index => $exclusion)
                                            <li class="flex items-start gap-3 p-4 border rounded-lg bg-white dark:bg-gray-800 dark:border-gray-700 shadow-sm addon_list_item">
                                                <p class="text-gray-700 dark:text-white text-base leading-snug">
                                                    {{ $exclusion }}
                                                </p>
                                                <input 
                                                    type="checkbox" 
                                                    wire:click="toggleExclusion('{{ $exclusion }}', {{ $ex_index }})" 
                                                    @if(in_array($exclusion, $selectedExclusions)) checked @endif
                                                    class="mt-1 cursor-pointer"
                                                >
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- Trip Highlight Tab --}}
                            @if($activeInExTab === 'trip_highlight')
                                <div class="p-4">
                                    <ul class="space-y-3">
                                        @foreach ($triphighlight as $trip_index => $trip_highlight_item)
                                            <li class="flex items-start gap-3 p-4 border rounded-lg bg-white dark:bg-gray-800 dark:border-gray-700 shadow-sm addon_list_item">
                                                <p class="text-gray-700 dark:text-white text-base leading-snug">
                                                    {{ $trip_highlight_item }}
                                                </p>
                                                <input 
                                                    type="checkbox" 
                                                    wire:click="toggleTriphighlight('{{ $trip_highlight_item }}', {{ $trip_index }})" 
                                                    @if(in_array($trip_highlight_item, $selectedTriphighlight)) checked @endif
                                                    class="mt-1 cursor-pointer"
                                                >
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-2">
                <!-- Left Card - Message Options -->
                <div class="col-span-1 md:col-span-2 h-full">
                    <div class="bg-white shadow-lg rounded-2xl p-6 space-y-5 min-h-[350px]">
                        <!-- Header -->
                        <h2 class="uppercase text-gray-600 text-sm font-semibold">Send Message Via</h2>

                        <!-- Message Options -->
                        <div class="flex flex-wrap gap-6">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" wire:model="send_whatsapp"  wire:change="messageChannelChanged" class="form-checkbox h-5 w-5 text-blue-600 focus:ring-blue-500">
                                <span class="text-gray-800 font-medium cursor-pointer">WhatsApp</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" wire:model="send_email" wire:change="messageChannelChanged" class="form-checkbox h-5 w-5 text-blue-600 focus:ring-blue-500">
                                <span class="text-gray-800 font-medium cursor-pointer">Email</span>
                            </label>
                            {{-- <label class="flex items-center gap-2">
                                <input type="checkbox" wire:model="send_sms" wire:change="messageChannelChanged" class="form-checkbox h-5 w-5 text-blue-500 focus:ring-blue-500">
                                <span class="text-gray-800 font-medium cursor-pointer">SMS</span>
                            </label> --}}
                        </div>

                        <!-- Status Badge -->
                        <span class="badge gap-2 bg-danger/10 text-danger">
                            <span class="w-1.5 h-1.5 inline-block bg-danger rounded-full"></span>
                           SENT PACKAGES
                        </span>

                        <!-- History Table -->
                        <div class="overflow-x-auto !mt-0">
                            <table class="table-auto min-w-full text-sm text-center border border-gray-200 rounded table-sent-packages">
                                <thead class="bg-gray-100 uppercase text-xs text-gray-600">
                                    <tr>
                                        <th class="px-3 py-2 border">Date</th>
                                        <th class="px-3 py-2 border">Itinerary</th>
                                        <th class="px-3 py-2 border">Sent Via</th>
                                        <th class="px-3 py-2 border">Estimated Amount</th>
                                        <th class="px-3 py-2 border">
                                            Link
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Sample No Data Row -->
                                    @forelse ($sent_itineraries as $item)
                                        <tr>
                                            <td class="px-3 py-2 border">{{ \Carbon\Carbon::parse($item->sent_at)->format('d M Y, h:i A') }}</td>
                                            <td class="px-3 py-2 border">{{ $item->itinerary_syntax }}</td>
                                            <td class="px-3 py-2 border">{{ $item->send_via }}</td>
                                            <td class="px-3 py-2 border">₹{{ number_format($item->total_cost, 2) }}</td>
                                            <td class="px-3 py-2 border">
                                                {{-- {{ route('lead.shared.itinerary.view', $item->id) }} --}}
                                                <a href="{{route('website.lead.customized.itinerary', $item->itinerary_code)}}" target="_blank" class="text-blue-600 underline">View</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-3 text-sm italic text-gray-500 text-center">
                                                No packages sent yet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Right Card - Total & Send -->
                <div class="flex flex-col justify-between bg-white shadow-lg rounded-2xl p-6 max-height-250">

                    {{-- If arrival date is valid (today or future) --}}
                    @if($valid_panel)

                        {{-- If lead is NOT confirmed --}}
                        @if($leadData->status != "Confirmed")

                            <div class="text-center mb-4">
                                <p class="uppercase text-gray-500 text-sm">Total Estimated Amount</p>
                                <p class="text-2xl font-bold text-gray-800">
                                    {{ env('DEFAULT_CURRENCY_SYMBOL') }}{{ $total_amount }}
                                </p>
                            </div>

                            {{-- Show success/error messages --}}
                            @if (session('success'))
                                <div class="alert alert-success mb-2">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger mb-2">
                                    {!! session('error') !!}
                                </div>
                            @endif

                            {{-- If any sending options are enabled --}}
                            @if($send_whatsapp || $send_email || $send_sms)
                                <button wire:click="sendMessages"
                                    class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-dark transition-all">
                                    Send Messages
                                </button>
                            @else
                                <p class="text-gray-500 text-sm text-center mt-2">
                                    No communication method selected.
                                </p>
                            @endif

                        @else
                            {{-- Lead is already confirmed --}}
                            <p class="text-center text-green-600 font-semibold">
                                This lead has already been confirmed. No further action required.
                            </p>
                        @endif

                    @else
                        {{-- Arrival date is in the past --}}
                        <p class="text-center text-red-500 font-semibold">
                            This lead is no longer active because the arrival date has passed.
                        </p>
                    @endif

                </div>

                
            </div>

        </div>
        <!-- Livewire Modal -->
    
        @if($isRelatedModalOpen)
            <div class="side-model {{$isRelatedModalOpen ? 'open' : ''}}">
                <!-- Modal Content -->
                <div class="ti-modal-content p-4 h-full flex flex-col overflow-hidden">

                    <!-- Header -->
                    <div class="ti-modal-header items-center border-b pb-4">
                        <div class="flex justify-between mb-2">
                            <div>
                                <span class="badge gap-2 bg-primary/10 text-primary uppercase text-small m-2">Showing
                                    {{count($related_hotels)}} <i class="fas fa-hotel"></i> in {{$selected_division}}
                                </span>
                            </div>
                            <button type="button"
                                class="text-gray-400 hover:text-gray-600 focus:outline-none badge gap-2 bg-danger/10 text-danger"
                                wire:click="closeRelatedModal">
                                <i class="fa-solid fa-xmark text-lg text-dark"></i>
                            </button>
                        </div>
                        @if (session('related_hotel_error'))
                        <div class="alert alert-danger">
                            {!! session('related_hotel_error') !!}
                        </div>
                        @endif
                    </div>

                    <!-- Body -->
                    <div class="flex-grow mt-6 overflow-y-auto">

                        @forelse ($related_hotels as $related_hotel_index=>$related_hotel)
                        <div class="custom-hotel-container relative !overflow-visible mt-1">
                            <div class="custom-hotel-content">
                                <div class="custom-hotel-image-container w-2/5">
                                    <div class="custom-image-carousel">
                                        <div class="custom-image-wrapper"
                                            style="padding: 3px;max-width: 150px;min-height: 100px;">
                                            @if($related_hotel_index==0)
                                            <div class="selectedBadgeWrapper ">
                                                <span class="badgeBar"></span>
                                                <div class="selectedBadgeContainer">
                                                    <div class="tickContainer">
                                                        <div class="tick"></div>
                                                    </div>
                                                    <span class="selectedBadgeText font10"
                                                        data-testid="selected-room">Selected Hotel</span>
                                                </div>
                                            </div>
                                            @endif
                                            <img class="custom-hotel-image" src="{{asset($related_hotel['image'])}}"
                                                alt="Hotel Image">
                                        </div>
                                    </div>
                                </div>

                                <div class="w-3/5">
                                    <div class="custom-hotel-details-top">
                                        <p class="text-black-600 text-base italic related_hotel_title">
                                            {{$related_hotel['name']}}</p>
                                        <p class="text-gray-500 text-small">{{$related_hotel['address']}}</p>
                                        <div class="flex justify-between">
                                            <div>
                                                <label class="hotel-preview-label relative cursor-pointer">
                                                    <input type="radio" name="related_hotel.{{$related_hotel_index}}.room"
                                                        value="{{$related_hotel['selected_room_id']}}" class="hidden peer"
                                                        checked="">
                                                    <div class="hotel-card">
                                                        <span
                                                            class="hotel-name related_hotel_title">{{$related_hotel['selected_room_name']}}</span>
                                                        <span class="checkmark">✓</span>
                                                    </div>
                                                </label>
                                            </div>
                                            <div>
                                                @if($related_hotel_index>0)
                                                <div>
                                                    <span class="related_hotel_amount">{{$related_hotel['sign']}}
                                                        {{ENV('DEFAULT_CURRENCY_SYMBOL')}}{{$related_hotel['difference']}}</span>
                                                </div>
                                                <a href="javascript:void(0)"
                                                    wire:click="updateDayHotel({{$related_hotel['selected_day']}},{{$related_hotel['selected_hotel_id']}},{{$related_hotel['id']}},'{{$related_hotel['selected_room_name']}}', '{{$related_hotel['selected_season']}}')"
                                                    class="py-1 px-3  inline-flex items-center gap-x-2 text-sm rounded-full ti-btn-primary-gradient text-white  me-[0.375rem] !mb-2 uppercase">Select</a>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        @empty
                        <div class="alert alert-secondary">
                            Sorry! no related hotels available for the selected criteria.
                        </div>
                        @endforelse

                    </div>
                </div>
            </div>
        @endif
    </div>
    {{-- Model --}}

    <div wire:loading class="loader" wire:target="addAboutDescHighlight, ItineraryImageDelete, deleteDayImage, OpenNewRouteModal,OpenAssignRouteModal, RemoveDayRouteItem,RemoveDayRoute,RemoveDayHotel, getActivityOrSightseeing, getRoute, main_banner, removeAboutDescHighlight,getHotel,updateSelectedRoom,ResetItinerary,updateSelectedRoomPlan,GetRoomAddonPlan,decreaseQuantity,increaseQuantity,confirm_for_all_day_cab,getPerDayCab,RemovePerDayCabItem,ChangeHotel,ChangeSimilarHotel,closeRelatedModal,updateDayHotel,CreateNewRoute,OpenNewSightseeingModal,addSightseeing,removeSightSeing,submitNewSightSeeingForm,OpenNewActivityModal,submitNewActivityForm,addActivity,removeActivity,updateType,toggleInclusion,toggleExclusion,changePackageBudget,changeDayHotelCategory,messageChannelChanged,sendMessages,activeInExTab">
        <div class="spinner">
        <img src="{{asset('build/assets/images/media/loader.svg')}}" alt="">
        </div>
    </div>
    @if($isRelatedModalOpen)
        <div class="overlay" wire:click="closeRelatedModal"></div>
    @endif

</div>
<!-- Include Dragula JS -->
@section('scripts')
    <script type="text/javascript" src="{{ asset('build/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('build/assets/libs/dragula/dragula.min.js') }}"></script>
    <script>

        window.addEventListener('refreshComponent', function (event) {
            document.querySelectorAll('.refresh_component').forEach(element => {
                element.value = ''; // Clear input fields
            });
        });
        window.addEventListener('refreshPage', function (event) {
            window.location.reload();
        });

        window.addEventListener('resetInputQuantity', function (event) {
            let itemId = event.detail[0].id;
            let quantity = event.detail[0].quantity;
            document.querySelectorAll('#custom_quantity_input' + itemId).forEach(element => {
                element.value = quantity; // Use event.detail to access the dispatched data
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            // Apply Dragula to the list
            dragula([document.getElementById('draggable-list')]);
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function ChangeSimilarHotelData(index, division, hotel_id, room_id, room_name, meal_type, season){
            @this.call('ChangeSimilarHotel', index, division, hotel_id, room_id, room_name, meal_type, season);
        }
        function ChangeHotelData(index, hotel_id, division,tab){
            @this.call('ChangeHotel', index, hotel_id,division,tab);
        }
        window.addEventListener('ConfirmForAllDayCab', function (event) {
            let field = event.detail[0].field;
            let index = event.detail[0].index;
            let value = event.detail[0].value;
            let price = event.detail[0].price;
            let cab_id = event.detail[0].cab_id;
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to apply this change to all days?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, apply to all days!",
                cancelButtonText: "No, just this day!"
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('getPerDayCab', field, index, value, price, cab_id, 'YES');
                } else {
                    @this.call('getPerDayCab', field, index, value, price, cab_id, 'NO');
                }
            });
        });

        window.addEventListener('load_ck_editor', function (event) {
            let item_index = event.detail[0].item_index;
            let item_type = event.detail[0].item_type;
            let modal_id = event.detail[0].modal_id;

            requestAnimationFrame(() => {
            // Destroy CKEditor instances whose elements no longer exist
                for (const instanceName in CKEDITOR.instances) {
                    const instance = CKEDITOR.instances[instanceName];
                    if (instance && (!document.getElementById(instance.name))) {
                        instance.destroy(true);
                    }
                }

                // Re-initialize editors for sightseeing
                if (item_type === 'sightseeing') {
                    const className = `.sightseeing_description_${modal_id}_${item_index}`;
                    document.querySelectorAll(className).forEach((element, index) => {
                        // Generate unique ID for CKEditor
                        const elementId = `sightseeing_description_${modal_id}_${item_index}_${index}`;

                        if (!element.id || element.id.trim() === '') {
                            element.id = elementId;
                        }

                        if (!CKEDITOR.instances[element.id]) {
                            const editor = CKEDITOR.replace(element.id, {
                                height: 120,
                                removePlugins: 'elementspath',
                                resize_enabled: false
                            });

                            // Bind change event to update Livewire
                            editor.on('change', function () {
                                const value = editor.getData();
                                @this.call('syncDescriptionToLivewire', {
                                    index: item_index,
                                    value: value
                                });
                            });
                        }
                    });
                }
                if (item_type === 'activity') {
                    const className = `.activity_description_${modal_id}_${item_index}`;
                    document.querySelectorAll(className).forEach((element, index) => {
                        // Create unique ID
                        const elementId = `activity_description_${modal_id}_${item_index}_${index}`;

                        if (!element.id || element.id.trim() === '') {
                            element.id = elementId;
                        }

                        if (!CKEDITOR.instances[element.id]) {
                            const editor = CKEDITOR.replace(element.id, {
                                height: 120,
                                removePlugins: 'elementspath',
                                resize_enabled: false
                            });

                            editor.on('change', function () {
                                const value = editor.getData();
                                @this.call('syncActivityDescriptionToLivewire', {
                                    index: item_index,
                                    value: value
                                });
                            });
                        }
                    });
                }

            });
        });
    </script>
@endsection
