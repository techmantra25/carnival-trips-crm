<div>
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
                <div class="box-header itinerary_box_header">
                    <div>
                        {{-- <a href="{{route('admin.itinerary.preset.builder',[$destinationId,$categoryId])}}" class="ti-btn ti-btn-sm ti-btn-soft-danger !border !border-danger/20">
                            <i class="ri-refresh-line"></i>
                        </a> --}}
                    </div>
                    <div class="flex justify-between pb-2">
                        <div class="flex">
                            <div class="mx-2">
                                <p class="badge gap-2 bg-danger/10 text-danger uppercase">Destination</p><hr>
                                <div class="badge bg-outline-primary cursor-pointer !font-normal !text-sm uppercase">
                                    <span> {{$destinationName}}</span>
                                </div>
                            </div>
                            {{-- <div class="mx-2">
                                <p class="badge gap-2 bg-danger/10 text-danger uppercase">Hotel Category</p><hr>
                                <div class="badge bg-outline-primary cursor-pointer !font-normal !text-sm uppercase">
                                    <span>    {{$categoryName}}</span>
                                </div>
                            </div> --}}
                        </div>
                        <div class="flex">
                            @if($itineraryType=="post_inquiry")
                                <div class="bg-orange-200 rounded-lg shadow-md w-fit p-2 text-black-600 text-[13px] italic mx-2">
                                    <div class="px-2">
                                        <p class="text-lg font-semibold uppercase">Itinerary is for:</p>
                                    </div>
                                    <div class="flex">
                                        <div class="px-2">
                                            <p class="text-md font-semibold mt-2">Client: <span class="font-normal">{{$leadData->customer_name}}</span></p>
                                            <p class="text-md font-semibold mt-2">Client Email: <span class="font-normal">{{$leadData->customer_email}}</span></p>
                                            <p class="text-md font-semibold mt-2">Client Mobile: <span class="font-normal">+{{$leadData->country_code}}{{$leadData->customer_mobile}}</span></p>
                                            <p class="text-md font-semibold mt-2">Travel Date: <span class="font-normal">{{date('d-m-Y',strtotime($leadData->arrival_date))}}</span></p>
                                        </div>
                                        <div class="px-2">
                                            <p class="text-md font-semibold mt-2">Andaman Trip: <span class="font-normal">{{$itinerary_syntax}}</span></p>
                                            <p class="text-md font-semibold">PAX: <span class="font-normal">{{$leadData->number_of_travellor}}</span></p>
                                            <p class="text-md font-semibold">Adults: <span class="font-normal">{{$leadData->number_of_adults}}</span></p>
                                            <p class="text-md font-semibold">Child: <span class="font-normal">{{$leadData->number_of_children}}</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center itinerary_total_amount">
                                <p class="uppercase">Total Amount</p>
                                    <p class="text-2xl">{{env('DEFAULT_CURRENCY_SYMBOL')}}{{$total_amount}}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <form wire:submit.prevent="submitForm">
                        <div class="table-responsive mb-2">
                            <table class="table whitespace-nowrap table-bordered table-bordered-primary border-primary/10 min-w-full new-activity">
                                <thead class="uppercase">
                                    <tr class="border-b !border-primary/30 bg-warning/10">
                                        <th scope="col">
                                            <div class="custom-fulldiv">
                                                <div>
                                                    Name Of Itinerary
                                                </div>
                                                <div class="nd-field">
                                                    <input type="text" wire:model="itinerary_syntax" class="form-control form-control-sm text-center" readonly>
                                                </div>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="">
                                                
                                                @if($itineraryType=="post_inquiry")
                                                    <div class="flex justify-between">
                                                        <div>Night Halt Details</div>
                                                        <div>
                                                        <a  href="javascript:void(0)" class="badge gap-2 bg-success/10 text-dark uppercase text-sm" wire:click="NewNightHalt('yes')"><i class="fa-solid fa-plus text-xs text-dark"></i>Add New</a>
                                                        </div>
                                                    </div>
                                                    <div wire:ignore>
                                                        <select name="existing_night_halt_details" wire:model="selected_night_halt" class="placeholder:text-textmuted text-sm selected_seasion_type select2" wire:key="existing-night-halt--0" id="existing_night_halt_details">
                                                            <option value="" hidden="">Filter item</option>
                                                            @foreach ($existing_night_halt_details as $key=>$night_halt)
                                                                <option value="{{$night_halt['id']}}" wire:key="existing-night-halt-{{$key+1}}">{{$night_halt['itinerary_journey']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @else
                                                    <div>Night Halt Details</div>
                                                    <div>
                                                        <input type="text" wire:model="selected_night_halt_details" class="placeholder:text-textmuted text-sm selected_seasion_type" value="" readonly>
                                                    </div>
                                                @endif
                                            </div>
                                        </th>
                                        <th scope="col" class="!text-center w-1/5">
                                            <a href="javascript:void(0)" wire:click="ResetItinerary" class="!px-4 badge bg-outline-danger cursor-pointer !font-normal !text-sm uppercase">RESET</button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($day_by_divisions as $division_index=>$division_item)
                                        <table class="table table-bordered table-bordered-primary border-primary/10 min-w-full new-activity">
                                            <thead class="bg-primary/20">
                                                <tr>
                                                    <th class="!text-center uppercase"><span class="text-xl">Day {{$division_index}} ({{$division_item['division_name']}})</span></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        {{-- <div class="grid grid-cols-12 mb-4">
                                                            <label class="sm:col-span-2 col-span-12 col-form-label">Text</label>
                                                            <div class="sm:col-span-10 col-span-12">
                                                                <textarea wire:model="day_texts.day_{{ $division_index }}_text" 
                                                                class="form-control form-control-sm" 
                                                                rows="3"
                                                                wire:keyup="UpdateByKeyUp('day_{{ $division_index }}', 'day_{{ $division_index }}_text', $event.target.value)"></textarea>
                                                            </div>
                                                        </div> --}}
                                                    
                                                        {{-- <span class="badge gap-2 bg-danger/10 text-danger uppercase text-small mx-2">Day {{$division_index}} in {{$division_item['division_name']}}</span> --}}

                                                         {{-- If Itinerary is Post Lead then will show Hotel --}}
                                                         @if($itineraryType=="post_inquiry")
                                                            <div class="mt-4"  wire:key="dayselectedHotel-{{ $division_index }}">
                                                                <span class="badge gap-2 bg-danger/10 text-danger uppercase text-small m-2"><i class="fas fa-hotel"></i> Hotel  <span class="custom-header-separator">|</span> 1 Night<span class="custom-header-separator">|</span> in {{$division_item['division_name']}}</span>
                                                                <div class="grid grid-cols-12 sm:gap-x-6 sm:gap-y-4">
                                                                    <div class="md:col-span-8 col-span-12 mb-4 mx-2 itinerary-build">
                                                                        
                                                                        @forelse ($division_item['day_hotel'] as $division_hotel_item)
                                                                            <div class="custom-card mt-2" wire:key="day-{{ $division_index }}-hotel-{{ $division_hotel_item['hotel_id'] }}">
                                                                                <div class="custom-hotel-container relative !overflow-visible">
                                                                                    <div class="custom-hotel-content">
                                                                                        <div class="custom-hotel-image-container">
                                                                                            {{-- <p class="custom-hotel-rating">4.2<small>/5</small></p> --}}
                                                                                            <div class="custom-image-carousel">
                                                                                                <div class="custom-image-wrapper" style="width: 225px; height: 120px;">
                                                                                                    <img class="custom-hotel-image" width="225" height="120" 
                                                                                                        src="{{$division_hotel_item['hotel_image']?asset($division_hotel_item['hotel_image']):asset('build/assets/images/logo/hotel.jpg')}}" 
                                                                                                        alt="Hotel Image">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    
                                                                                        <div class="custom-hotel-details">
                                                                                            <div class="custom-hotel-details-top">
                                                                                                <p class="text-black-600 text-base italic">{{$division_hotel_item['hotel_name']}}</p>
                                                                                                <p class="text-gray-500 text-small">{{$division_hotel_item['hotel_address']}}</p>
                                                                                                <p class="badge gap-2 bg-danger/10 text-danger uppercase text-small my-2">Rooms</p>
                                                                                                <div>
                                                                                                    @forelse ($division_hotel_item['hotel_rooms'] as $room_index=> $room)
                                                                                                        <label class="hotel-preview-label relative cursor-pointer">
                                                                                                            <input 
                                                                                                                type="radio"
                                                                                                                name="selected_day_wise_itinerary_hotel.{{ $division_index }}.room"
                                                                                                                value="{{ $room->id ?? '' }}" 
                                                                                                                class="hidden peer"
                                                                                                                wire:change="updateSelectedRoom({{$division_hotel_item['hotel_id']}},{{ $division_index }}, {{ $room->id }})" 
                                                                                                                {{$room->id==$division_hotel_item['selected_room']?"checked":""}}
                                                                                                                >
                                                                                                            <!-- Hotel Selection Box -->
                                                                                                            <div class="hotel-card">
                                                                                                                <span class="hotel-name">{{$room->room_name}}</span>
                                                                                                                <!-- Selected Indicator -->
                                                                                                                <span class="checkmark">✓</span>
                                                                                                            </div>
                                                                                                        </label>
                                                                                                    @empty
                                                                                                        <div class="alert alert-warning text-sm italic">
                                                                                                            rooms not added on this hotel
                                                                                                        </div>
                                                                                                    @endforelse
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <button type="button" wire:click="RemoveDayHotel({{$division_index}},'day_hotel', {{$division_hotel_item['hotel_id']}})" class="delete-icon">✖
                                                                                    </button>
                                                                                </div>
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
                                                                                                <div class="alert alert-warning text-sm italic">
                                                                                                    {{ $errorRoom[$division_index] }}
                                                                                                </div>
                                                                                            @endif

                                                                                            <table class="table table-bordered table-bordered-primary border-primary/10 min-w-full mt-4">
                                                                                                <thead class="bg-primary/5">
                                                                                                    <tr>
                                                                                                        <th class="!text-center uppercase" style="width: 120px;">Plan</th>
                                                                                                        @foreach ($hotel_room_price[$division_index]['addon_seasion_plan'] as $addon_seasion_plan)
                                                                                                            <th class="!text-center uppercase">{{$addon_seasion_plan['title']}}
                                                                                                            </th>
                                                                                                        @endforeach
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                <tbody class="">
                                                                                                    <tr>
                                                                                                        <td class="align-top">
                                                                                                            
                                                                                                            <span class="badge gap-2 bg-danger/10 text-danger">
                                                                                                                <span class="w-1.5 h-1.5 inline-block bg-danger rounded-full">
                                                                                                                </span>
                                                                                                                {{$hotel_room_price[$division_index]['main_seasion_plan'][0]['title']}}
                                                                                                            </span>
                                                                                                            
                                                                                                            <div class="mt-2">
                                                                                                                @foreach ($hotel_room_price[$division_index]['main_seasion_plan'][0]['plan_type'] as $plan_type_index=> $plan_type)
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
                                                                                                                @endforeach
                                                                                                            </div>
                                                                                                            <div class="custom-hotel-details-top">
                                                                                                                @if($division_hotel_item['selected_room_main_plan'])
                                                                                                                    <div class="flex justify-center">
                                                                                                                        <label class="cab-preview-label relative cursor-pointer">
                                                                                                                            <div class="cab-card card-price">
                                                                                                                                <span class="hotel-name italic text-yellow-600">{{env('DEFAULT_CURRENCY_SYMBOL')}}{{$division_hotel_item['selected_room_main_plan']->price}}</span>
                                                                                                                            </div>
                                                                                                                        </label>
                                                                                                                    </div>
                                                                                                                    <!-- Quantity Controls -->
                                                                                                                    <div class="flex items-center space-x-2 justify-center">
                                                                                                                        {{-- <i class="fas fa-minus-circle text-red-500 cursor-pointer text-base" wire:click="decreaseQuantity({{$division_index}},{{$division_hotel_item['selected_room_main_plan']->id}})"></i> --}}
                                                                                                                        <input type="number"
                                                                                                                        id="custom_quantity_input{{$division_hotel_item['selected_room_main_plan']->id}}" 
                                                                                                                        class="custom-quantity-input" 
                                                                                                                        wire:model="itemQuantity.{{ $division_index }}.{{$division_hotel_item['selected_room_main_plan']->id}}" 
                                                                                                                        wire:keyup="updateQuantity({{$division_index}},{{$division_hotel_item['selected_room_main_plan']->id}})" 
                                                                                                                        min="1" value="{{$division_hotel_item['selected_room_main_plan']->value_quantity}}" readonly/>
                                                                                                                        {{-- <i class="fas fa-plus-circle text-green-500 cursor-pointer text-base"  wire:click="increaseQuantity({{$division_index}},{{$division_hotel_item['selected_room_main_plan']->id}})"></i> --}}
                                                                                                                    </div>
                                                                                                                    <div class="no_of_room">
                                                                                                                        NO OF ROOM
                                                                                                                    </div>
                                                                                                                @endif
                                                                                                            </div>
                                                                                                        </td>
                                                                                                        @foreach ($hotel_room_price[$division_index]['addon_seasion_plan'] as $index_addon_season_plan=>$addon_seasion_plan)
                                                                                                            <td class="!text-center uppercase align-top !p-0">
                                                                                                                <div class="small-btm">
                                                                                                                    <select wire:key="existing-addon-plan-{{$division_index}}-{{$index_addon_season_plan}}-100" wire:change="GetRoomAddonPlan('{{$addon_seasion_plan['title']}}',{{$division_index}}, {{$division_hotel_item['hotel_id']}}, {{$selectedRoomId}}, $event.target.value, $event.target.selectedOptions[0].getAttribute('data-price'))">
                                                                                                                        <option value="" selected hidden>Choose {{$addon_seasion_plan['title']}}</option>
                                                                                                                        @foreach($addon_seasion_plan['plan_type'] as $addon_plan_index=> $addon_plan_type)
                                                                                                                            <option value="{{$addon_plan_type['name']}}" data-price="{{$addon_plan_type['price']}}" wire:key="existing-addon-plan-{{$division_index}}-{{$index_addon_season_plan}}-{{$addon_plan_index}}">{{$addon_plan_type['name']}}</option>
                                                                                                                        @endforeach
                                                                                                                    </select>
                                                                                                                </div>
                                                                                                                @foreach ($addon_seasion_plan['selected_addon_plan'] as $index_selected_addon_plan =>$selected_addon_plan)
                                                                                                                    <div class="addon_plan_item" wire:key="day-{{$division_index}}-route--cab-{{$selected_addon_plan['id']}}">
                                                                                                                        <div class="custom-hotel-container relative !overflow-visible">
                                                                                                                            <div class="custom-cab-content">
                                                                                                                                <div class="custom-cab-details">
                                                                                                                                    <div class="custom-hotel-details-top">
                                                                                                                                        <p class="text-black-600 italic" style="font-size:10px;">{{$selected_addon_plan['value']}}</p>
                                                                                                                                        <div>
                                                                                                                                            <label class="cab-preview-label relative cursor-pointer">
                                                                                                                                                <div class="cab-card card-price">
                                                                                                                                                    <span class="hotel-name italic text-yellow-600">{{env('DEFAULT_CURRENCY_SYMBOL')}}{{$selected_addon_plan['price']}}</span>
                                                                                                                                                </div>
                                                                                                                                            </label>
                                                                                                                                        </div>
                                                                                                                                        <!-- Quantity Controls -->
                                                                                                                                        <div class="flex items-center space-x-2 justify-center">
                                                                                                                                            {{-- <i class="fas fa-minus-circle text-red-500 cursor-pointer text-base" wire:click="decreaseQuantity({{$division_index}},{{$selected_addon_plan['id']}})"></i> --}}
                                                                                                                                            <input type="number"
                                                                                                                                                id="custom_quantity_input{{$selected_addon_plan['id']}}" 
                                                                                                                                                class="custom-quantity-input" 
                                                                                                                                                wire:model="itemQuantity.{{ $division_index }}.{{$selected_addon_plan['id']}}" 
                                                                                                                                                wire:keyup="updateQuantity({{$division_index}},{{$selected_addon_plan['id']}})" 
                                                                                                                                                min="1" value="{{$selected_addon_plan['value_quantity']}}" readonly/>
                                                                                                                                            
                                                                                                                                            {{-- <i class="fas fa-plus-circle text-green-500 cursor-pointer text-base" wire:click="increaseQuantity({{$division_index}},{{$selected_addon_plan['id']}})"></i> --}}
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                                <i class="far fa-times-circle text-warning cursor-pointer text-xs" wire:click="RemoveDayRouteItem({{$division_index}},{{$selected_addon_plan['id']}})"></i>
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
                                                                        @empty
                                                                            <div class="custom-card">
                                                                                <div class="alert alert-warning text-sm italic">
                                                                                    Hotel not added yet!
                                                                                </div>
                                                                            </div>
                                                                        @endforelse
                                                                        
                                                                        
                                                                    </div>
                                                                    <div class="md:col-span-4 col-span-12 mb-4 mx-2 itinerary-build">
                                                                        <div class="custom-card">
                                                                            <div class="custom-hotel-container" wire:ignore>
                                                                                <label for="">
                                                                                    <span class="badge gap-2 bg-primary/10 text-primary uppercase">
                                                                                        Hotels
                                                                                    </span>
                                                                                </label>
                                                                                <select
                                                                                    class="placeholder:text-textmuted text-sm selected_seasion_type select2" data-id="{{$division_index}}" 
                                                                                    wire:key="hotel-{{ $division_index }}-100">
                                                                                    <option value="" hidden>Choose hotel</option>
                                                                                    @if (!empty($division_item['division_hotels']) && count($division_item['division_hotels']) > 0)
                                                                                        @foreach ($division_item['division_hotels'] as $hotel_index => $hotel_item)
                                                                                            <option 
                                                                                                value="{{ $hotel_item['id'] ?? '' }}"
                                                                                                wire:key="hotel-{{ $division_index }}-{{ $hotel_index }}">
                                                                                                {{ $hotel_item['name'] ?? 'No Name' }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    @else
                                                                                        <option value="" disabled>No hotels available</option>
                                                                                    @endif
                                                                                </select>
                                                                                @error('errorHotel.{{$division_index}}') 
                                                                                    <span class="text-red-500">{{ $message }}</span> 
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="bg-orange-200 rounded-lg shadow-md w-fit p-2 text-black-600 text-[13px] italic mt-2">
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
                                                                                
                                                                                <p class="text-xs font-semibold">
                                                                                    ➤ {{ $fieldLabel }} ({{ $day_wise_amount->total_quantity }} * {{ $unitPrice }}) = {{ env('DEFAULT_CURRENCY_SYMBOL') }}{{ $day_wise_amount->total_price }}
                                                                                </p>
                                                                            @endforeach
                                                                            
                                                                            
                                                                            <hr class="my-4 border-gray-500">
                                                                        
                                                                            <p class="text-lg font-bold text-red-700">
                                                                                Total Amount: <span class="text-black">{{ env('DEFAULT_CURRENCY_SYMBOL') }}{{number_format($day_total_price)}}</span>
                                                                            </p>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="mt-4"  wire:key="dayselectedCab-{{ $division_index }}">
                                                            <span class="badge gap-2 bg-danger/10 text-danger uppercase text-small m-2"><i class="fas fa-taxi"></i> Per Day Cab  <span class="custom-header-separator">|</span> in {{$division_item['division_name']}}</span>
                                                        </div>
                                                        <table class="table table-bordered table-bordered-primary border-primary/10 min-w-full mt-4">
                                                            <thead class="bg-primary/5">
                                                                <tr>
                                                                    <th class="text-center">Cab</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse ($division_item['per_day_cab'] as $per_day_index=>$division_per_day_cab)
                                                                    <tr>
                                                                        <td colspan="3" style="border-left: 1px solid;">
                                                                            <div class="small-btm">
                                                                                @php
                                                                                    $dayCabs = isset($division_per_day_cab['day_cab'])?collect($division_per_day_cab['day_cab'])->pluck('value')->toArray():[];
                                                                                @endphp
                                                                                <span class="badge gap-2 bg-warning/10 text-warning uppercase">Existing Per Day Cabs</span>
                                                                                <select wire:key="existing-per-day-cab-{{ $division_index }}-100" 
                                                                                wire:change="getPerDayCab('per_day_cab',{{$division_index}}, $event.target.value, $event.target.selectedOptions[0].getAttribute('data-price'), $event.target.selectedOptions[0].getAttribute('data-id'))">
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
                                                                                        <div class="custom-card cab-card" wire:key="per-day-{{ $division_index }}-cab-{{$per_day_cab['id']}}">
                                                                                            <div class="custom-hotel-container relative !overflow-visible">
                                                                                                <div class="custom-cab-content">
                                                                                                    <div class="custom-hotel-image-container">
                                                                                                        <div class="custom-image-wrapper" style="width: 95px; height: 50px;">
                                                                                                            <img class="custom-hotel-image" width="95" height="50" src="{{ isset($per_day_cab['cab']['image']) && $per_day_cab['cab']['image'] ? asset($per_day_cab['cab']['image']) : asset('assets/img/cab.png') }}" 
                                                                                                            alt="Cab Image">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="custom-cab-details">
                                                                                                        <div class="custom-hotel-details-top">
                                                                                                            <p class="text-black-600 text-xs">{{ $per_day_cab['value'] }}</p>
                                                                                                            <div>
                                                                                                                <label class="cab-preview-label relative cursor-pointer">
                                                                                                                    <div class="cab-card card-price">
                                                                                                                        <span class="hotel-name italic text-yellow-600">{{ env('DEFAULT_CURRENCY_SYMBOL') }}{{ $per_day_cab['price'] }}</span>
                                                                                                                    </div>
                                                                                                                </label>
                                                                                                            </div>
                                                                                                            <!-- Quantity Controls -->
                                                                                                            <div class="flex items-center space-x-2 justify-center">
                                                                                                                {{-- <i class="fas fa-minus-circle text-red-500 cursor-pointer text-base"
                                                                                                                    wire:click="decreaseQuantity({{$division_index}},{{$per_day_cab['id']}})"></i> --}}
                                                                                                                    <input type="number"
                                                                                                                    id="custom_quantity_input{{$per_day_cab['id']}}" 
                                                                                                                    class="custom-quantity-input" 
                                                                                                                    wire:model="itemQuantity.{{ $division_index }}.{{$per_day_cab['id']}}" 
                                                                                                                    wire:keyup="updateQuantity({{$division_index}},{{$per_day_cab['id']}})" 
                                                                                                                    min="1" value="{{$per_day_cab['value_quantity']}}" readonly/>
                                                                                                                
                                                                                                                {{-- <i class="fas fa-plus-circle text-green-500 cursor-pointer text-base"
                                                                                                                    wire:click="increaseQuantity({{$division_index}},{{$per_day_cab['id']}})"></i> --}}
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <i class="far fa-times-circle text-warning cursor-pointer text-xs" wire:click="RemovePerDayCabItem({{$division_index}},{{$per_day_cab['id']}})"></i>
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

                                                        <div class="mt-4" wire:key="dayselectedCab-{{ $division_index }}">
                                                            <span class="badge gap-2 bg-danger/10 text-danger uppercase text-small m-2">
                                                                <i class="fas fa-route"></i> Route  
                                                                <span class="custom-header-separator">|</span>  
                                                                <i class="fas fa-binoculars"></i> Sightseeing  
                                                                <span class="custom-header-separator">|</span>  
                                                                <i class="fas fa-running"></i> Activity  
                                                                <span class="custom-header-separator">|</span> in {{$division_item['division_name']}}
                                                            </span>
                                                        </div>
                                                        <table class="table table-bordered table-bordered-primary border-primary/10 min-w-full mt-4">
                                                            <thead class="bg-primary/5">
                                                                <tr>
                                                                    <th class="!text-center uppercase" width="5%">SL No.</th>
                                                                    <th class="!text-center uppercase">ROUTE</th>
                                                                    <th class="!text-center uppercase">SIGHTSEEINGS</th>
                                                                    <th class="!text-center uppercase">ACTIVITY</th>
                                                                    <th class="!text-center uppercase" width="5%">
                                                                        <button type="button" wire:click="OpenAssignRouteModal({{$division_index}})" class="ti-btn ti-btn-sm ti-btn-soft-success !border !border-success/20">
                                                                            <i class="fa-solid fa-plus text-lg text-dark"></i>
                                                                        </button>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                             
                                                                @forelse ($division_item['day_route'] as $route_index=>$division_route_item)
                                                                    <tr wire:key="day-{{ $division_index }}-route-{{ $division_route_item['route_service_summary_id'] }}">
                                                                        <td class="!text-center" rowspan="2">
                                                                            <span class="badge bg-primary/10 text-primary">{{$route_index+1}}</span>
                                                                        </td>
                                                                        <td class="!text-center">
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
                                                                        <td class="!text-center align-top">
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
                                                                                                            <label for="">
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
                                                                            @if(isset($division_route_item['day_sightseing']) && count($division_route_item['day_sightseing'])>0)
                                                                                <div class="sortable-list border-2 border-dashed border-gray-400 p-1 rounded-lg bg-white dark:bg-gray-800 shadow-md">
                                                                                    <ul id="draggable-list" class="draggable-list space-y-2 space-x-1 text-xs">
                                                                                        @foreach ($division_route_item['day_sightseing'] as $sight_index=>$day_sightseing)
                                                                                            <li class="draggable-item flex items-center justify-between bg-gray-100 dark:bg-gray-700 p-1 rounded-md cursor-move"  wire:key="day-{{ $division_index }}-route-{{ $route_index }}-sightseeing-{{$day_sightseing['id']}}">
                                                                                                <span class="text-gray-600 dark:text-white/70 text-[10px] italic">{{ucfirst($day_sightseing['value'])}} <strong class="text-blue-600">@if($day_sightseing['price']>0)({{env('DEFAULT_CURRENCY_SYMBOL')}}{{$day_sightseing['price']}})@endif</strong> </span>
                                                                                                
                                                                                                <div class="flex items-center space-x-2 justify-center ms-auto">
                                                                                                    {{-- <i class="fas fa-minus-circle text-red-500 cursor-pointer text-base"
                                                                                                        wire:click="decreaseQuantity({{$division_index}},{{$day_sightseing['id']}})"></i> --}}
                                                                                                        
                                                                                                        <input type="number"
                                                                                                        id="custom_quantity_input{{$day_sightseing['id']}}" 
                                                                                                        class="custom-quantity-input text-xs leading-none py-0" 
                                                                                                        wire:model="itemQuantity.{{ $division_index }}.{{$day_sightseing['id']}}" 
                                                                                                        wire:keyup="updateQuantity({{$division_index}},{{$day_sightseing['id']}})" 
                                                                                                        min="1" value="{{$day_sightseing['value_quantity']}}" readonly/>
                                                                                                    
                                                                                                    {{-- <i class="fas fa-plus-circle text-green-500 cursor-pointer text-base"
                                                                                                        wire:click="increaseQuantity({{$division_index}},{{$day_sightseing['id']}})"></i> --}}
                                                                                                </div>
                                                                                                <i class="far fa-times-circle text-primary cursor-pointer ps-1 ms-3" wire:click="RemoveDayRouteItem({{$division_index}},{{$day_sightseing['id']}})"></i>
                                                                                            </li>
                                                                                        @endforeach
                                                                                    </ul>
                                                                                </div>
                                                                            @endif
                                                                        </td>
                                                                        <td class="!text-center align-top">
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
                                                                                                    {{-- <i class="fas fa-minus-circle text-red-500 cursor-pointer text-base"
                                                                                                        wire:click="decreaseQuantity({{$division_index}},{{$day_activity['id']}})"></i> --}}
                                                                                                        
                                                                                                        <input type="number"
                                                                                                        id="custom_quantity_input{{$day_activity['id']}}" 
                                                                                                        class="custom-quantity-input text-xs leading-none py-0" 
                                                                                                        wire:model="itemQuantity.{{ $division_index }}.{{$day_activity['id']}}" 
                                                                                                        wire:keyup="updateQuantity({{$division_index}},{{$day_activity['id']}})" 
                                                                                                        min="1" value="{{$day_activity['value_quantity']}}" readonly/>
                                                                                                    
                                                                                                    {{-- <i class="fas fa-plus-circle text-green-500 cursor-pointer text-base"
                                                                                                        wire:click="increaseQuantity({{$division_index}},{{$day_activity['id']}})"></i> --}}
                                                                                                </div>
                                                                                                <i class="far fa-times-circle text-success cursor-pointer ps-1 ms-3" wire:click="RemoveDayRouteItem({{$division_index}},{{$day_activity['id']}})"></i>
                                                                                            </li>
                                                                                        @endforeach
                                                                                    </ul>
                                                                                </div>
                                                                            @endif
                                                                        </td>
                                                                        
                                                                        <td class="!text-center" rowspan="2">
                                                                            <button type="button" wire:click="RemoveDayRoute({{$division_index}},'day_route', {{$division_route_item['route_service_summary_id']}})" class="ti-btn ti-btn-sm ti-btn-soft-danger !border !border-danger/20">
                                                                                <i class="ti ti-trash"></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="3" style="border-left: 1px solid;">
                                                                            <div class="small-btm">
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
                                                                                        <div class="custom-card cab-card" wire:key="day-{{ $division_index }}-route-{{ $route_index }}-cab-{{$day_cab['id']}}">
                                                                                            <div class="custom-hotel-container relative !overflow-visible">
                                                                                                <div class="custom-cab-content">
                                                                                                    <div class="custom-hotel-image-container">
                                                                                                        <div class="custom-image-wrapper" style="width: 95px; height: 50px;">
                                                                                                            <img class="custom-hotel-image" width="95" height="50" src="{{ isset($day_cab['cab']['image']) && $day_cab['cab']['image'] ? asset($day_cab['cab']['image']) : asset('assets/img/cab.png') }}" 
                                                                                                            alt="Cab Image">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="custom-cab-details">
                                                                                                        <div class="custom-hotel-details-top">
                                                                                                            <p class="text-black-600 text-xs">{{ $day_cab['value'] }}</p>
                                                                                                            <div>
                                                                                                                <label class="cab-preview-label relative cursor-pointer">
                                                                                                                    <div class="cab-card card-price">
                                                                                                                        <span class="hotel-name italic text-yellow-600">{{ env('DEFAULT_CURRENCY_SYMBOL') }}{{ $day_cab['price'] }}</span>
                                                                                                                    </div>
                                                                                                                </label>
                                                                                                            </div>
                                                                                                            <!-- Quantity Controls -->
                                                                                                            <div class="flex items-center space-x-2 justify-center">
                                                                                                                {{-- <i class="fas fa-minus-circle text-red-500 cursor-pointer text-base"
                                                                                                                    wire:click="decreaseQuantity({{$division_index}},{{$day_cab['id']}})"></i> --}}
                                                                                                                    <input type="number"
                                                                                                                    id="custom_quantity_input{{$day_cab['id']}}" 
                                                                                                                    class="custom-quantity-input" 
                                                                                                                    wire:model="itemQuantity.{{ $division_index }}.{{$day_cab['id']}}" 
                                                                                                                    wire:keyup="updateQuantity({{$division_index}},{{$day_cab['id']}})" 
                                                                                                                    min="1" value="{{$day_cab['value_quantity']}}" readonly/>
                                                                                                                
                                                                                                                {{-- <i class="fas fa-plus-circle text-green-500 cursor-pointer text-base"
                                                                                                                    wire:click="increaseQuantity({{$division_index}},{{$day_cab['id']}})"></i> --}}
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <i class="far fa-times-circle text-warning cursor-pointer text-xs" wire:click="RemoveDayRouteItem({{$division_index}},{{$day_cab['id']}})"></i>
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

                                                        {{-- Model For New Route  --}}
                                                            <div class="hs-overlay {{$active_assign_route_modal==$division_index?"":"hidden"}} fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                                                <div class="hs-overlay-open:mt-7 ti-modal-box mt-0 ease-out lg:!max-w-4xl lg:w-full m-3 lg:!mx-auto modal_md_width bg-white rounded-lg">
                                                                    <div class="ti-modal-content p-20">
                                                                        <div class="ti-modal-header flex justify-between items-center">
                                                                            <span class="badge gap-2 bg-danger/10 text-danger uppercase">
                                                                                <span class="w-1.5 h-1.5 inline-block bg-danger rounded-full "></span>Day {{$division_index}} in {{$division_item['division_name']}}</span>
                                                                            </span> 
                                                                            <button type="button" class="text-gray-400 hover:text-gray-600 focus:outline-none" wire:click="OpenNewRouteModal(0)">
                                                                                <i class="fa-solid fa-xmark text-lg"></i>
                                                                            </button>
                                                                        </div>
                                                                        <div class="ti-modal-body text-start new-activity">
                                                                            <div class="custom-hotel-container" wire:ignore>
                                                                                <label for="">
                                                                                    <span class="badge gap-2 bg-primary/10 text-primary uppercase">
                                                                                        Routes
                                                                                    </span>
                                                                                </label>
                                                                                <select
                                                                                    class="placeholder:text-textmuted text-sm selected_seasion_type route_select2"
                                                                                    wire:key="routes-{{ $division_index }}-100" wire:change="getRoute({{$division_index}},$event.target.value)">
                                                                                    <option value="" hidden>Choose route</option>
                                                                                    @if (!empty($division_item['division_routes']) && count($division_item['division_routes']) > 0)
                                                                                        @foreach ($division_item['division_routes'] as $routes_index => $route_item)
                                                                                            <option 
                                                                                                value="{{ $route_item['id'] ?? '' }}"
                                                                                                wire:key="routes-{{ $division_index }}-{{ $routes_index }}" {{$route_item['route']?"":"disabled"}}>
                                                                                                {{ $route_item['route'] ? $route_item['route']['route_name']: 'No route found'}}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    @else
                                                                                        <option value="" disabled>No hotels available</option>
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
                                                        {{--End Model For New Route  --}}
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
                                            ? 'text-primary border-primary bg-white dark:bg-gray-800 dark:text-white' 
                                            : 'text-gray-500 hover:text-primary border-transparent' }}"
                                >
                                    Inclusions
                                </button>

                                <button
                                    type="button"
                                    wire:click="$set('activeInExTab', 'exclusion')"
                                    class="px-4 py-2 rounded-t-md border-b-2 transition-all duration-200
                                        {{ $activeInExTab === 'exclusion' 
                                            ? 'text-primary border-primary bg-white dark:bg-gray-800 dark:text-white' 
                                            : 'text-gray-500 hover:text-primary border-transparent' }}"
                                >
                                    Exclusions
                                </button>
                                <button
                                    type="button"
                                    wire:click="$set('activeInExTab', 'trip_highlight')"
                                    class="px-4 py-2 rounded-t-md border-b-2 transition-all duration-200
                                        {{ $activeInExTab === 'trip_highlight' 
                                            ? 'text-primary border-primary bg-white dark:bg-gray-800 dark:text-white' 
                                            : 'text-gray-500 hover:text-primary border-transparent' }}"
                                >
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
                                            <li class="flex items-start gap-3 p-4 border rounded-lg bg-white dark:bg-gray-800 dark:border-gray-700 shadow-sm">
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
                                            <li class="flex items-start gap-3 p-4 border rounded-lg bg-white dark:bg-gray-800 dark:border-gray-700 shadow-sm">
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
                                            <li class="flex items-start gap-3 p-4 border rounded-lg bg-white dark:bg-gray-800 dark:border-gray-700 shadow-sm">
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
        </div>
    </div>
    <!-- Livewire Modal -->
    @if($showModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
            wire:click="closeModal">

            <div class="relative bg-white p-4 rounded-lg shadow-lg max-w-2xl w-full"
                wire:click.stop> <!-- Prevent closing when clicking inside -->
                <!-- Close Button -->
                <button class="delete-icon" wire:click="closeModal">✖</button>

                <!-- Display Image -->
                <img src="{{ asset($modalImage) }}" class="w-full h-auto rounded-md">
            </div>
        </div>
    @endif

    {{--New Night Halt Model --}}
    @if($itineraryType=="post_inquiry")
        <div id="assign_cab" class="hs-overlay {{$active_new_night_halt_modal==0?"hidden":""}} fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="hs-overlay-open:mt-7 ti-modal-box mt-0 ease-out lg:!max-w-4xl lg:w-full m-3 lg:!mx-auto modal_md_width bg-white rounded-lg">
                <div class="ti-modal-content p-20">
                    <div class="ti-modal-header flex justify-end items-center">
                        <button type="button" class="text-gray-400 hover:text-gray-600 focus:outline-none badge gap-2 bg-danger/10 text-danger" wire:click="NewNightHalt('no')">
                            <i class="fa-solid fa-xmark text-lg text-dark"></i>
                        </button>
                    </div>
                    <div class="ti-modal-body text-start">
                        <div class="flex items-center mb-2">
                            <div class="grid grid-cols-1 hover:grid-cols-6 mx-1">
                                <label for="">
                                    <span class="badge gap-2 bg-danger/10 text-danger uppercase">
                                        Destination
                                    </span>
                                </label>
                                <input type="text" class="form-control selected_seasion_type" value="{{optional($leadData->destination)->name??'N/A'}}" readonly>
                            </div>
                            <div class="grid grid-cols-1 hover:grid-cols-6 mx-1">
                                <label for="">
                                    <span class="badge gap-2 bg-danger/10 text-danger uppercase">
                                        Category
                                    </span>
                                </label>
                                <select 
                                    name="division_list" 
                                    class="placeholder:text-textmuted text-sm selected_seasion_type"  
                                    wire:change="GetCategory($event.target.value)" 
                                    wire:key="category-0">
                                    <option value="" hidden>Filter category</option>
                                    @foreach ($categories as $category)
                                        <option 
                                            value="{{ $category->id }}" 
                                            {{$selectedCategory==$category->id?"selected":""}} 
                                            wire:key="category-{{ $category->id }}">
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <form wire:submit.prevent="nightHaltSubmitForm">
                            <table class="table whitespace-nowrap table-bordered table-bordered-primary border-primary/10 min-w-full new-activity">
                                <thead class="uppercase">
                                    <tr class="border-b !border-primary/30 bg-warning/10">
                                        <th scope="col" class="!text-center">
                                            Name Of Night Halt
                                        </th>
                                    </tr>
                                    <tr class="border-b !border-primary/30 bg-warning/10">
                                        <th scope="col" class="!text-center">
                                            <div class="custom-fulldiv">
                                                <div class="nd-field">
                                                    <div class="nd-group">
                                                        <input type="text" wire:model="nightHalt.day" wire:keyup="validateDaysAndNights($event.target.value)"
                                                            class="form-control form-control-sm" readonly>
                                                        <span>D</span>
                                                    </div>
                                                    <div class="spacer">/</div>
                                                    <div class="nd-group">
                                                        <input type="text" wire:model="nightHalt.night" wire:keyup="validateDaysAndNights($event.target.value)"
                                                            class="form-control form-control-sm" readonly>
                                                        <span>N</span>
                                                    </div>
                                                </div>
                                                @if($active_night_distribution==1)
                                                    <div class="nd-group-wide">
                                                        <input type="text" wire:model="nightHalt.night_distribution" wire:keyup="validateNightDistribution"
                                                        class="nd-input-placeholder form-control form-control-sm !text-center refresh_component" placeholder="Night distributaion">
                                                        <span>Example: (4+2+1)</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($itinerary_journey) > 0)
                                        @foreach($itinerary_journey as $index => $item)
                                            <tr>
                                                <td>
                                                    <div class="custom-fulldiv">
                                                        <div class="night-group max-w-75">
                                                            <input type="text" wire:model="itinerary_journey.{{ $index }}"
                                                                class="form-control form-control-sm" readonly>
                                                            <span>N</span>
                                                        </div>
                                                        <div>
                                                            <select 
                                                                wire:model="itinerary_journey_divisions.{{$index}}" 
                                                                class="placeholder:text-textmuted text-sm selected_seasion_type" wire:key="itinerary-journey-divisions-{{$index}}-0" wire:change="updateJourneyDivision({{$index}},$event.target.value)">
                                                                <option value="" hidden>Select Divisions</option>
                                                                @foreach ($divisions as $division)
                                                                    <option 
                                                                        value="{{ $division->id }}"
                                                                        wire:key="itinerary-journey-divisions-{{$index}}-{{ $division->id }}">
                                                                        {{ $division->name }}({{$division->code}})
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                
                                    <tr>
                                        <td>
                                            @if($newPresetError)
                                                <div class="alert alert-danger">
                                                    {{ $newPresetError }}
                                                </div>
                                            @endif
                                            @if(count($itinerary_journey)==count($itinerary_journey_divisions))
                                                <div class="text-end mt-3">
                                                    <button type="submit" class="ti-btn ti-btn-primary-full !py-1 pt-0 ti-btn-wave me-[0.375rem]">
                                                        <i class="fa-solid fa-save"></i> Save
                                                    </button>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- Model --}}
    @endif

    {{-- wire:target="addAboutDescHighlight, ItineraryImageDelete, deleteDayImage, showImageModal, OpenNewRouteModal, RemoveDayRouteItem,RemoveDayRoute,RemoveDayHotel, closeModal, getActivityOrSightseeing, getRoute, uploadMainBanner, main_banner, uploadDestinationSlider, removeAboutDescHighlight,getHotel,updateSelectedRoom,ResetItinerary,updateSelectedRoomPlan,GetRoomAddonPlan,decreaseQuantity,increaseQuantity,getPerDayCab,RemovePerDayCabItem,NewNightHalt,validateNightDistribution,GetCategory,nightHaltSubmitForm,updateJourneyDivision,toggleInclusion,CreateNewRoute" --}}

    <div wire:loading class="loader">
        <div class="spinner">
        <img src="{{asset('build/assets/images/media/loader.svg')}}" alt="">
        </div>
    </div>
</div>
@section('scripts')
<!-- Include Dragula JS -->
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
<script>
    $(document).ready(function () {
        $('.select2').select2();
        $('.select2').on('change', function (e) {
            var value = $(this).select2("val");
            var index = $(this).data("id");
            @this.call('getHotel', index,value);
        });
        $('#existing_night_halt_details').select2({
            placeholder: "Filter package",
            allowClear: true // optional: adds "x" to clear selections
        });
        $('#existing_night_halt_details').on('change', function (e) {
            var value = $(this).select2("val");
            @this.call('ChangeNightHalt', value);
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