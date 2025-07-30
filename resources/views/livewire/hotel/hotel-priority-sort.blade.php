<div>
    <style>
        #hotel_list.select2-hidden-accessible + .select2-container {
            width: 300px !important;
        }
    </style>
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
                                        Divisions
                                    </span>
                                </label>
                                <select name="division" class="placeholder:text-textmuted text-sm selected_seasion_type select2" wire:key="division-0"  multiple="multiple" id="division">
                                    <option value="" hidden="">Filter division</option>
                                    @foreach ($divisions as $key=>$item)
                                        <option value="{{$item->id}}" wire:key="division-{{$key+1}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div wire:ignore>
                                <label for="">
                                    <span class="badge gap-2 bg-danger/10 text-danger uppercase">
                                        Hotel Categories
                                    </span>
                                </label>
                                <br>
                                <select name="hotel_category" class="placeholder:text-textmuted text-sm selected_seasion_type select2" wire:key="hotel_category-0"  multiple="multiple" id="hotel_category">
                                    @foreach ($hotel_categories as $key=>$item)
                                        <option value="{{$item->id}}" wire:key="hotel_category-{{$key+1}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div wire:ignore>
                                <label for="">
                                    <span class="badge gap-2 bg-primary/10 text-primary uppercase">
                                        New Hotels
                                    </span>
                                </label>
                                <br>
                                <select name="hotel_list" class="placeholder:text-textmuted text-sm selected_seasion_type select2" wire:key="hotel_list-0" id="hotel_list">
                                    <option value="" selected hidden>Add new hotel</option>
                                    @foreach ($hotels as $key=>$item)
                                        <option value="{{$item->id}}" wire:key="hotel_list-{{$key+1}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <a href="javascript:void(0)" wire:click="ResetPage" class="ti-btn ti-btn-sm ti-btn-soft-danger !border !border-danger/20">
                                    <i class="ri-refresh-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="overflow-x-auto">
                            <table class="whitespace-nowrap table-bordered table-bordered-primary border-primary/10 min-w-full">
                                <thead class="bg-gray-100 text-xs">
                                    
                                    <tr>
                                        <th class="border border-gray-300 p-2 text-center align-middle uppercase">SL</th>
                                        <th class="border border-gray-300 p-2 text-center align-middle uppercase">Divisions</th>
                                        <th class="border border-gray-300 p-2 text-center align-middle uppercase">Hotel Category</th>
                                        <th class="border border-gray-300 p-2 text-center align-middle uppercase">
                                            Hotels
                                            <div class="text-[10px] text-red-500 font-normal normal-case mt-1">
                                                (Drag & drop to set hotel priority)
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-center text-xs">
                                    @php $sl = 1; @endphp
                                        @foreach ($data as $city)
                                            @foreach ($city->priority_hotels->groupBy('hotel_category') as $categoryId => $hotelsByCategory)
                                                @php 
                                                    $categoryName = optional($hotelsByCategory->first()->CategoryData)->name ?? 'No Category';
                                                    $divisionName = optional($hotelsByCategory->first()->DivisionData)->name ?? 'No Division';
                                                @endphp
                                                <tr>
                                                    <td class="border border-gray-300 p-2 text-center">{{ $sl++ }}</td>
                                                    <td class="border border-gray-300 p-2 text-center">{{ $divisionName }}</td>
                                                    <td class="border border-gray-300 p-2 text-center">{{ $categoryName }}</td>
                                                    <td class="border border-gray-300 p-2">
                                                        <div class="space-y-2 rounded-md border-2 border-dotted p-2 border-slate-500 transition-all duration-300 ease-in-out"
                                                                x-data
                                                                x-init="Sortable.create($el, {
                                                                    animation: 150,
                                                                    handle: '.handle',
                                                                    ghostClass: 'bg-yellow-100',
                                                                    dragClass: 'opacity-1000',
                                                                    onEnd: (evt) => {
                                                                        const orderedIds = Array.from($el.children).map(el => el.dataset.id);
                                                                        @this.call('updatePriorityOrder', {{ $categoryId }}, orderedIds);
                                                                    }
                                                                })"
                                                            >
                                                            @foreach ($hotelsByCategory as $k => $hotel)
                                                                <div 
                                                                    class="flex items-center gap-3 px-3 py-2 bg-gray-50 border rounded shadow-sm cursor-move hover:bg-white transition-all handle"
                                                                    data-id="{{ $hotel->id }}"
                                                                >
                                                                    <!-- SL Badge -->
                                                                    <div class="w-6 h-6 flex items-center justify-center text-xs font-semibold bg-blue-200 text-gray-700 rounded-full">
                                                                        {{ $k + 1 }}
                                                                    </div>

                                                                    <!-- Hotel Name -->
                                                                    <span class="text-sm text-gray-700 font-medium">{{ $hotel->name }}</span>
                                                                    @if($k==0)
                                                                        <span class="badge gap-2 bg-success/10 text-success uppercase">First Priority</span>
                                                                    @endif
                                                                    
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        {{-- <div class="space-y-2 rounded-md border-2 border-dotted p-2 border-slate-500">
                                                            @foreach ($hotelsByCategory as $k => $hotel)
                                                                <div class="flex items-center gap-3 px-3 py-2 bg-gray-50 border rounded shadow-sm cursor-move hover:bg-white transition-all">
                                                                    <!-- SL Badge -->
                                                                    <div class="w-6 h-6 flex items-center justify-center text-xs font-semibold bg-blue-200 text-gray-700 rounded-full">
                                                                        {{ $k + 1 }}
                                                                    </div>
                                                                    
                                                                    <!-- Hotel Name -->
                                                                    <span class="text-sm text-gray-700 font-medium">{{ $hotel->name }}</span>
                                                                </div>
                                                            @endforeach
                                                        </div> --}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:loading class="loader" wire:target="ResetPage,getHotelCategory,getDivision,getHotel,updatePriorityOrder">
        <div class="spinner">
            <img src="{{asset('build/assets/images/media/loader.svg')}}" alt="">
        </div>
    </div>
</div>

<script>
     

    $(document).ready(function () {
        $('.select2').select2();
        $('#hotel_category').select2({
            placeholder: "Filter by hotel category",
            allowClear: true // optional: adds "x" to clear selections
        });
        $('#division').select2({
            placeholder: "Filter by division",
            allowClear: true // optional: adds "x" to clear selections
        });
        $('#hotel_list').select2({
            placeholder: "Add new hotel",
            allowClear: true // optional: adds "x" to clear selections
        });
        $('#hotel_category').on('change', function (e) {
            var value = $(this).select2("val");
            @this.call('getHotelCategory', value);
        });
        $('#division').on('change', function (e) {
            var value = $(this).select2("val");
            @this.call('getDivision', value);
        });
        $('#hotel_list').on('change', function (e) {
            var value = $(this).select2("val");
            @this.call('getHotel', value);
        });
    });
</script>
