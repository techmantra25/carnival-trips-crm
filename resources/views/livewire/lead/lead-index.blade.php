<div>
    <style>
        .custom_lable{
            font-size: 10px;
            margin-bottom: -16px;
            color: #0162e8;
        }
        .custom_field{
            height: 32px;
            line-height: 1;
            font-size: 11px;
        }
        .custom_field::placeholder {
            height: 32px;
            line-height: 1;
            font-size: 11px;
        }
    </style>
    @php
        use Illuminate\Support\Str;
    @endphp
    <div class="md:flex block items-center justify-between mb-2 mt-1  page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{$common['parentHeader']}}</h5>
            <nav>
                <ol class="flex items-center whitespace-nowrap min-w-0">
                    <li class="text-[12px]"> <a class="flex items-center text-primary hover:text-primary"
                            href="javascript:void(0);"> {{$common['parentHeader']}} <i
                                class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                        </a> </li>
                    <li class="text-[12px]"> <a class="flex items-center text-textmuted" href="javascript:void(0);">{{$common['childHeader']}}
                        </a> </li>
                </ol>
            </nav>
        </div>

        <div class="ti-btn-list flex">
            {{-- Emergency Alert Banner --}}
            <div
                x-data="{
                    counter: 30,
                    init() {
                        setInterval(() => {
                            if (this.counter > 1) {
                                this.counter--;
                            } else {
                                this.counter = 30;
                                $wire.$refresh(); // Auto refresh Livewire
                            }
                        }, 1000);
                    }
                }"
                role="alert"
            >
                <div
                    class="bg-white text-red-600 font-semibold px-3 py-1 text-xs rounded-full flex items-center space-x-2 shadow"
                >
                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v6h6M20 20v-6h-6M4 20l16-16" />
                    </svg>
                    <span x-text="counter + 's'"></span>
                </div>
            </div>


            <button type="button" wire:click="openImportModal" class="ti-btn ti-btn-orange ti-btn-wave !py-1 pt-0 me-[0.375rem] flex items-center gap-1">
            <i class="ti ti-upload"></i> Import Lead
            </button>
            <a href="javascript:void(0)" wire:click="NewPresetItinerary('yes')" class="ti-btn ti-btn-primary-full !py-1 pt-0 ti-btn-wave  me-[0.375rem]"><i class="fa-solid fa-plus"></i>Add Lead</a>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-6">
        <div class="xl:col-span-12 col-span-12">
            <div class="box custom-box !mb-0">
                <div class="p-0 mx-2 my-2 space-x-1">
                    <span 
                        class="badge cursor-pointer {{ empty($search_destination) ? 'bg-primary text-white' : 'bg-outline-primary' }}"
                        wire:click="clearAllDestinations"
                    >
                        All
                    </span>

                    @foreach ($destinations as $destination_item)
                        <span 
                            class="badge cursor-pointer {{ in_array($destination_item->id, $search_destination ?? []) ? 'bg-primary text-white' : 'bg-outline-primary' }}" 
                            wire:click="toggleDestination({{ $destination_item->id }})"
                        >
                            {{ $destination_item->name }}
                        </span>
                    @endforeach
                </div>
                <div class="p-0 mx-2 my-2 space-x-1">
                    <span 
                        class="badge cursor-pointer {{ empty($filter_lead_status) ? 'bg-success text-white' : 'bg-outline-success' }}"
                        wire:click="clearAllLeadStatus"
                    >
                        All
                    </span>

                    @foreach ($leads_status as $status)
                        <span 
                            class="badge cursor-pointer {{ in_array($status->name, $filter_lead_status ?? []) ? 'bg-success text-white' : 'bg-outline-success' }}" 
                            wire:click="changeLeadStatus('{{ $status->name }}')"
                        >
                            {{ $status->name }}
                        </span>
                    @endforeach
                </div>
                <div class="p-0">
                    <table class="table whitespace-nowrap min-w-full">
                        <tbody>
                            <tr>
                                {{-- <td class="py-0 mt-0">
                                    <select class="ti-form-select rounded-sm !py-2 !px-3 !p-0 custom_field refresh_component" id="select-beast"
                                        autocomplete="off" name="filter_package" wire:model="filter_package" wire:change="changePackage($event.target.value)">
                                        <option value="">Select a package...</option>
                                        <option value="Holiday Packages">Holiday Packages</option>
                                        <option value="Hotels">Hotels</option>
                                        <option value="Logistics">Logistics</option>
                                        
                                    </select>
                                </td> --}}
                                <td class="py-0 mt-0">
                                    {{-- <label for="select-1"
                                        class="ti-form-select rounded-sm !py-2 !px-3-label text-sm text-textmuted custom_lable">LEAD
                                        TYPE</label> --}}
                                    <select class="ti-form-select rounded-sm !py-2 !px-3 !p-0 custom_field refresh_component" id="select-beast"
                                        autocomplete="off" name="filter_source_type" wire:model="filter_source_type" wire:change="changeLeadType($event.target.value)">
                                        <option value="">Select source type...</option>
                                        <option value="Facebook">Facebook</option>
                                        <option value="Instagram">Instagram</option>
                                        <option value="Website">Website</option>
                                    </select>
                                </td>
                                <td>
                                     <input
                                    class="form-control placeholder:!text-headerprimecolor placeholder:opacity-70 placeholder:font-thin placeholder:text-sm custom_field refresh_component"
                                    placeholder="Search for anything..." 
                                    type="search" 
                                    name="filter" 
                                    wire:model.lazy="filter" wire:keyup="SetFilter($event.target.value)">
                                </td>
                                <td width="13%">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="date" class="form-control !border-s-0 custom_field refresh_component"
                                                placeholder="START DATE" name="start_date" wire:model="start_date" wire:change="ChangeDate($event.target.value,'start_date')">
                                        </div>
                                    </div>
                                </td>
                                <td width="13%">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="date" class="form-control !border-s-0 custom_field refresh_component"
                                                placeholder="END DATE" name="end_date" wire:model="end_date" wire:change="ChangeDate($event.target.value,'end_date')">
                                        </div>
                                    </div>
                                </td>
                                <td width="4%">
                                    <a href="javascript:void(0)"
                                        class="ti-btn ti-btn-danger-full text-white  ti-btn-icon me-2 !mb-0" wire:click="ResetAllField">
                                        <i class="mdi mdi-refresh"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box custom-box">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table whitespace-nowrap table-bordered table-bordered-primary border-primary/10 min-w-full">
                            <thead class="uppercase">
                                <tr class="border-b !border-primary/30">
                                    <th scope="col" class="!text-center">SL No.</th>
                                    <th scope="col" class="!text-center">LEAD NAME</th>
                                    <th scope="col" class="!text-center">Customer Details</th>
                                    <th scope="col" class="!text-center">PACKAGE DETAILS</th>
                                    <th scope="col" class="!text-center">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($leads as $index=>$lead_item)
                                    <tr class="text-grey" id="delete{{$lead_item->id}}">
                                        <td scope="row" class="!text-center !p-1">
                                            <span class="badge bg-primary/10 text-primary"> {{ $leads->firstItem() + $index }}</span>
                                        </td>
                                        <td>
                                                <p class="badge bg-primary text-white">{{$lead_item->unique_id }}</p>
                                                <p class="mt-1"> {{ $lead_item->customer_name }}
                                                <span class="badge gap-2 bg-success/10 text-success">
                                                    <span class="w-1.5 h-1.5 inline-block bg-success rounded-full"></span>Online
                                                </span>
                                                </p>
                                            
                                            @php
                                                $timeInfo = \App\Helpers\CustomHelper::formatLeadTime($lead_item->created_at);
                                            @endphp
                                            <button type="button" class="badge bg-outline-secondary my-3 me-2 badge-custom-outline-secondary">
                                                {{ $timeInfo['formatted_date'] }}
                                                <span class="badge bg-secondary ms-2 text-white">{{ $timeInfo['time_ago'] }}</span>
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" class="badge bg-outline-secondary badge-custom-outline-secondary">
                                               {{$lead_item->package_type??"N/A"}}
                                                <span class="badge bg-danger ms-2 text-white">New</span>
                                            </button>
                                            @if($lead_item->customer_email)
                                                <div>
                                                    <i class="fa-regular fa-envelope text-danger"></i>
                                                    {{ $lead_item->customer_email }}
                                                </div>
                                            @endif
                                            @if($lead_item->customer_mobile)
                                                <div>
                                                    <i class="fa-solid fa-phone text-success"></i>
                                                    +{{ $lead_item->country_code }}{{ $lead_item->customer_mobile }}
                                                </div>
                                            @endif
                                            @if($lead_item->customer_whatsapp)
                                                <div>
                                                    <i class="fa-brands fa-whatsapp text-success"></i>
                                                    +{{ $lead_item->country_code }}{{ $lead_item->customer_whatsapp }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <span 
                                                    class="badge text-white 
                                                        @if ($lead_item->source_type === 'Facebook') bg-primary
                                                        @elseif ($lead_item->source_type === 'Instagram') bg-danger
                                                        @elseif ($lead_item->source_type === 'Website') bg-black
                                                        @else bg-secondary
                                                        @endif
                                                    "
                                                    title="@switch($lead_item->source_type)
                                                        @case('Facebook') Lead Source: Facebook Campaign @break
                                                        @case('Instagram') Lead Source: Instagram Ads @break
                                                        @case('Website') Lead Source: Website Form @break
                                                        @default Lead Source: Other
                                                    @endswitch"
                                                >
                                                    {{ $lead_item->source_type }}
                                                </span>
                                            </div>
                                            <div>
                                                <i class="ri-map-pin-line"></i>
                                                {{ $lead_item->destination?$lead_item->destination->name:"..." }} 
                                                <span class="badge bg-primary/10 text-primary">{{$lead_item->travel_duration_text}} @if($lead_item->number_of_travellor)| {{$lead_item->number_of_travellor}} PAX @endif</span>
                                            </div>
                                            <div>
                                                <i class="fa-regular fa-clock text-danger"></i>
                                                Start Date: {{ \Carbon\Carbon::parse($lead_item->arrival_date)->format('d M Y') }}
                                            </div>
                                            <div>
                                                <i class="fa-regular fa-clock text-danger"></i>
                                                End Date: {{ \Carbon\Carbon::parse($lead_item->departure_date)->format('d M Y') }}
                                            </div>
                                            <div>
                                                <span class="lead_itinerary_journey">
                                                    {{ optional($lead_item->itinerary)->itinerary_journey ?? '...' }}
                                                    ({{ optional($lead_item->category)->name ?? '...' }})
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="!text-center mb-2">
                                                @foreach($lead_item->sent_itinerary()->orderBy('id', 'ASC')->get() as $sent_itinerary_item)
                                                    @php
                                                        $title = "Itinerary: {$sent_itinerary_item->itinerary_syntax}\n";
                                                        $title .= "Send Via: {$sent_itinerary_item->send_via}\n";
                                                        $title .= "Total Cost: ₹" . number_format($sent_itinerary_item->total_cost) . "\n";
                                                        $title .= "Destination: " . optional($sent_itinerary_item->destination)->name . "\n";
                                                        $title .= "Hotel Category: " . optional($sent_itinerary_item->category)->name;
                                                    @endphp

                                                    <span 
                                                        class="badge bg-outline-secondary {{$sent_itinerary_item->is_confirmed==0?"badge-custom-outline-secondary":"badge-custom-outline-secondary-selected"}} cursor-pointer" 
                                                        title="{{ $title }}"
                                                    >
                                                        {{ $sent_itinerary_item->itinerary_code }}
                                                    </span>
                                                @endforeach
                                            </div>

                                            <div class="!text-center">
                                                <a href="javascript:void(0)" wire:click="LeadStatusModal({{$lead_item->id}})"   title="Click to update lead status">
                                                    <span class="badge gap-2 {{ \App\Helpers\CustomHelper::getLeadStatusBadgeColor($lead_item->status) }}">
                                                        <span class="w-1.5 h-1.5 inline-block bg-black rounded-full"></span>
                                                        {{ ucwords($lead_item->status) }}
                                                    </span>
                                                </a>     
                                            </div>  
                                            <div class="!text-center">

                                                {{-- View Lead Log / History --}}
                                                <a href="{{route('admin.leads.log.history', $lead_item->id)}}"
                                                class="ti-btn ti-btn-info mt-[0.375rem] ml-2" title="View Lead Log / History">
                                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                                </a>
                                                {{-- View Shared Itinerary History --}}
                                                <a href="{{ route('admin.leads.shared.itinerary.history', $lead_item->id) }}"
                                                class="ti-btn ti-btn-primary mt-[0.375rem] ml-2"
                                                title="View Shared Itinerary History">
                                                    <i class="fa-solid fa-share-nodes mr-1"></i> 
                                                </a>
                                                
                                                {{-- View Trip Preference Form --}}
                                                <a href="{{ route('admin.leads.trip.preference.data', $lead_item->id) }}"
                                                class="ti-btn ti-btn-warning mt-[0.375rem] ml-2"
                                                title="View Trip Preference Form">
                                                    <i class="fa-solid fa-clipboard-question mr-1"></i> 
                                                </a>
                                                {{-- Assign Lead to Team Member --}}
                                                @if($authUser->role !== 'member')
                                                    <button type="button"
                                                        class="ti-btn ti-btn-orange mt-[0.375rem]"
                                                        title="Assign Lead to Team Member" wire:click="assignLeadModal({{$lead_item->id}})">
                                                        <i class="fa-solid fa-user-check"></i>
                                                    </button>
                                                @endif
                                                
                                                {{-- Edit Lead --}}
                                                @if($lead_item->itinerary)
                                                    @php
                                                        $encryptedId = Crypt::encrypt($lead_item->itinerary->id);
                                                    @endphp

                                                    @if($lead_item->status!=="Confirmed")
                                                    <a href="{{route('admin.cost_calculator.query_edit',$encryptedId)}}" class="ti-btn ti-btn-orange  mt-[0.375rem]" title="Edit Lead"><i class="fa-regular fa-pen-to-square"></i></a>
                                                    @endif

                                                    {{-- Itinerary --}}
                                                    @if($lead_item->itinerary->night_journey &&                 $lead_item->itinerary->stay_by_journey)
                                                        <a href="{{route('admin.itinerary.query.build', $encryptedId)}}" class="ti-btn ti-btn-teal mt-[0.375rem]" title="Itinerary"><i class="fa-solid fa-arrows-up-down-left-right"></i></a> <br>
                                                    @endif
                                                     <br>
                                                        @php
                                                            $destination_slug = $lead_item->destination
                                                            ? Str::slug($lead_item->destination->name)
                                                            : 'destination';
                                                            $lead_itinerary_journey = $lead_item->travel_in_days.'day-'.$lead_item->travel_in_nights.'nights';
                                                            $LeadUrlShare = App\Models\LeadUrlShare::where('lead_id', $lead_item->id)->where('itinerary_id', $lead_item->itinerary->id)->first();
                                                            $shared_link = App\Helpers\CustomHelper::secure_encode_id($LeadUrlShare?$LeadUrlShare->id:null);
                                                        @endphp
                                                    @if($shared_link)
                                                    <a href="{{route('website.lead.destination.preset-itinerary',[$destination_slug,$lead_itinerary_journey,$shared_link])}}" target="_blank" class="ti-btn ti-btn-secondary ti-btn-border-start  mt-[0.375rem]" title="Itinerary">WEBSITE</a>
                                                    @endif
                                                @endif
                                               
                                                 <a href="{{route('website.trip.preference.form', $lead_item->unique_id)}}" target="_blank" class="ti-btn ti-btn-secondary ti-btn-border-start  mt-[0.375rem]" title="Itinerary">FB Form</a>
                                            </div>
                                           <div>
                                                <span class="badge bg-warning/10 text-warning" title="Assign Lead Member">
                                                    <span class="text-black"><i class="fa-regular fa-user me-1"></i></span>
                                                    <span class="text-black"> Assigned To:</span>
                                                    <strong class="ms-1 text-black">
                                                        @if($lead_item->assignee)
                                                            {{ $lead_item->assignee->name }} ({{ $lead_item->assignee->email }})
                                                        @else
                                                            N/A
                                                        @endif
                                                    </strong>
                                                </span>
                                            </div>

                                            @php
                                                $confirmed_itinerary = $lead_item->sent_itinerary()->where('is_confirmed', 1)->first();
                                            @endphp
                                            @if($confirmed_itinerary)
                                                <div>
                                                    <span class="badge bg-success/10 text-warning" title="Assign Lead Member">
                                                        <span class="text-black">
                                                            <i class="fa-regular fa-user me-1"></i>
                                                        </span>
                                                        <span class="text-black"> Confirmed By:</span>
                                                        <strong class="ms-1 text-black">
                                                            @if($confirmed_itinerary && $confirmed_itinerary->confirmedBy)
                                                                {{ $confirmed_itinerary->confirmedBy->name }} ({{ $confirmed_itinerary->confirmedBy->email }})
                                                            @else
                                                                N/A
                                                            @endif
                                                        </strong>
                                                    </span>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="alert alert-danger">
                                                Result not found!
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>   
                        <div class="d-flex justify-content-end mt-3 paginator">
                            {{ $leads->links() }} <!-- Pagination links -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

     {{-- Model --}}
        <div class="hs-overlay {{$active_assign_new_modal==0?"hidden":""}} fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div
                class="hs-overlay-open:mt-7 ti-modal-box mt-0 ease-out lg:!max-w-4xl lg:w-full m-3 lg:!mx-auto query_modal_lg_width bg-white rounded-lg">
                <div class="ti-modal-content p-20">
                    <div class="ti-modal-header flex justify-between items-center">
                        <h5 class="page-title text-[1.3125rem] font-medium t7ext-defaulttextcolor mb-0 uppercase">Add New
                            Lead</h5>
                        <button type="button"
                            class="text-gray-400 hover:text-gray-600 focus:outline-none badge gap-2 bg-danger/10 text-danger"
                            wire:click="NewPresetItinerary('no')">
                            <i class="fa-solid fa-xmark text-lg text-dark"></i>
                        </button>
                    </div>
                    <div class="ti-modal-body text-start query_modal_body">
                        <form wire:submit.prevent="submitForm">
                            <div class="flex flex-wrap gap-2">
                                @foreach ($destinations as $item)
                                <label class="cursor-pointer">
                                    <input type="radio" name="destination" value="{{ $item->id }}"
                                        wire:model="destination" wire:change="changeDestination($event.target.value)"
                                        class="sr-only" />

                                    <div class="px-4 py-2 rounded-md border transition-all modal_destination
                                                    {{ $destination == $item->id 
                                                        ? 'bg-primary text-white border-primary' 
                                                      : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
                                        {{ $item->name }}
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            <div>
                                @error('destination') <span class="text-danger text-sm font-12">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="container mx-auto px-4 py-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <label for="customer_name"
                                            class="block text-sm font-medium text-gray-700 modal_query_lable">Guest
                                            name<span class="text-danger">*</span>
                                        </label>
                                        <input type="text" wire:model="customer_name" name="customer_name" value=""
                                            placeholder="Full name of guest" aria-label="Full name of guest"
                                            class="form-control form-control-lg placeholder:text-textmuted text-sm font-12 {{ $errors->has('customer_name') ? '!border-danger focus:border-danger focus:ring-danger' : '' }}">
                                        @error('customer_name') <span
                                            class="text-danger text-sm font-12">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="flex">
                                        <div class="px-1">
                                            <label
                                                class="block text-sm font-medium text-gray-700 modal_query_lable">Total
                                                Member<span class="text-danger">*</span>
                                            </label>
                                            <input type="number" wire:model="total_members" name="total_members"
                                                value="" placeholder="Member"
                                                class="form-control form-control-lg placeholder:text-textmuted text-sm font-12 {{ $errors->has('total_members') ? '!border-danger focus:border-danger focus:ring-danger' : '' }}">
                                            @error('total_members') <span
                                                class="text-danger text-sm font-12">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="px-1">
                                            <label
                                                class="block text-sm font-medium text-gray-700 modal_query_lable">Number
                                                of Adults<span class="text-danger">*</span>
                                            </label>
                                            <input type="number" wire:model="number_of_adults" name="number_of_adults"
                                                value="" placeholder="Adults"
                                                class="form-control form-control-lg placeholder:text-textmuted text-sm font-12 {{ $errors->has('number_of_adults') ? '!border-danger focus:border-danger focus:ring-danger' : '' }}">
                                            @error('number_of_adults') <span
                                                class="text-danger text-sm font-12">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="px-1">
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" wire:model="enableChildren" wire:change="toggleChildren" />
                                                <span
                                                    class="block text-sm font-medium text-gray-700 modal_query_lable">Add
                                                    Child</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div>
                                        @if($enableChildren)
                                        <div class="grid grid-cols-12">
                                            @foreach ($childs as $index => $child)
                                            <div class="sm:col-span-12 col-span-12">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <div class="w-[45%]">
                                                        @if($index==0)
                                                        <label for=""
                                                            class="block text-sm font-medium text-gray-700 modal_query_lable">Quantity</label>
                                                        @endif
                                                        <select wire:model="childs.{{ $index }}.quantity"
                                                            class="form-control form-control-sm">
                                                            <option value="" hidden>Quantity</option>
                                                            @for($i = 1; $i <= 10; $i++) <option value="{{ $i }}">
                                                                {{ $i }}</option>
                                                                @endfor
                                                        </select>
                                                        @error("childs.$index.quantity") <span
                                                            class="text-danger text-sm font-12">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="w-[45%]">
                                                        @if($index==0)
                                                        <label for=""
                                                            class="block text-sm font-medium text-gray-700 modal_query_lable">Child
                                                            Age</label>
                                                        @endif
                                                        <select wire:model="childs.{{ $index }}.age"
                                                            class="form-control form-control-sm">
                                                            <option value="" hidden>Child Age</option>
                                                            @foreach ($childsData as $child_item)
                                                            <option value="{{ $child_item }}">{{ $child_item }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error("childs.$index.age") <span
                                                            class="text-danger text-sm font-12">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div>
                                                        <button type="button"
                                                            wire:click="removeExtraChild({{ $index }})"
                                                            class="ti-btn ti-btn-sm ti-btn-soft-danger !border !border-danger/20">
                                                            <i class="ti ti-trash text-lg"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="text-end mb-2">
                                            <button type="button" wire:click="addExtraChild"
                                                class="badge bg-outline-success cursor-pointer !font-normal !text-sm uppercase">
                                                <i class="fa-solid fa-plus text-dark mr-1"></i> Add Child
                                            </button>
                                        </div>
                                        @endif
                                    </div>

                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <label for="mobile_number"
                                            class="block text-sm font-medium text-gray-700 modal_query_lable">Mobile
                                            Number
                                        </label>
                                        <input type="number" wire:model="mobile_number" name="mobile_number" value=""
                                            placeholder="Mobile number"
                                            class="form-control form-control-lg placeholder:text-textmuted text-sm font-12 {{ $errors->has('mobile_number') ? '!border-danger focus:border-danger focus:ring-danger' : '' }}">
                                        @error('mobile_number') <span
                                            class="text-danger text-sm font-12">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 modal_query_lable">Email
                                            Address
                                        </label>
                                        <input type="email" wire:model="email_address" name="email_address" value=""
                                            placeholder="Email address"
                                            class="form-control form-control-lg font-12 placeholder:text-textmuted text-sm {{ $errors->has('email_address') ? '!border-danger focus:border-danger focus:ring-danger' : '' }}">
                                        @error('email_address') <span
                                            class="text-danger text-sm font-12">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        {{-- <label for="company_name"
                                            class="block text-sm font-medium text-gray-700 modal_query_lable">Assign To
                                            Company<span class="text-danger">*</span>
                                        </label>
                                        <select name="company_name" wire:model="company_name"
                                            class="form-control font-12 {{ $errors->has('company_name') ? '!border-danger focus:border-danger focus:ring-danger' : '' }}">
                                            <option value="" selected hidden>Select B2B company</option>
                                            @foreach ($companies as $company_item)
                                            <option value="{{$company_item['name']}}"
                                                {{ old('company_name') == $company_item['name'] ? 'selected' : '' }}>
                                                {{$company_item['name']}}</option>
                                            @endforeach
                                        </select>

                                        @error('company_name') <span
                                            class="text-danger text-sm font-12">{{ $message }}</span> @enderror --}}
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <label for="whatsapp_number"
                                            class="block text-sm font-medium text-gray-700 modal_query_lable">Whatsapp
                                            Number
                                        </label>
                                        <input type="number" wire:model="whatsapp_number" name="whatsapp_number"
                                            value="" placeholder="Whatsapp number"
                                            class="form-control form-control-lg placeholder:text-textmuted text-sm font-12 {{ $errors->has('whatsapp_number') ? '!border-danger focus:border-danger focus:ring-danger' : '' }}">
                                        @error('whatsapp_number') <span
                                            class="text-danger text-sm font-12">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="flex">
                                        <div class="px-1 w-full">
                                            <label for="hotel_category"
                                                class="block text-sm font-medium text-gray-700 modal_query_lable">Hotel Category<span class="text-danger">*</span>
                                            </label>
                                            <select name="hotel_category" wire:model="hotel_category"
                                                wire:change="changeHotelCategory($event.target.value)"
                                                class="form-control font-12 {{ $errors->has('hotel_category') ? '!border-danger focus:border-danger focus:ring-danger' : '' }}">
                                                <option value="">Select category</option>
                                                @foreach ($categories as $hotel_category_item)
                                                <option value="{{ $hotel_category_item->id }}" {{$selectedCategory==$hotel_category_item->id?"selected":""}}>
                                                    {{ $hotel_category_item->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('hotel_category') <span class="text-danger text-sm font-12">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="px-1 w-full">
                                            <label for="package_type"
                                                class="block text-sm font-medium text-gray-700 modal_query_lable">Package
                                                Type<span class="text-danger">*</span>
                                            </label>
                                            <select name="package_type" wire:model="package_type"
                                                wire:change="changePackageType($event.target.value, $event.target.selectedOptions[0].dataset.day, $event.target.selectedOptions[0].dataset.night)"
                                                class="form-control font-12 {{ $errors->has('package_type') ? '!border-danger focus:border-danger focus:ring-danger' : '' }}">
                                                <option value="">Select package name</option>
                                                @foreach ($packageTypes as $type)
                                                <option value="{{ $type['name'] }}" data-day="{{ $type['day'] }}"
                                                    data-night="{{ $type['night'] }}">
                                                    {{ $type['name'] }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('package_type') <span class="text-danger text-sm font-12">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="flex">
                                        <div class="px-1 w-full">
                                            <label
                                                class="block text-sm font-medium text-gray-700 modal_query_lable">Arrival
                                                Date<span class="text-danger">*</span>
                                            </label>
                                            <input type="date" wire:model="arrival_date" name="arrival_date" id="arrival_date"
                                                class="form-control form-control-lg placeholder:text-textmuted text-sm font-12 {{ $errors->has('arrival_date') ? '!border-danger focus:border-danger focus:ring-danger' : '' }}"
                                                min="{{ date('Y-m-d') }}" value="{{$arrival_date}}" wire:change="changeArrivalDate($event.target.value)">
                                            @error('arrival_date') <span
                                                class="text-danger text-sm font-12">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="px-1 w-full">
                                            <label
                                                class="block text-sm font-medium text-gray-700 modal_query_lable">Departure
                                                Date<span class="text-danger">*</span>
                                            </label>
                                            <input type="date" wire:model="departure_date" name="departure_date" id="departure_date"
                                                class="form-control form-control-lg placeholder:text-textmuted text-sm font-12 {{ $errors->has('departure_date') ? '!border-danger focus:border-danger focus:ring-danger' : '' }}"
                                                min="{{date('Y-m-d')}}" value="{{$departure_date}}" readonly>
                                            @error('departure_date') <span
                                                class="text-danger text-sm font-12">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                    <div>
                                        <label for="source_type"
                                            class="block text-sm font-medium text-gray-700 modal_query_lable">Source Type<span class="text-danger">*</span>
                                        </label>
                                        <select name="source_type" wire:model="source_type"
                                            class="form-control font-12 {{ $errors->has('source_type') ? '!border-danger focus:border-danger focus:ring-danger' : '' }}">
                                            <option value="" selected hidden>Select query source</option>
                                            @foreach ($queryTypes as $item_query)
                                            <option value="{{$item_query['name']}}"
                                                {{ old('source_type') == $item_query['name'] ? 'selected' : '' }}>
                                                {{$item_query['name']}}</option>
                                            @endforeach
                                        </select>

                                        @error('source_type') <span
                                            class="text-danger text-sm font-12">{{ $message }}</span> @enderror
                                    </div>
                                    {{-- wire:click.away="$set('night_halt_status', 0)" --}}
                                    <div class="relative w-full">
                                        <div class="flex justify-between">
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 modal_query_lable">Night
                                                    Halt Details<span class="text-danger">*</span></label>
                                            </div>
                                            <div>
                                                <a href="javascript:void(0)"
                                                    class="badge gap-2 bg-danger/10 text-dark uppercase text-sm"
                                                    wire:click="NewNightHalt('yes')"><i
                                                        class="fa-solid fa-plus text-xs text-dark"></i>Add New</a>
                                            </div>
                                        </div>
                                        {{-- Search Input --}}
                                        <input type="text"
                                            class="form-control form-control-lg font-12 placeholder:text-textmuted text-sm {{ $errors->has('night_halt_details') ? '!border-danger focus:border-danger focus:ring-danger' : '' }} refresh_component"
                                            placeholder="Search night halt..." wire:model="night_halt_details"
                                            value="{{$night_halt_details}}" id="night_halt_details"
                                            wire:keyup="filterNightHalt($event.target.value)" autocomplete="off">
                                        @error('night_halt_details') <span
                                            class="text-danger text-sm font-12">{{ $message }}</span> @enderror

                                        {{-- Tooltip-style Dropdown --}}
                                        @if($night_halt_status == 1)
                                        <div class="border rounded bg-white shadow-lg z-50 absolute w-full transition ease-out duration-150 transform scale-100 opacity-100"
                                            style="max-height: 300px; overflow-y: auto; top: 100%; left: 0; transform: translateY(4px);">

                                            @forelse ($existing_night_halt_details as $halt)
                                            <div wire:click="setNightHalt('{{ $halt['id'] }}','{{ $halt['itinerary_journey'] }}')"
                                                class="px-3 py-2 cursor-pointer hover:bg-gray-100 text-sm font-12"
                                                wire:key="night_halt_{{ $halt['id'] }}">
                                                {{ $halt['itinerary_journey'] }}
                                            </div>
                                            @empty
                                            <div class="px-3 py-2 text-sm text-gray-500">No active itinerary available between the selected dates.</div>
                                            @endforelse
                                        </div>
                                        @endif
                                    </div>
                                    <div>
                                        <label for=""
                                            class="block text-sm font-medium text-gray-700 modal_query_lable">Meal
                                            type<span class="text-danger">*</span></label>
                                        <div class="flex flex-wrap gap-2">
                                            @if(!empty($mealTypes->plan_item))
                                            @foreach (explode(', ',$mealTypes->plan_item) as $item)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="meal_plan" value="{{ $item }}"
                                                    wire:model="meal_type"
                                                    wire:change="changeMealPlan($event.target.value)" class="sr-only" />

                                                <div
                                                    class="px-4 py-2 rounded-md border transition-all modal_destination
                                                                    {{ $meal_type == $item 
                                                                        ? 'bg-primary text-white border-primary' 
                                                                    : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
                                                    {{ $item }}
                                                </div>
                                            </label>
                                            @endforeach
                                            @endif
                                            @error('meal_type') <span
                                                class="text-danger text-sm font-12">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div>
                                        <label for=""
                                            class="block text-sm font-medium text-gray-700 modal_query_lable">Nationality
                                            Type<span class="text-danger">*</span></label>
                                        <div class="flex flex-wrap gap-2">
                                            <label class="cursor-pointer">
                                                <input type="radio" name="nationality_type" value="Indian"
                                                    wire:model="nationality_type"
                                                    wire:change="changeNationalityType('Indian')" class="sr-only" />
                                                <div
                                                    class="px-4 py-2 rounded-md border transition-all modal_destination
                                                            {{ $nationality_type == "Indian" 
                                                                ? 'bg-primary text-white border-primary' 
                                                            : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
                                                    Indian
                                                </div>
                                            </label>
                                            <label class="cursor-pointer">
                                                <input type="radio" name="nationality_type" value="NRI"
                                                    wire:model="nationality_type"
                                                    wire:change="changeNationalityType('NRI')" class="sr-only" />
                                                <div
                                                    class="px-4 py-2 rounded-md border transition-all modal_destination
                                                            {{ $nationality_type == "NRI" 
                                                                ? 'bg-primary text-white border-primary' 
                                                            : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
                                                    Foreign National
                                                </div>
                                            </label>
                                            @error('nationality_type') <span
                                                class="text-danger text-sm font-12">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <div class="px-1 w-3xs mb-2">
                                        <label class="block text-sm font-medium text-gray-700 modal_query_lable">Number
                                            Of Rooms<span class="text-danger">*</span></label>
                                        <input type="number" wire:model="number_of_rooms" name="number_of_rooms"
                                            value="" placeholder="Rooms"
                                            class="form-control form-control-lg placeholder:text-textmuted text-sm font-12 ">
                                        @error("number_of_rooms") <span
                                            class="text-danger text-sm font-12">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="px-1 w-3xs mb-2">
                                        <label class="block text-sm font-medium text-gray-700 modal_query_lable">Extra Mattress</label>
                                        <input type="text" wire:model="extra_mattress" name="extra_mattress"
                                            value="" placeholder="extra mattress"
                                            class="form-control form-control-lg placeholder:text-textmuted text-sm font-12 ">
                                        @error("extra_mattress") <span
                                            class="text-danger text-sm font-12">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="ti-modal-footer">
                        <div class="text-end">
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
                            <button type="submit" wire:click.prevent="submitForm"
                                class="ti-btn ti-btn-primary-full !py-1 pt-0 ti-btn-wave  me-[0.375rem]">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{--New Night Halt Model --}}
        <div id="assign_cab"
            class="hs-overlay {{$active_new_night_halt_modal==0?"hidden":""}} fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div
                class="hs-overlay-open:mt-7 ti-modal-box mt-0 ease-out lg:!max-w-4xl lg:w-full m-3 lg:!mx-auto modal_md_width bg-white rounded-lg">
                <div class="ti-modal-content p-20">
                    <div class="ti-modal-header flex justify-end items-center">
                        <button type="button"
                            class="text-gray-400 hover:text-gray-600 focus:outline-none badge gap-2 bg-danger/10 text-danger"
                            wire:click="NewNightHalt('no')">
                            <i class="fa-solid fa-xmark text-lg text-dark"></i>
                        </button>
                    </div>
                    <div class="ti-modal-body text-start">
                        <div class="">
                            <div class="">
                                <label for="">
                                    <span class="badge gap-2 bg-danger/10 text-danger uppercase">
                                        Destination
                                    </span>
                                </label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($destinations as $item)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="destination" value="{{ $item->id }}"
                                            wire:model="destination"
                                            wire:change="changeDestination($event.target.value)" class="sr-only" />

                                        <div class="px-4 py-2 rounded-md border transition-all modal_destination
                                                    {{ $destination == $item->id 
                                                        ? 'bg-primary text-white border-primary' 
                                                      : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
                                            {{ $item->name }}
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="my-2 text-end">
                                <label for="">
                                    <span class="badge gap-2 bg-danger/10 text-danger uppercase">
                                        Category
                                    </span>
                                </label>
                                <select name="division_list"
                                    class="placeholder:text-textmuted text-sm selected_seasion_type"
                                    wire:change="GetCategory($event.target.value)" wire:key="category-0">
                                    <option value="" hidden>Filter category</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{$selectedCategory==$category->id?"selected":""}}
                                        wire:key="category-{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <form wire:submit.prevent="nightHaltSubmitForm">
                            <table
                                class="table whitespace-nowrap table-bordered table-bordered-primary border-primary/10 min-w-full new-activity">
                                <thead class="uppercase">
                                    <tr class="border-b !border-primary/30 bg-warning/10">
                                        <th scope="col" class="!text-center">
                                            Name Of Itinerary
                                        </th>
                                    </tr>
                                    <tr class="border-b !border-primary/30 bg-warning/10">
                                        <th scope="col" class="!text-center">
                                            <div class="custom-fulldiv">
                                                <div class="nd-field">
                                                    <div class="nd-group">
                                                        <input type="text" wire:key="night-halt-day"
                                                            wire:model="nightHalt.day"
                                                            class="form-control form-control-sm"
                                                            value="{{$nightHalt['day']}}" readonly>
                                                        <span>D</span>
                                                    </div>
                                                    <div class="spacer">/</div>
                                                    <div class="nd-group">
                                                        <input type="text" wire:model="nightHalt.night"
                                                            class="form-control form-control-sm"
                                                            value="{{$nightHalt['night']}}" readonly>
                                                        <span>N</span>
                                                    </div>
                                                </div>
                                                @if($active_night_distribution==1)
                                                    <div class="w-full">
                                                        <input type="text" wire:model="nightHalt.night_distribution"
                                                            wire:keyup="validateNightDistribution"
                                                            class="nd-input-placeholder form-control form-control-sm !text-center refresh_component"
                                                            placeholder="Itinerary Journey" value="{{$nightHalt['night_distribution']}}" readonly>
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
                                                    <input type="hidden" wire:model="itinerary_journey.{{ $index }}"
                                                        class="form-control form-control-sm" readonly>
                                                    <span>{{ App\Helpers\CustomHelper::ordinal($index + 1) }} Day</span>
                                                </div>
                                                <div>
                                                    <select wire:model="itinerary_journey_divisions.{{$index}}"
                                                        class="placeholder:text-textmuted text-sm selected_seasion_type"
                                                        wire:key="itinerary-journey-divisions-{{$index}}-0"
                                                        wire:change="updateJourneyDivision({{$index}},$event.target.value)">
                                                        <option value="" hidden>Select Divisions</option>
                                                        @foreach ($divisions as $division)
                                                        <option value="{{ $division->id }}"
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
                                    {{-- <tr>
                                        <td>
                                            <div class="flex items-center mb-2 flex-wrap gap-4">
                                                <!-- Valid From -->
                                                <div class="flex flex-col mx-1">
                                                    <label>
                                                        <span class="badge gap-2 bg-danger/10 text-danger uppercase">
                                                            Valid From
                                                        </span>
                                                    </label>
                                                    <div class="flex gap-2">
                                                        <!-- Day -->
                                                        <select name="valid_from_day" class="placeholder:text-textmuted text-sm selected_seasion_type" wire:model="valid_from_day">
                                                            <option value="" hidden selected>Day</option>
                                                            @for ($i = 1; $i <= 31; $i++)
                                                                @php
                                                                    $day = str_pad($i, 2, '0', STR_PAD_LEFT);
                                                                @endphp
                                                                <option value="{{ $day }}">{{ $day }}</option>
                                                            @endfor
                                                        </select>

                                                        <!-- Month -->
                                                        <select name="valid_from_month" class="placeholder:text-textmuted text-sm selected_seasion_type" wire:model="valid_from_month">
                                                            <option value="" hidden selected>Month</option>
                                                            @foreach ([
                                                                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                                                                5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                                                                9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                                                            ] as $num => $name)
                                                                @php
                                                                    $month = str_pad($num, 2, '0', STR_PAD_LEFT);
                                                                @endphp
                                                                <option value="{{ $month }}">{{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Valid To -->
                                                <div class="flex flex-col mx-1">
                                                    <label>
                                                        <span class="badge gap-2 bg-danger/10 text-danger uppercase">
                                                            Valid To
                                                        </span>
                                                    </label>
                                                    <div class="flex gap-2">
                                                        <!-- Day -->
                                                        <select name="valid_to_day" class="placeholder:text-textmuted text-sm selected_seasion_type" wire:model="valid_to_day">
                                                            <option value="" hidden selected>Day</option>
                                                        @for ($i = 1; $i <= 31; $i++)
                                                                @php
                                                                    $day = str_pad($i, 2, '0', STR_PAD_LEFT);
                                                                @endphp
                                                                <option value="{{ $day }}">{{ $day }}</option>
                                                            @endfor
                                                        </select>

                                                        <!-- Month -->
                                                        <select name="valid_to_month" class="placeholder:text-textmuted text-sm selected_seasion_type" wire:model="valid_to_month">
                                                            <option value="" hidden selected>Month</option>
                                                            @foreach ([
                                                                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                                                                5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                                                                9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                                                            ] as $num => $name)
                                                                @php
                                                                    $month = str_pad($num, 2, '0', STR_PAD_LEFT);
                                                                @endphp
                                                                <option value="{{ $month }}">{{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr> --}}
                                    <tr>
                                        <td>
                                            @if($newPresetError)
                                            <div class="alert alert-danger">
                                                {{ $newPresetError }}
                                            </div>
                                            @endif
                                            @if(count($itinerary_journey)==count($itinerary_journey_divisions))
                                            <div class="text-end mt-3">
                                                <button type="submit"
                                                    class="ti-btn ti-btn-primary-full !py-1 pt-0 ti-btn-wave me-[0.375rem]">
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

        {{-- Import Modal --}}
        @if($showImportModal)
            <div id="lead_import_modal"
                class="hs-overlay {{$showImportModal?"":"hidden"}} fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div
                    class="hs-overlay-open:mt-7 ti-modal-box mt-0 ease-out lg:!max-w-4xl lg:w-full m-3 lg:!mx-auto modal_md_width bg-white rounded-lg">
                    <div class="ti-modal-content p-20">
                        <div class="ti-modal-header flex justify-between items-center">
                              <h2 class="text-lg font-semibold mb-4 text-gray-800">Import Leads</h2>
                            <button type="button"
                                class="text-gray-400 hover:text-gray-600 focus:outline-none badge gap-2 bg-danger/10 text-danger"
                                wire:click="$set('showImportModal', false)">
                                <i class="fa-solid fa-xmark text-lg text-dark"></i>
                            </button>
                        </div>
                        <div class="ti-modal-body text-start">
                            <!-- Sample File & Upload Section -->
                            <div class="space-y-5">

                                <!-- Sample File Download -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-center justify-between">
                                    <div class="text-sm text-blue-700 font-medium">
                                        Need a template? Download the sample file:
                                    </div>
                                    <a href="{{ asset('assets/leads.xlsx') }}" download
                                    class="inline-flex items-center gap-1 text-sm ti-btn ti-btn-ghost-danger ti-btn-wave  me-[0.375rem] px-3 py-1.5 rounded shadow-sm">
                                        <i class="ti ti-download text-base"></i> Sample File
                                    </a>
                                </div>

                                <!-- File Upload Input -->
                                <div class="space-y-2">
                                    <label for="importFile" class="block text-sm font-semibold text-gray-700">
                                        Upload Lead File
                                        <span class="text-gray-500 text-xs font-normal">(Accepted: .xlsx, .csv)</span>
                                    </label>

                                    <div class="flex items-center gap-3">
                                        <input type="file" id="importFile" wire:model="importFile"
                                            class="block w-full text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        
                                        @if($importFile)
                                            <span class="text-green-600 text-sm font-medium" style="width: 105px;">SELECTED ✔</span>
                                        @endif
                                    </div>

                                    @error('importFile')
                                        <p class="text-red-600 text-sm">{{ $message }}</p>
                                    @enderror
                                    @if($leadImportError)
                                        <p class="text-red-600 text-sm font-medium mt-1">
                                            {{ $leadImportError }}
                                        </p>
                                    @endif
                                </div>

                            </div>


                            <!-- Footer -->
                            <div class="text-right mt-2">
                                <button wire:click="importLeads"
                                    class="ti-btn ti-btn-primary-full !py-1 pt-0 ti-btn-wave  me-[0.375rem]">
                                    Import
                                </button>
                                <button wire:click="$set('showImportModal', false)"
                                    class="ti-btn ti-btn-secondary-full !py-1 pt-0 ti-btn-wave  me-[0.375rem]">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if($showAssignLeadModal)
            <div
                class="hs-overlay {{$showAssignLeadModal?"":"hidden"}} fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div
                    class="hs-overlay-open:mt-7 ti-modal-box mt-0 ease-out lg:!max-w-4xl lg:w-full m-3 lg:!mx-auto modal_md_width bg-white rounded-lg">
                    <div class="ti-modal-content p-20">
                        <div class="ti-modal-header flex justify-between items-center">
                            <div>
                                 <h2 class="text-lg font-semibold text-gray-800 !mb-0">Assign Lead ({{$active_lead->unique_id}})</h2>
                                <button type="button" class="badge bg-outline-secondary me-2 badge-custom-outline-secondary">
                                    Team Lead
                                    <span class="badge bg-success ms-2 text-white">{{optional($active_lead->teamLead)->name}}</span>
                                </button>
                            </div>
                        </div>
                        <div class="ti-modal-body text-start mt-4">
                            {{-- Show already assigned info --}}
                            @if($active_lead->assigned_to_id)
                                <div class="mb-3">
                                    <div class="p-3 bg-yellow-100 text-yellow-900 rounded text-sm">
                                        This lead is already assigned to 
                                        <strong>{{ optional($active_lead->assignee)->name }}</strong>.
                                        You can reassign it to another member below.
                                    </div>
                                </div>
                            @endif

                            <div>
                                <select class="ti-form-select border-2 border-gray-300 rounded-sm !py-2 !px-3 !p-0"
                                    autocomplete="off" wire:model="selected_member">
                                    <option value="" hidden>Select a member...</option>
                                    @foreach ($members as $member_item)
                                        <option value="{{ $member_item->id }}">{{ $member_item->name }}</option>
                                    @endforeach
                                </select>

                                @if($leadAssignError)
                                    <p class="text-red-600 text-sm font-medium mt-1">
                                        {{ $leadAssignError }}
                                    </p>
                                @endif
                            </div>

                            <!-- Footer -->
                            <div class="text-right mt-2">
                                <button wire:click="$set('showAssignLeadModal', false)"
                                    class="ti-btn ti-btn-danger-full !py-1 pt-0 ti-btn-wave me-[0.375rem]">
                                    Cancel
                                </button>
                                <button wire:click="assignLead"
                                    class="ti-btn ti-btn-primary-full !py-1 pt-0 ti-btn-wave me-[0.375rem]">
                                    Assign
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if($showLeadStatusModal)
            <div
                class="hs-overlay {{$showLeadStatusModal?"":"hidden"}} fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div
                    class="hs-overlay-open:mt-7 ti-modal-box mt-0 ease-out lg:!max-w-4xl lg:w-full m-3 lg:!mx-auto modal_md_width bg-white rounded-lg">
                    <div class="ti-modal-content p-20">
                        <div class="ti-modal-header flex justify-between items-center">
                            <div>
                                 <h2 class="text-lg font-semibold text-gray-800 !mb-0">Active Status ({{$active_lead->unique_id}})</h2>
                                <button type="button" class="badge bg-outline-secondary me-2 badge-custom-outline-secondary">
                                    Team Lead
                                    <span class="badge bg-success ms-2 text-white">{{optional($active_lead->teamLead)->name}}</span>
                                </button>
                            </div>
                        </div>
                        <div class="ti-modal-body text-start mt-4">
                            {{-- Show already assigned info --}}
                           @if($active_lead->status)
                                <div class="mb-3">
                                    <div class="p-3 bg-yellow-100 text-yellow-900 rounded text-sm">
                                        This lead currently has the status: 
                                        <strong>{{ ucfirst($active_lead->status) }}</strong>.
                                    </div>
                                </div>
                            @endif

                            <div>
                                <label for="select-1"
                                        class="ti-form-select rounded-sm !py-2 !px-3-label text-sm text-textmuted custom_lable">Status</label>
                                <select class="ti-form-select border-2 border-gray-300 rounded-sm !py-2 !px-3 !p-0"
                                    autocomplete="off" wire:model="selected_status" wire:change="assignLeadStatus">
                                    <option value="" hidden>Select a status...</option>
                                        @foreach ($leads_status as $status_item)
                                            <option value="{{ $status_item->name }}">{{ ucwords($status_item->name)}}</option>
                                        @endforeach
                                </select>
                                @if($selected_status == "Confirmed")
                                    <label for="select-1"
                                        class="ti-form-select rounded-sm !py-2 !px-3-label text-sm text-textmuted custom_lable">Itinerary</label>

                                    <select class="ti-form-select border-2 border-gray-300 rounded-sm !py-2 !px-3 !p-0"
                                        autocomplete="off" wire:model="selected_itinerary">
                                        <option value="" hidden>Select an itinerary...</option>

                                        @foreach ($sent_lead_itineraries as $lead_itineraries)
                                            @php
                                                $title = "Code: {$lead_itineraries->itinerary_code}\n";
                                                $title .= "Itinerary: {$lead_itineraries->itinerary_syntax}\n";
                                                $title .= "Total Cost: ₹" . number_format($lead_itineraries->total_cost) . "\n";
                                                $title .= "Destination: " . optional($lead_itineraries->destination)->name . "\n";
                                                $title .= "Hotel Category: " . optional($lead_itineraries->category)->name;
                                            @endphp

                                            <option value="{{ $lead_itineraries->id }}" title="{{ $title }}">
                                                {{ $lead_itineraries->itinerary_code }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif


                                @if($leadAssignError)
                                    <p class="text-red-600 text-sm font-medium mt-1">
                                        {{ $leadAssignError }}
                                    </p>
                                @endif
                            </div>

                            <!-- Footer -->
                            <div class="text-right mt-2">
                                <button wire:click="$set('showLeadStatusModal', false)"
                                    class="ti-btn ti-btn-danger-full !py-1 pt-0 ti-btn-wave me-[0.375rem]">
                                    Cancel
                                </button>
                                <button wire:click="updateLeadStatus"
                                    class="ti-btn ti-btn-primary-full !py-1 pt-0 ti-btn-wave me-[0.375rem]">
                                    Update
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        {{-- End Import Modal --}}
        {{-- NewPresetItinerary, changeDestination,submitForm,setNightHalt,nightHaltSubmitForm,GetCategory,NewNightHalt,updateJourneyDivision,changePackageType,changeHotelCategory,changeMealPlan,getDestination, addExtraChild, removeExtraChild, enableChildren,changeNationalityType,toggleDestination,ResetAllField,changePackage,changeLeadType,changeLeadStatus,openImportModal,importLeads,showImportModal,importFile,ChangeDate,changeArrivalDate,assignLeadModal, --}}
    <div wire:loading class="loader">
        <div class="spinner">
        <img src="{{asset('build/assets/images/media/loader.svg')}}" alt="">
        </div>
    </div>
    <!-- Toast Container (bottom right) -->
    <div class="fixed bottom-5 inset-x-0 z-50 flex justify-end px-5 pointer-events-none">
        @if (session()->has('success'))
            <div
                x-data="{ show: true }"
                x-init="setTimeout(() => show = false, 3000)"
                x-show="show"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-4"
                class="bg-green-500 text-white px-5 py-3 rounded shadow-lg flex items-center space-x-2 pointer-events-auto"
            >
                <i class="ti ti-check text-xl"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div
                x-data="{ show: true }"
                x-init="setTimeout(() => show = false, 3000)"
                x-show="show"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-4"
                class="bg-red-500 text-white px-5 py-3 rounded shadow-lg flex items-center space-x-2 pointer-events-auto"
            >
                <i class="ti ti-alert-triangle text-xl"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
    </div>

</div>
@section('scripts')
<script>
    window.addEventListener('refreshComponent', function (event) {
        document.querySelectorAll('.refresh_component').forEach(element => {
            element.value = ''; // Clear input fields
        });
    });
     window.addEventListener('setNightHaltInput', function (event) {
        let value = event.detail[0].value;
        $('#night_halt_details').val(value);
    });
     window.addEventListener('setDate', function (event) {
        let arrival_date = event.detail[0].arrival_date;
        let departure_date = event.detail[0].departure_date;
        $('#arrival_date').val(arrival_date);
        $('#departure_date').val(departure_date);
    });
</script>
@endsection
