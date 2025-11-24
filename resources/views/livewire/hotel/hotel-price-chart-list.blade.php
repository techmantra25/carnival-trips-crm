<div>
    <style>
        #hotel_list.select2-hidden-accessible + .select2-container {
            width: 408.8px !important;
        }
        #room_list.select2-hidden-accessible + .select2-container {
            width: 191.363px !important;
        }
        #hotel_category.select2-hidden-accessible + .select2-container {
            width: 200px !important;
        }
        
    </style>
    <div class="grid grid-cols-12 gap-6">

        <div class="xl:col-span-12 col-span-12">
            <div class="box custom-box">
    
                <!--[if BLOCK]><![endif]-->
                <div class="box-body">
                    <div class="flex justify-between">
                        <div>
                            <div class="badge bg-outline-success cursor-pointer">
                                <span>No of Result: {{count($data)}}</span>
                            </div>
    
                        </div>
                        <div class="flex">
                            <div wire:ignore>
                                <label for="">
                                    <span class="badge gap-2 bg-danger/10 text-danger uppercase">
                                        Hotels
                                    </span>
                                </label>
                                <select name="hotel_list" class="placeholder:text-textmuted text-sm selected_seasion_type select2" wire:key="hotel-0"  multiple="multiple" id="hotel_list">
                                    <option value="" hidden="">Filter Hotel</option>
                                    @foreach ($hotels as $key=>$item)
                                        <option value="{{$item->id}}" wire:key="hotel-{{$key+1}}">{{$item->name}} | {{optional($item->DivisionData)->name??"No Division"}}</option>
                                    @endforeach
                                    <!--[if ENDBLOCK]><![endif]-->
                                </select>
                            </div>
                            <div wire:ignore>
                                <label for="">
                                    <span class="badge gap-2 bg-danger/10 text-danger uppercase">
                                        Rooms
                                    </span>
                                </label>
                                <select name="room_list" class="placeholder:text-textmuted text-sm selected_seasion_type select2" wire:key="room_list-0"  multiple="multiple" id="room_list">
                                    <option value="" hidden="">Filter Rooms</option>
                                    @foreach ($room_name_list as $key=>$item)
                                        <option value="{{$item}}" wire:key="room_list-{{$key+1}}">{{$item}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="">
                                    <span class="badge gap-2 bg-danger/10 text-danger uppercase">
                                        Type
                                    </span>
                                </label>
                                <select name="chart_title" wire:model="chart_title" class="placeholder:text-textmuted text-sm selected_seasion_type" wire:key="chart_title-0" wire:change="ChangeType($event.target.value)">
                                    <option value="" hidden="">Filter by type</option>
                                        <option value="Actual Price Chart" wire:key="chart_title-1">Actual Price Chart</option>
                                        <option value="Selling Price Chart" wire:key="chart_title-2">Selling Price Chart</option>
                                </select>
                            </div>
                            <div>
                                <a href="javascript:void(0)" wire:click="ResetPage" class="ti-btn ti-btn-sm ti-btn-soft-danger !border !border-danger/20">
                                    <i class="ri-refresh-line"></i>
                                </a>
                            </div>
                            <div class="ml-2">
                                <button wire:click="toggleInputs" class="py-1 px-3 inline-flex items-center gap-x-2 text-sm rounded-sm ti-btn-{{ $isDisabled ? 'secondary' : 'success' }}-gradient text-white me-[0.375rem] !mb-2">
                                    {{ $isDisabled ? 'Enable Editing' : 'Disable Editing' }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        
                        <div class="overflow-x-auto scrollbar">
                            <table class="whitespace-nowrap table-bordered table-bordered-primary border-primary/10 min-w-full">
                                <thead class="bg-gray-100 text-xs">
                                    @php
                                        $main_plan_colspan = 0;
                                        foreach($season_main_plan as $main_k=>$main_plan){
                                            $plan_items = explode(', ',$main_plan['plan_item']);
                                            if(count($plan_items)>1){
                                                $main_plan_colspan += count($plan_items);
                                            }else{
                                                $main_plan_colspan += 1;
                                            }
                                        }
                                        
                                        $addon_plan_colspan = 0;
                                        foreach($season_addon_plan as $addon_plan){
                                            $plan_items = explode(', ',$addon_plan['plan_item']);
                                            if(count($plan_items)>1){
                                                $addon_plan_colspan += count($plan_items);
                                            }else{
                                                $addon_plan_colspan += 1;
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <th rowspan="2" class="border border-gray-300 p-2 text-center align-middle">HOTELS</th>
                                        <th rowspan="2" class="border border-gray-300 p-2 text-center align-middle">ROOMS</th>
                                        <th colspan="{{$main_plan_colspan}}" class="border border-gray-300 p-2 text-center bg-yellow-50">SEASON WISE PRICE CHART</th>
                                        <th colspan="{{$addon_plan_colspan}}" class="border border-gray-300 p-2 text-center bg-yellow-50">ADDON PRICE CHART</th>
                                    </tr>
                                    <tr class="text-[11px]">
                                        @foreach($season_main_plan as $k=>$main_plan)
                                            @php
                                                $plan_items = explode(', ',$main_plan['plan_item']);
                                                $plan_items_class ="";
                                                if($k==0){
                                                    $plan_items_class = 'bg-blue-50';
                                                }elseif($k==1){
                                                    $plan_items_class = 'bg-red-50';
                                                }elseif($k==2){
                                                    $plan_items_class = 'bg-green-50';
                                                }else{
                                                    $plan_items_class = 'bg-warning-50';
                                                }
                                            @endphp
                                        <th class="border {{$plan_items_class}} border-gray-300 p-2 text-center" colspan="{{count($plan_items)}}">{{strtoupper($main_plan['title'])}}</th>
                                        @endforeach
                                        @foreach($season_addon_plan as $k=>$addon_plan)
                                            @php
                                                $addon_plan_items = explode(', ',$addon_plan['plan_item']);
                                                $plan_items_class ="";
                                                if($k==0){
                                                    $plan_items_class = 'bg-blue-50';
                                                }elseif($k==1){
                                                    $plan_items_class = 'bg-red-50';
                                                }elseif($k==2){
                                                    $plan_items_class = 'bg-green-50';
                                                }else{
                                                    $plan_items_class = 'bg-yellow-50';
                                                }
                                            @endphp
                                        <th class="border {{$plan_items_class}} border-gray-300 p-2 text-center" colspan="{{count($addon_plan_items)}}">{{strtoupper($addon_plan['title'])}}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="text-center text-xs">
                                    @forelse ($data as $item)
                                        @php 
                                            $roomCount = isset($item['room']) && is_array($item['room'])? count($item['room']):0;
                                            $sl = 0;
                                        @endphp
                                        @if(isset($item['room']) && is_array($item['room']))
                                            @foreach ($item['room'] as $roomIndex => $room)
                                                <tr wire:key="hotel-room-{{$roomIndex}}">
                                                    @if ($sl === 0)
                                                        <td rowspan="{{ $roomCount }}" class="border border-gray-300 p-2 font-bold text-blue-900 align-middle">
                                                            <p class="mb-1 uppercase">{{ $item['hotel_name'] }} 
                                                                <span class="text-pink-500">({{ $item['hotel_division'] }})</span>
                                                            </p>
                                                        </td>
                                                    @endif
                                    
                                                    {{-- Room Name --}}
                                                    <td class="border border-gray-300 p-2 uppercase">{{ $room['room_name'] }}</td>
                                                    
                                                    {{-- SEASON PRICES --}}
                                                    @php
                                                        $season_prices_sl = 0;
                                                    @endphp
                                                    @foreach ($room['season_prices'] as $main_plan_title=>$season_prices)
                                                        @php
                                                            $main_plan_title = str_replace('_', ' ', $main_plan_title);
                                                            $plan_items_class ="";
                                                            if($season_prices_sl==0){
                                                                $plan_items_class = 'primary';
                                                            }elseif($season_prices_sl==1){
                                                                $plan_items_class = 'danger';
                                                            }elseif($season_prices_sl==2){
                                                                $plan_items_class = 'success';
                                                            }else{
                                                                $plan_items_class = 'warning';
                                                            }
                                                        @endphp
                                                       
                                                        @foreach ($season_prices as $plan_name=>$plan_item)
                                                            <td class="border border-gray-300 p-2 text-center">
                                                                <div>
                                                                    <label class="col-form-label">
                                                                        <p class="badge gap-2 bg-{{$plan_items_class}}/10 text-{{$plan_items_class}}">
                                                                            {{$plan_name}}
                                                                        </p>
                                                                    </label>
                                                                </div>
                                                                <input type="text" wire:key="price-input-{{ $plan_item['item_id'] }}"
                                                                   {{ $isDisabled ? 'disabled' : '' }} class="form-control form-control cw-60 form-control-sm max-w-16 text-center price_chart_value refresh_component"
                                                                    value="{{$plan_item['item_price']}}" onkeyup="validateRoomPrice(this, 'main', {{ $item['hotel_id'] }}, {{ $room['room_id'] }},'{{$chart_title}}','{{$chart_type}}','{{ucwords($main_plan_title)}}','{{$plan_name}}',{{$plan_item['item_id']}})">
                                                            </td>
                                                        @endforeach
                                                        @php
                                                            $season_prices_sl++;
                                                        @endphp
                                                    @endforeach
                                                    @php
                                                        $addon_prices_sl = 0;
                                                    @endphp
                                                    @foreach ($room['addon_prices'] as $addon_plan_title=>$addon_prices)
                                                        @php
                                                            $addon_plan_title = str_replace('_', ' ', $addon_plan_title);
                                                            $plan_items_class ="";
                                                            if($addon_prices_sl==0){
                                                                $plan_items_class = 'primary';
                                                            }elseif($addon_prices_sl==1){
                                                                $plan_items_class = 'danger';
                                                            }elseif($addon_prices_sl==2){
                                                                $plan_items_class = 'success';
                                                            }else{
                                                                $plan_items_class = 'warning';
                                                            }
                                                        @endphp
                                                        @foreach ($addon_prices as $addon_plan_name=>$addon_plan_item)
                                                            <td class="border border-gray-300 p-2 text-center">
                                                                <div>
                                                                    <label class="col-form-label">
                                                                        <p class="badge gap-2 bg-{{$plan_items_class}}/10 text-{{$plan_items_class}}">
                                                                            {{$addon_plan_name}}
                                                                        </p>
                                                                    </label>
                                                                </div>
                                                                <input type="text" wire:key="price-input-{{ $addon_plan_item['item_id'] }}"
                                                                    {{ $isDisabled ? 'disabled' : '' }} class="form-control form-control cw-60 form-control-sm max-w-16 text-center price_chart_value refresh_component"
                                                                    value="{{$addon_plan_item['item_price']}}" onkeyup="validateRoomPrice(this, 'addon', {{ $item['hotel_id'] }}, {{ $room['room_id'] }},'{{$chart_title}}','{{$chart_type}}','{{ucwords($addon_plan_title)}}','{{$addon_plan_name}}',{{$addon_plan_item['item_id']}})">
                                                            </td>
                                                        @endforeach
                                                        @php
                                                            $addon_prices_sl++;
                                                        @endphp
                                                    @endforeach
                                    
                                                </tr>
                                                @php $sl++; @endphp
                                            @endforeach
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="14" class="border border-gray-300 p-2 text-red-500 text-center">
                                                No hotel price data available.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:loading class="loader" wire:target="ResetPage,ChangeType,getHotel,getRoom,toggleInputs">
        <div class="spinner">
            <img src="{{asset('build/assets/images/media/loader.svg')}}" alt="">
        </div>
    </div>
</div>
<script>
    // window.addEventListener('refreshComponent', function (event) {
    //     document.querySelectorAll('.refresh_component').forEach(element => {
    //         element.value = ''; // Clear input fields
    //     });
    // });
    $(document).ready(function () {
        $('.select2').select2();
        $('#hotel_list').select2({
            placeholder: "Filter by hotels",
            allowClear: true // optional: adds "x" to clear selections
        });
        $('#room_list').select2({
            placeholder: "Filter by rooms",
            allowClear: true // optional: adds "x" to clear selections
        });
        $('#hotel_list').on('change', function (e) {
            var value = $(this).select2("val");
            @this.call('getHotel', value);
        });
        $('#room_list').on('change', function (e) {
            var value = $(this).select2("val");
            @this.call('getRoom', value);
        });
    });
</script>
