<table class="table whitespace-nowrap min-w-full">
    <thead class="bg-secondary/10 uppercase">
        <tr class="border-b border-defaultborder">
            <th>SL</th>
            @foreach ($fields as $field)
                <th scope="col" class="!text-center">{{ strtoupper(str_replace('_', ' ', $field)) }}</th>
            @endforeach
            @if($dataType!='country_codes')
            <th scope="col" class="!text-center">ACTIONS</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $index => $item)
            @if ($dataType === 'leads')
                <tr class="text-grey" id="delete{{$item->id}}">
                    <td scope="row" class="!p-1"><span class="badge bg-primary/10 text-primary"><span class="badge bg-primary/10 text-primary">{{ $index + 1 }}</span></span></td>
                    <td class="border-b border-defaultborder !text-center">
                        <p class="badge bg-primary text-white">{{$item->unique_id }}</p>
                        <p class="mt-1"> {{ $item->customer_name }}
                                <span class="badge gap-2 bg-success/10 text-success">
                                    <span class="w-1.5 h-1.5 inline-block bg-success rounded-full"></span>Online
                                </span>
                        </p>
                        @php
                            $createdAt = \Carbon\Carbon::parse($item->created_at);
                            $diffInMinutes = $createdAt->diffInMinutes();
                            $hours = floor($diffInMinutes / 60);
                            $minutes = $diffInMinutes % 60;
                        @endphp
                        <button type="button" class="badge bg-outline-secondary my-3 me-2">
                            {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y h.i A') }}
                            <span class="badge bg-secondary ms-2 text-white"> {{ $hours > 0 ? $hours . ' hr' : '' }} {{ $minutes > 0 ? $minutes . ' min' : '' }} ago</span>
                        </button>
                    </td>
                    <td class="border-b border-defaultborder !text-center">
                        {{ $item->lead_source }} 
                        <span class="badge bg-primary text-white">{{ $item->source_type }} </span>
                    </td>
                    <td class="border-b border-defaultborder !text-center">
                        <div>
                            <i class="fa-regular fa-envelope text-danger"></i>
                            {{ $item->customer_email }}
                        </div>
                        <div>
                            <i class="fa-solid fa-phone text-success"></i>
                            +{{ $item->country_code }}{{ $item->customer_mobile }}
                        </div>
                        <div>
                            <i class="fa-brands fa-whatsapp text-success"></i>
                            +{{ $item->country_code }}{{ $item->customer_whatsapp }}
                        </div>
                    </td>
                    <td class="border-b border-defaultborder !text-center">
                        <div>
                            @if($item->package)
                            <span class="badge bg-outline-primary">{{ $item->package }}</span>
                            @endif
                        </div>
                        <div>
                            <i class="ri-map-pin-line"></i>
                            {{ $item->destination?$item->destination->name:"..." }}
                        </div>
                        <div>
                            <i class="fa-regular fa-clock text-danger"></i>
                            Start Date: {{ \Carbon\Carbon::parse($item->departure_date)->format('d M Y') }}
                        </div>
                        <div>
                            <i class="fa-regular fa-clock text-danger"></i>
                            Trip Duration: {{ $item->travel_duration_text }}
                        </div>
                    </td>
                    <td class="border-b border-defaultborder !text-center">
                        <a href="javascript:void(0);" class="hs-dropdown-toggle badge {{ \App\Helpers\CustomHelper::getLeadStatus($item->status)['color'] }} text-white" data-hs-overlay="#leads-{{ $item->id }}">
                            {{ \App\Helpers\CustomHelper::getLeadStatus($item->status)['name'] }}
                        </a>
                    </td>
                    <td class="border-b border-defaultborder !text-center">
                        @php
                            $encryptedId = Crypt::encrypt($item->id);
                        @endphp
                        <a class="ti-btn ti-btn-outline-primary ti-btn-wave !py-1 !px-2 me-[0.375rem]" href="{{route('admin.itinerary.preset.list', $encryptedId)}}">Itinerary</a>
                        {{-- <a class="ti-btn ti-btn-outline-primary ti-btn-wave !py-1 !px-2 me-[0.375rem]" >View</a> --}}
                        <button class="ti-btn ti-btn-outline-primary ti-btn-wave !py-1 !px-2 me-[0.375rem]" title="Edit Lead"><i class="fa-regular fa-pen-to-square"></i>Edit</button>

                    </td>
                    <x-global-modal
                        id="{{ $item->id }}"
                        type="leads"
                        title="Update Lead Status for {{ $item->unique_id }}"
                        content="Your content here"
                        :statuses="$extra['status']"
                        formAction="{{ route('admin.leads.update_status', $item->id) }}"
                        formMethod="POST"
                        formButton="submit"
                        selectedStatus="{{$item->status}}"
                    />
                </tr>
            @elseif($dataType=='country_codes')
                <tr class="text-grey" id="delete{{$item->id}}">
                    <td scope="row" style="text-align: center;" class="border-b border-defaultborder !text-center !p-1"><span class="badge bg-primary/10 text-primary">{{ $index + 1 }}</span></td>
                    <td scope="row" style="text-align: center;" class="border-b border-defaultborder !text-center !p-1">{{$item->country_code}}</td>
                    <td scope="row" style="text-align: center;" class="border-b border-defaultborder !text-center !p-1">{{$item->country_name}}</td>
                    <td scope="row" style="text-align: center;" class="border-b border-defaultborder !text-center !p-1">{{$item->phone_code}}</td>
                    <td scope="row" style="text-align: center;" class="border-b border-defaultborder !text-center !p-1">{{$item->phone_length}}</td>
                </tr>
            @elseif($dataType=='states')
                <tr class="text-grey" id="delete{{$item->id}}">
                    <td scope="row" class="border-b border-defaultborder !text-center !p-1"><span class="badge bg-primary/10 text-primary">{{ $index + 1 }}</span></td>
                    <td scope="row" class="border-b border-defaultborder !text-center !p-1">{{$item->name}}</td>
                    <td scope="row" class="border-b border-defaultborder !text-center !p-1">{{$item->country?$item->country->country_name:""}}</td>
                    <td scope="row" class="border-b border-defaultborder !text-center !p-1">
                        <livewire:master-status-toggle 
                            modelName="State" 
                            :item="$item" 
                            wire:key="status-toggle-{{ $item->id }}" 
                        />
                    </td>
                    <td scope="row" class="border-b border-defaultborder !text-center !p-1" width="10%">
                        <div>
                            <x-action-button type="edit" url="{{ route('admin.state.index',['update_id'=>$item->id]) }}" itemId="{{ $item->id }}" />
                            <x-action-button type="delete" url="{{ route('admin.state.destroy', $item->id) }}" itemId="{{ $item->id }}" />
                        </div>
                    </td>
                </tr>
            @elseif($dataType=='cab')
                <tr class="text-grey" id="delete{{$item->id}}">
                    <td scope="row" class="border-b border-defaultborder !text-center " width="4%">
                        <span class="badge bg-primary/10 text-primary">{{ $index + 1 }}</span></td>
                    <td scope="row" class="!p-1" width="20%">
                        <img src="{{$item->image?asset($item->image):asset('assets/img/cab.png')}}" alt="Cab Image" width="90%">
                    </td>
                    <td scope="row" class="border-b border-defaultborder !text-center !p-1">{{$item->title}}</td>
                    <td scope="row" class="border-b border-defaultborder !text-center !p-1">{{$item->capacity?$item->capacity.' Seat':""}}</td>
                    <td scope="row" class="border-b border-defaultborder !text-center !p-1">
                        <livewire:master-status-toggle 
                            modelName="Cab" 
                            :item="$item" 
                            wire:key="status-toggle-{{ $item->id }}" 
                        />
                    </td>
                    <td scope="row" class="border-b border-defaultborder !text-center !p-1" width="10%">
                        <div>
                            <x-action-button type="edit" url="{{ route('admin.cab.index',['update_id'=>$item->id]) }}" itemId="{{ $item->id }}" />
                            <x-action-button type="delete" url="{{ route('admin.cab.destroy', $item->id) }}" itemId="{{ $item->id }}" />
                        </div>
                    </td>
                </tr>
            @elseif($dataType=='division')
                <tr class="text-grey" id="delete{{$item->id}}">
                    <td scope="row" class="border-b border-defaultborder !text-center !p-1"><span class="badge bg-primary/10 text-primary">{{ $index + 1 }}</span></td>
                    <td scope="row" class="border-b border-defaultborder !text-center !p-1">{{$item->name}} <span class="text-danger">({{$item->code}})</span></td>
                    <td scope="row" class="border-b border-defaultborder !text-center !p-1">{{$item->DestinationData?$item->DestinationData->name:""}}</td>
                    <td scope="row" class="border-b border-defaultborder !text-center !p-1">
                        <livewire:master-status-toggle 
                            modelName="City" 
                            :item="$item" 
                            wire:key="status-toggle-{{ $item->id }}" 
                        />
                    </td>
                    <td scope="row" class="border-b border-defaultborder !text-center !p-1" width="10%">
                        <div>
                            <x-action-button type="edit" url="{{ route('admin.division.index',['update_id'=>$item->id]) }}" itemId="{{ $item->id }}" />
                            <x-action-button type="delete" url="{{ route('admin.division.destroy', $item->id) }}" itemId="{{ $item->id }}" />
                        </div>
                    </td>
                </tr>
            @elseif($dataType=='categories')
                <tr class="text-grey" id="delete{{$item->id}}">
                    <td scope="row" class="border-b border-defaultborder !text-center !p-1"><span class="badge bg-primary/10 text-primary">{{ $index + 1 }}</span></td>
                    <td scope="row" class="border-b border-defaultborder !text-center !p-1">{{$item->name}}</td>
                    <td scope="row" class="border-b border-defaultborder !text-center !p-1">
                        <livewire:master-status-toggle 
                            modelName="Category" 
                            :item="$item" 
                            wire:key="status-toggle-{{ $item->id }}" 
                        />
                    </td>
                    <td scope="row" class="border-b border-defaultborder !text-center !p-1" width="10%">
                        <div>
                            <x-action-button type="edit" url="{{ route('admin.category.index',['update_id'=>$item->id]) }}" itemId="{{ $item->id }}" />
                            <x-action-button type="delete" url="{{ route('admin.category.destroy', $item->id) }}" itemId="{{ $item->id }}" />
                        </div>
                    </td>
                </tr>
            @elseif($dataType=='roomCategories')
                <tr class="text-grey" id="delete{{$item->id}}">
                    <td scope="row" class="border-b border-defaultborder !text-center !p-1"><span class="badge bg-primary/10 text-primary">{{ $index + 1 }}</span></td>
                    <td scope="row" class="border-b border-defaultborder !text-center !p-1">{{$item->name}}</td>
                    <td scope="row" class="border-b border-defaultborder !text-center !p-1">
                        <livewire:master-status-toggle 
                            modelName="RoomCategory" 
                            :item="$item" 
                            wire:key="status-toggle-{{ $item->id }}" 
                        />
                    </td>
                    <td scope="row" class="border-b border-defaultborder !text-center !p-1" width="10%">
                        <div>
                            <x-action-button type="edit" url="{{ route('admin.room.category.index',['update_id'=>$item->id]) }}" itemId="{{ $item->id }}" />
                            <x-action-button type="delete" url="{{ route('admin.room.category.destroy', $item->id) }}" itemId="{{ $item->id }}" />
                        </div>
                    </td>
                </tr>
            @elseif($dataType=='ammenities')
                <tr class="text-grey" id="delete{{$item->id}}">
                    <td scope="row" class="border-b border-defaultborder !text-center !p-1"><span class="badge bg-primary/10 text-primary">{{ $index + 1 }}</span></td>
                    <td scope="row" class="border-b border-defaultborder !text-center !p-1">{{$item->name}}</td>
                    <td scope="row" class="border-b border-defaultborder !text-center !p-1">
                        <livewire:master-status-toggle 
                            modelName="Ammenity" 
                            :item="$item" 
                            wire:key="status-toggle-{{ $item->id }}" 
                        />
                    </td>
                    <td scope="row" class="border-b border-defaultborder !text-center !p-1" width="10%">
                        <div>
                            <x-action-button type="edit" url="{{ route('admin.ammenity.index',['update_id'=>$item->id]) }}" itemId="{{ $item->id }}" />
                            <x-action-button type="delete" url="{{ route('admin.ammenity.destroy', $item->id) }}" itemId="{{ $item->id }}" />
                        </div>
                    </td>
                </tr>
            @elseif($dataType==='hotel')
                <tr class="text-grey" id="delete{{$item->id}}">
                    <td scope="row" class="border-b border-defaultborder !text-center align-top !p-1" width="5%"><span class="badge bg-primary/10 text-primary">{{ $index + 1 }}</span></td>
                    <td scope="row" class="border-b border-defaultborder !text-center align-top !p-1 w-[25%]">
                        <div class="mb-1 text-[0.875rem] font-semibold">
                            <a href="javascript:void(0);" class="badge bg-danger/10 text-danger custom_button_text">{{$item->name}}</a>
                        </div>
                        <div class="flex items-center">
                            <div class="me-4">
                                <span class="avatar avatar-xxl bg-light">
                                    <img src="{{$item->image?asset($item->image):asset('build/assets/images/logo/hotel.jpg')}}" alt="">
                                </span>
                            </div>
                            <div>
                                <div class="mb-1 flex items-center align-middle">
                                    <span class="me-1">Destination:</span><span class="font-semibold text-textmuted">{{$item->DestinationData?$item->DestinationData->name:""}}</span>
                                </div>
                                <div class="mb-1 flex items-center align-middle">
                                    <span class="me-1">City:</span><span class="font-semibold text-textmuted">{{$item->DivisionData?$item->DivisionData->name:""}}</span>
                                </div>
                                <div class="mb-1 flex items-center align-middle">
                                    <span class="me-1">Category:</span><span class="font-semibold text-textmuted">{{$item->CategoryData?$item->CategoryData->name:""}}</span>
                                </div>
                                <div class="mb-1 flex items-center align-middle">
                                    <i class="fa-solid fa-phone text-success mr-2"></i> <span class="font-semibold text-textmuted"> {{ $item->phone_code }}{{ $item->mobile_number }} </span>
                                </div>
                                <div class="mb-1 flex items-center align-middle">
                                    <i class="fa-brands fa-whatsapp text-success mr-2"></i>  <span class="font-semibold text-textmuted">{{ $item->phone_code }}{{ $item->whatsapp_number }}</span>
                                </div>
                                <div class="mb-1 flex items-center align-middle">
                                    <i class="fa-regular fa-envelope text-danger mr-2"></i>  <span class="font-semibold text-textmuted">{{ $item->email1 }}</span>
                                </div>
                                <div class="mb-1 flex items-center align-middle">
                                    <i class="fa-regular fa-envelope text-danger mr-2"></i>  <span class="font-semibold text-textmuted">{{ $item->email2 }}</span>
                                </div>
                                <div class="mb-1 flex items-center align-middle">
                                    <span class="me-1">No of Rooms:</span><span class="font-semibold text-textmuted">{{$item->number_of_rooms}}</span>
                                </div>
                            </div>
                        </div>
                        <button 
                        class="badge bg-outline-warning !py-1 !px-2 custom_button_text" 
                            data-title="" 
                            id="" 
                            onclick="showImages(this)" 
                            data-images='@json($item->images->pluck("image_path"))'
                            >
                            View Gallery
                        </button>
                        
                    </td>
                    <td scope="row" class="border-b border-defaultborder !text-center align-top !p-1">
                        {{-- <table class="table min-w-full hotel_room_table">
                            <thead>
                                <tr class="alert alert-primary">
                                    <th>Room Name</th>
                                    <th>Total of Room</th>
                                    <th>No of Beds</th>
                                    <th>Extra Bed Capacity</th>
                                    <th>Extra Mattress Capacity</th>
                                    <th>Room Capacity</th>
                                    <th>Ammenities</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="7"> --}}
                                    <div class="hotel_room_details alert alert-primary">
                                        <div class="room_item_details">Room Name</div>
                                        <div class="room_item_details">No. of Rooms</div>
                                        <div class="room_item_details">No. of Beds</div>
                                        <div class="room_item_details">Extra Bed</div>
                                        <div class="room_item_details">Mattress</div>
                                        <div class="room_item_details">Capacity</div>
                                        <div class="room_item_details">Amenities</div>
                                    </div>
                                    <livewire:hotel.room-sorter :rooms="$item->rooms->sortBy('positions')"/>

                                    {{-- </td>
                                </tr>
                            </tbody>
                        </table> --}}
                    </td>
                    <td scope="row" class="border-b border-defaultborder !text-center align-top !p-1">
                        <livewire:master-status-toggle 
                            modelName="Hotel" 
                            :item="$item" 
                            wire:key="status-toggle-{{ $item->id }}" 
                        />
                    </td>
                    <td scope="row" class="border-b border-defaultborder !text-center align-top !p-1" width="10%">
                        <div>
                            <x-action-button type="edit" url="{{ route('admin.hotel.edit',$item->id) }}" itemId="{{ $item->id }}" />
                            <x-action-button type="delete" url="{{ route('admin.hotel.destroy', $item->id) }}" itemId="{{ $item->id }}" />
                        </div>
                    </td>
                </tr>
            @else
            @endif
        @endforeach
    </tbody>
</table>
