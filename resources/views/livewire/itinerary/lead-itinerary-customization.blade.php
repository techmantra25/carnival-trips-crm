<div>
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
                    <div class="flex justify-between pb-2">
                        <div class="flex">
                            <div class="mx-2">
                                <p class="badge gap-2 bg-danger/10 text-danger uppercase">Destination</p><hr>
                                <div class="badge bg-outline-primary cursor-pointer !font-normal !text-sm uppercase">
                                    <span> {{optional($leadData->destination)->name??'N/A'}}</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="flex flex-wrap gap-4 mb-6 text-center cursor-pointer">
                              <label class="bg-success/10 text-dark uppercase text-sm">
                                <input type="radio" name="package_type" value="general" wire:model="selected_package_type" class="hidden peer cursor-pointer" wire:change="updatePackageType('general')" />
                                <div class="package-card-content">
                                  General Package
                                  <span class="text-sm font-normal text-blue-600 block">₹9,999</span>
                                </div>
                              </label>
                          
                              <label class="bg-success/10 text-dark uppercase text-sm">
                                <input type="radio" name="package_type" value="vip" wire:model="selected_package_type" class="hidden peer cursor-pointer" wire:change="updatePackageType('vip')" />
                                <div class="package-card-content">
                                  VIP Package
                                  <span class="text-sm font-normal text-blue-600 block">₹21,999</span>
                                </div>
                              </label>
                            </div>
                        </div>  
                        <div class="flex">
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
                                        <p class="text-md font-semibold mt-2">{{optional($leadData->destination)->name??'N/A'}} Trip: <span class="font-normal">{{$leadData->travel_duration_text}}</span></p>
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
                                                        <div>
                                                            <span class="badge gap-2 bg-danger/10 text-danger uppercase text-small m-2"><i class="fas fa-hotel"></i> Hotel  <span class="custom-header-separator">|</span> 1 Night<span class="custom-header-separator">|</span> in {{$division_item['division_name']}}</span>
                                                            <div class="grid grid-cols-12 sm:gap-x-6 sm:gap-y-4">
                                                                <div class="md:col-span-8 col-span-12 mb-4 mx-2 itinerary-build">
                                                                    <div class="custom-card mt-2" wire:key="day-{{$division_index}}-hotel-{{$package[$division_index]['hotel']['selected']['id']}}">
                                                                        <div class="custom-hotel-container relative !overflow-visible">
                                                                            <div class="custom-hotel-content">
                                                                                <div class="custom-hotel-image-container">
                                                                                    <div class="custom-image-carousel">
                                                                                        <div class="custom-image-wrapper" style="width: 225px; height: 120px;">
                                                                                            <img class="custom-hotel-image" width="225" height="120"
                                                                                                src="{{asset($package[$division_index]['hotel']['selected']['image'])}}"
                                                                                                alt="Hotel Image">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                            
                                                                                <div class="custom-hotel-details">
                                                                                    <div class="custom-hotel-details-top">
                                                                                        <p class="text-black-600 text-base italic">{{$package[$division_index]['hotel']['selected']['name']}}</p>
                                                                                        <p class="text-gray-500 text-small">{{$package[$division_index]['hotel']['selected']['address']}}</p>
                                                                                        <p class="badge gap-2 bg-danger/10 text-danger uppercase text-small my-2">Rooms</p>
                                                                                        <div>
                                                                                            @if(count($package[$division_index]['hotel']['selected']['related_rooms'])>0)
                                                                                                @forelse ($package[$division_index]['hotel']['selected']['related_rooms'] as $related_rooms)
                                                                                                    <label class="hotel-preview-label relative cursor-pointer">
                                                                                                        <input type="radio" wire:model="package.{{ $division_index }}.hotel.selected.selected_room.room_id" name="day{{$division_index}}.hotel.room" value="{{$related_rooms['id']}}"
                                                                                                            class="hidden peer" wire:change="updateSelectedRoom({{$package[$division_index]['hotel']['selected']['id']}},{{$division_index}}, {{$related_rooms['id']}})" {{$related_rooms['id']==$package[$division_index]['hotel']['selected']['selected_room']['room_id']?"checked":""}}>
                                                                                                        <!-- Hotel Selection Box -->
                                                                                                        <div class="hotel-card">
                                                                                                            <span class="hotel-name">{{$related_rooms['room_name']}}</span>
                                                                                                            <!-- Selected Indicator -->
                                                                                                            <span class="checkmark">✓</span>
                                                                                                        </div>
                                                                                                    </label>
                                                                                                    @empty
                                                                                                    <div class="alert alert-warning text-sm italic">
                                                                                                        rooms not added on this hotel
                                                                                                    </div>
                                                                                                @endforelse
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            {{-- <button type="button" wire:click="RemoveDayHotel(1,'day_hotel', 86)" class="delete-icon">✖
                                                                            </button> --}}
                                                                        </div>
                                                                        <div class="room-details-card mt-2" wire:key="day-{{$division_index}}-selectedRoom-{{$package[$division_index]['hotel']['selected']['selected_room']['room_id']}}">
                                                                            <div class="text-center uppercase">
                                                                                <p class="">{{$package[$division_index]['hotel']['selected']['selected_room']['room_name']}}<span class="text-danger">(Room Capacity: {{$package[$division_index]['hotel']['selected']['selected_room']['room_capacity']}} P)</span></p>
                                                                            </div>
                                                            
                                                                            <table class="table table-bordered table-bordered-primary border-primary/10 min-w-full mt-4">
                                                                                <thead class="bg-primary/5">
                                                                                    <tr>
                                                                                        <th class="!text-center uppercase" style="width: 120px;">Plan</th>
                                                                                        <!--[if BLOCK]><![endif]-->
                                                                                        <th class="!text-center uppercase">Meal Plan
                                                                                        </th>
                                                                                        <th class="!text-center uppercase">CWNB
                                                                                        </th>
                                                                                        <th class="!text-center uppercase">CWM
                                                                                        </th>
                                                                                        <th class="!text-center uppercase">CWB
                                                                                        </th>
                                                                                        <!--[if ENDBLOCK]><![endif]-->
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody class="">
                                                                                    <tr>
                                                                                        <td class="align-top">
                                                                                        
                                                                                            <span class="badge gap-2 bg-danger/10 text-danger">
                                                                                                <span class="w-1.5 h-1.5 inline-block bg-danger rounded-full">
                                                                                                </span>
                                                                                               {{$package[$division_index]['season']}}
                                                                                            </span>
                                                            
                                                                                            <div class="mt-2">
                                                                                                @foreach ($package[$division_index]['hotel']['selected']['selected_room']['main_related_plans'] as $related_plan_index=>$main_related_plans)
                                                                                                    <label
                                                                                                        class="plan-type-preview-label relative cursor-pointer"
                                                                                                        wire:key="day-{{$division_index}}-room-plan-{{$related_plan_index}}">
                                                                                                        <input type="radio" class="hidden peer"
                                                                                                            wire:model="package.{{ $division_index }}.hotel.selected.selected_room.main_plan_name"
                                                                                                            name="day.{{$division_index}}.hotel.room_plan" value="{{$main_related_plans['plan_item']}}"
                                                                                                            wire:change="updateSelectedRoomPlan({{$main_related_plans['hotel_id']}}, {{$main_related_plans['room_id']}}, {{$division_index}}, '{{$main_related_plans['plan_item']}}','{{$main_related_plans['item_price']}}')" @checked($package[$division_index]['hotel']['selected']['selected_room']['main_plan_name'] == $main_related_plans['plan_item'])>
                                                                                                        <div class="hotel-card">
                                                                                                            <span class="hotel-name">{{$main_related_plans['plan_item']}}</span>
                                                                                                            <span class="checkmark">✓</span>
                                                                                                        </div>
                                                                                                    </label>
                                                                                                @endforeach
                                                                                                
                                                                                            </div>
                                                                                            <div class="custom-hotel-details-top">
                                                                                                <!--[if BLOCK]><![endif]-->
                                                                                                <div class="flex justify-center">
                                                                                                    <label class="cab-preview-label relative cursor-pointer">
                                                                                                        <div class="cab-card card-price">
                                                                                                            <span class="hotel-name italic text-yellow-600">{{env('DEFAULT_CURRENCY_SYMBOL')}}{{isset($package[$division_index]['hotel']['selected']['selected_room']['main_plan_price'])?$package[$division_index]['hotel']['selected']['selected_room']['main_plan_price']:"0"}}</span>
                                                                                                        </div>
                                                                                                    </label>
                                                                                                </div>
                                                                                                <!-- Quantity Controls -->
                                                                                                <div class="flex items-center space-x-2 justify-center">
                                                                                                    <i class="fas fa-minus-circle text-red-500 cursor-pointer text-base"
                                                                                                        wire:click="decreaseQuantity(1,145)"></i>
                                                                                                    <input type="number" id="custom_quantity_input145" class="custom-quantity-input"
                                                                                                        wire:model="itemQuantity.1.145" wire:keyup="updateQuantity(1,145)" min="1"
                                                                                                        value="{{isset($package[$division_index]['hotel']['selected']['selected_room']['main_plan_quantity'])?$package[$division_index]['hotel']['selected']['selected_room']['main_plan_quantity']:"0"}}">
                                                                                                    <i class="fas fa-plus-circle text-green-500 cursor-pointer text-base"
                                                                                                        wire:click="increaseQuantity(1,145)"></i>
                                                                                                </div>
                                                                                                <div class="no_of_room">
                                                                                                    NO OF ROOM
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                        @foreach ($package[$division_index]['hotel']['selected']['addon_seasion_plan'] as $index_addon_season_plan=>$addon_seasion_plan)
                                                                                            <td class="!text-center uppercase align-top !p-0">
                                                                                                <div class="small-btm">
                                                                                                    {{-- <select wire:key="existing-addon-plan-{{$division_index}}-{{$index_addon_season_plan}}-100" wire:change="GetRoomAddonPlan('{{$addon_seasion_plan['title']}}',{{$division_index}}, {{$division_hotel_item['hotel_id']}}, {{$selectedRoomId}}, $event.target.value, $event.target.selectedOptions[0].getAttribute('data-price'))"> --}}
                                                                                                    <select wire:key="existing-addon-plan-{{$division_index}}-{{$index_addon_season_plan}}-100">
                                                                                                        <option value="">Choose {{$addon_seasion_plan['title']}}</option>
                                                                                                        @foreach($addon_seasion_plan['item'] as $addon_plan_index=> $addon_plan_type)
                                                                                                            <option value="{{$addon_plan_type}}" wire:key="existing-addon-plan-{{$division_index}}-{{$index_addon_season_plan}}-{{$addon_plan_index}}">{{$addon_plan_type}}</option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </div>
                                                                                                {{-- @foreach ($addon_seasion_plan['selected_addon_plan'] as $index_selected_addon_plan =>$selected_addon_plan)
                                                                                                    <div class="addon_plan_item" wire:key="day-1-route-0-cab-0">
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
                                                                                                                            <i class="fas fa-minus-circle text-red-500 cursor-pointer text-base" wire:click="decreaseQuantity({{$division_index}},{{$selected_addon_plan['id']}})"></i>
                                                                                                                            <input type="number"
                                                                                                                                id="custom_quantity_input{{$selected_addon_plan['id']}}" 
                                                                                                                                class="custom-quantity-input" 
                                                                                                                                wire:model="itemQuantity.{{ $division_index }}.{{$selected_addon_plan['id']}}" 
                                                                                                                                wire:keyup="updateQuantity({{$division_index}},{{$selected_addon_plan['id']}})" 
                                                                                                                                min="1" value="{{$selected_addon_plan['value_quantity']}}"/>
                                                                                                                            
                                                                                                                            <i class="fas fa-plus-circle text-green-500 cursor-pointer text-base" wire:click="increaseQuantity({{$division_index}},{{$selected_addon_plan['id']}})"></i>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <i class="far fa-times-circle text-warning cursor-pointer text-xs" wire:click="RemoveDayRouteItem({{$division_index}},{{$selected_addon_plan['id']}})"></i>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                @endforeach --}}
                                                                                            </td>
                                                                                        @endforeach
                                                                                        {{-- <td class="!text-center uppercase align-top !p-0">
                                                                                            <div class="small-btm">
                                                                                                <select wire:key="existing-addon-plan-1-1-100"
                                                                                                    wire:change="GetRoomAddonPlan('CWNB',1, 86, 44, $event.target.value, $event.target.selectedOptions[0].getAttribute('data-price'))">
                                                                                                    <option value="">Choose CWNB</option>
                                                                                                    <!--[if BLOCK]><![endif]-->
                                                                                                    <option value="3-5 YEARS" data-price="800.00"
                                                                                                        wire:key="existing-addon-plan-1-1-0">3-5 YEARS</option>
                                                                                                    <option value="3-5 YEARS + MEALS" data-price="1000.00"
                                                                                                        wire:key="existing-addon-plan-1-1-1">3-5 YEARS + MEALS</option>
                                                                                                    <!--[if ENDBLOCK]><![endif]-->
                                                                                                </select>
                                                                                            </div>
                                                                                            <!--[if BLOCK]><![endif]-->
                                                                                            <!--[if ENDBLOCK]><![endif]-->
                                                            
                                                                                        </td>
                                                                                        <td class="!text-center uppercase align-top !p-0">
                                                                                            <div class="small-btm">
                                                                                                <select wire:key="existing-addon-plan-1-2-100"
                                                                                                    wire:change="GetRoomAddonPlan('CWM',1, 86, 44, $event.target.value, $event.target.selectedOptions[0].getAttribute('data-price'))">
                                                                                                    <option value="">Choose CWM</option>
                                                                                                    <!--[if BLOCK]><![endif]-->
                                                                                                    <option value="3-5 YEARS" data-price="" wire:key="existing-addon-plan-1-2-0">3-5
                                                                                                        YEARS</option>
                                                                                                    <!--[if ENDBLOCK]><![endif]-->
                                                                                                </select>
                                                                                            </div>
                                                                                            <!--[if BLOCK]><![endif]-->
                                                                                            <!--[if ENDBLOCK]><![endif]-->
                                                            
                                                                                        </td>
                                                                                        <td class="!text-center uppercase align-top !p-0">
                                                                                            <div class="small-btm">
                                                                                                <select wire:key="existing-addon-plan-1-3-100"
                                                                                                    wire:change="GetRoomAddonPlan('CWB',1, 86, 44, $event.target.value, $event.target.selectedOptions[0].getAttribute('data-price'))">
                                                                                                    <option value="">Choose CWB</option>
                                                                                                    <!--[if BLOCK]><![endif]-->
                                                                                                    <option value="3-5 YEARS" data-price="1500.00"
                                                                                                        wire:key="existing-addon-plan-1-3-0">3-5 YEARS</option>
                                                                                                    <option value="3-5 YEARS + MEALS" data-price="1700.00"
                                                                                                        wire:key="existing-addon-plan-1-3-1">3-5 YEARS + MEALS</option>
                                                                                                    <option value="6-8 YEARS" data-price="1500.00"
                                                                                                        wire:key="existing-addon-plan-1-3-2">6-8 YEARS</option>
                                                                                                    <option value="6-8 YEARS + MEALS" data-price="1900.00"
                                                                                                        wire:key="existing-addon-plan-1-3-3">6-8 YEARS + MEALS</option>
                                                                                                    <option value="9-12 YEARS" data-price="1800.00"
                                                                                                        wire:key="existing-addon-plan-1-3-4">9-12 YEARS</option>
                                                                                                    <option value="9-12 YEARS + MEALS" data-price="2200.00"
                                                                                                        wire:key="existing-addon-plan-1-3-5">9-12 YEARS + MEALS</option>
                                                                                                    <option value="ALL AGES" data-price="1800.00"
                                                                                                        wire:key="existing-addon-plan-1-3-6">ALL AGES</option>
                                                                                                    <option value="ALL AGES + MEALS" data-price="2200.00"
                                                                                                        wire:key="existing-addon-plan-1-3-7">ALL AGES + MEALS</option>
                                                                                                    <!--[if ENDBLOCK]><![endif]-->
                                                                                                </select>
                                                                                            </div>
                                                                                            <!--[if BLOCK]><![endif]-->
                                                                                            <!--[if ENDBLOCK]><![endif]-->
                                                            
                                                                                        </td> --}}
                                                                                        <!--[if ENDBLOCK]><![endif]-->
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
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
                                                                                @if (!empty($package[$division_index]['hotel']['related_hotels']) && count($package[$division_index]['hotel']['related_hotels']) > 0)
                                                                                    @foreach ($package[$division_index]['hotel']['related_hotels'] as $hotel_index => $related_hotels)
                                                                                        @if($package[$division_index]['hotel']['selected_hotel'] !=$related_hotels['id'])
                                                                                            <option 
                                                                                                value="{{ $related_hotels['id'] ?? '' }}"
                                                                                                wire:key="hotel-{{ $division_index }}-{{ $hotel_index }}">
                                                                                                {{ $related_hotels['name'] ?? 'No Name' }}
                                                                                            </option>
                                                                                        @endif
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
                                                                </div>
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
        </div>
    </div>
    {{--New Night Halt Model --}}
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

    <div wire:loading class="loader" wire:target="ResetItinerary,NewNightHalt,validateNightDistribution,validateDaysAndNights,nightHaltSubmitForm,GetCategory,ChangeNightHalt,updatePackageType">
        <div class="spinner">
        <img src="{{asset('build/assets/images/media/loader.svg')}}" alt="">
        </div>
    </div>
</div>
<script>
    window.addEventListener('refreshComponent', function (event) {
        document.querySelectorAll('.refresh_component').forEach(element => {
            element.value = ''; // Clear input fields
        });
    });
    window.addEventListener('refreshPage', function (event) {
        window.location.reload();
    });
    $(document).ready(function () {
        $('.select2').select2();
        $('#existing_night_halt_details').select2({
            placeholder: "Filter package",
            allowClear: true // optional: adds "x" to clear selections
        });
        $('#existing_night_halt_details').on('change', function (e) {
            var value = $(this).select2("val");
            @this.call('ChangeNightHalt', value);
        });
    });
</script>
