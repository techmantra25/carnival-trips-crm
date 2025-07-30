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
                <div class="box-header flex justify-end">
                    
                    <div>
                       <div class="grid grid-cols-1 hover:grid-cols-6">
                            <label for="">
                                <span class="badge gap-2 bg-danger/10 text-danger uppercase">
                                    Destinations
                                 </span>
                            </label>
                            <select 
                                name="destination_list" 
                                class="placeholder:text-textmuted text-sm selected_seasion_type"  
                                wire:change="getDestination($event.target.value)" 
                                wire:key="destination-0">
                                <option value="" hidden>Filter Destinations</option>
                                @foreach ($desitinations as $destination_item)
                                    <option 
                                        value="{{ $destination_item->id }}" 
                                        {{$selectedDestination == $destination_item->id ? "selected" : ""}} 
                                        wire:key="destination-{{ $destination_item->id }}">
                                        {{ $destination_item->name }}
                                    </option>
                                @endforeach
                            </select>
                       </div>
                    </div>
                    <div>
                        <div class="grid grid-cols-1 hover:grid-cols-6">
                            <label for="">
                                <span class="badge gap-2 bg-danger/10 text-danger uppercase">
                                   Hotel Category
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
                    
                    {{-- <div class="prism-toggle mt-5">
                        <a href="javascript:void(0)" wire:click="NewPresetItinerary('yes')" class="ti-btn ti-btn-primary-full !py-1 pt-0 ti-btn-wave  me-[0.375rem]"><i class="fa-solid fa-plus"></i>Preset Itinerary Builder</a>
                    </div> --}}
                    <div class="mt-5">
                        <a href="{{route('admin.itinerary.preset.list')}}" class="ti-btn ti-btn-sm ti-btn-soft-danger !border !border-danger/20">
                            <i class="ri-refresh-line"></i>
                        </a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="flex justify-between">
                        <div class="badge bg-outline-success cursor-pointer">
                            <span>No of Result: {{count($post_lead_itineraries)}}</span>
                        </div>
                        <div>
                            <input type="text" class="badge bg-outline-primary w-xs" wire:keyup="QuickSearch($event.target.value)" placeholder="Quick Search..">
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="table-responsive">
                            <table
                                class="table whitespace-nowrap table-bordered table-bordered-primary border-primary/10 min-w-full">
                                <thead class="uppercase">
                                    <tr class="border-b !border-primary/30">
                                        <th scope="col" class="!text-center">SL No.</th>
                                        <th scope="col" class="!text-center">Guest Details</th>
                                        <th scope="col" class="!text-center">Destination</th>
                                        <th scope="col" class="!text-center">H. Category</th>
                                        <th scope="col" class="!text-center">Package Details</th>
                                        <th scope="col" class="!text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   @forelse ($post_lead_itineraries as $k=> $pre_item)
                                    <tr>
                                        <td class="!text-center"><span class="badge bg-primary/10 text-primary">{{$k+1}}</span> </td>
                                        <td class="!text-center">
                                            <p class="badge bg-primary text-white">{{$pre_item->lead?$pre_item->lead->unique_id:"N/A" }}</p>
                                            <p class="mt-1"> {{ $pre_item->lead?$pre_item->lead->customer_name:"N/A" }}
                                            </p>
                                            <div>
                                                <i class="fa-regular fa-envelope text-danger"></i>
                                                {{ optional($pre_item->lead)->customer_email ?? 'N/A' }}
                                            </div>
                                            <div>
                                                <i class="fa-solid fa-phone text-success"></i>
                                                {{ optional($pre_item->lead)->customer_mobile ? '+91' . $pre_item->lead->customer_mobile : 'N/A' }}
                                            </div>
                                            <div>
                                                <i class="fa-brands fa-whatsapp text-success"></i>
                                                {{ optional($pre_item->lead)->customer_whatsapp ? '+91' . $pre_item->lead->customer_whatsapp : 'N/A' }}
                                            </div>
                                            
                                        </td>
                                        <td class="!text-center">{{$pre_item->destination->name}}</td>
                                        <td class="!text-center">{{$pre_item->hotelCategory->name}}</td>
                                        <td class="!text-center">
                                            <div>
                                                <i class="fa-regular fa-clock text-danger"></i>
                                                Arrival Date: {{ \Carbon\Carbon::parse($pre_item->lead->arrival_date)->format('d M Y') }}
                                            </div>
                                            <div>
                                                <i class="fa-regular fa-clock text-danger"></i>
                                                Departure Date: {{ \Carbon\Carbon::parse($pre_item->lead->departure_date)->format('d M Y') }}
                                            </div>
                                            <div>
                                                <i class="fa-regular fa-clock text-danger"></i>
                                                Trip Duration: {{ $pre_item->lead->travel_duration_text }}
                                            </div>
                                            <div>
                                                <i class="fa-solid fa-users text-primary"></i>
                                                PAX: {{ optional($pre_item->lead)->number_of_travellor ?? 'N/A' }}
                                            </div>
                                            <div>
                                                <i class="fa-solid fa-user text-primary"></i>
                                                Adults: {{ optional($pre_item->lead)->number_of_adults ?? 'N/A' }}
                                            </div>
                                            <div>
                                                <i class="fa-solid fa-child text-primary"></i>
                                                Children: {{ optional($pre_item->lead)->number_of_children ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="!text-center">
                                            {{$pre_item->itinerary_syntax}} <br>
                                            <span class="badge bg-danger/10 text-danger">{{$pre_item->itinerary_journey}}</span> <br>
                                            @php
                                                $encryptedId = Crypt::encrypt($pre_item->id);
                                            @endphp
                                            <a href="{{route('admin.itinerary.preset.build', $encryptedId)}}" class="badge bg-primary/10 text-primary">Itinerary <i class="fas fa-arrow-right ml-1"></i></a>
                                        </td>
                                    </tr>
                                   @empty
                                   <tr>
                                        <td colspan="6">
                                            <div class="alert alert-danger">
                                                Result not found
                                            </div>
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
    {{-- Model --}}
    {{-- Model --}}

    <div wire:loading class="loader" wire:target="getDestination,GetCategory">
        <div class="spinner">
        <img src="{{asset('build/assets/images/media/loader.svg')}}" alt="">
        </div>
    </div>
</div>
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    
</script>
@endsection
