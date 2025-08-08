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
                </div>
                <div class="box-body">
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
                                {{-- <div>
                                    <label for="company_name"
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
                                        class="text-danger text-sm font-12">{{ $message }}</span> @enderror
                                </div> --}}
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
                                            min="{{date('Y-m-d')}}" value="{{$arrival_date}}" wire:change="changeArrivalDate($event.target.value)">
                                        @error('arrival_date') <span
                                            class="text-danger text-sm font-12">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="px-1 w-full">
                                        <label
                                            class="block text-sm font-medium text-gray-700 modal_query_lable">Departure
                                            Date<span class="text-danger">*</span>
                                        </label>
                                        <input type="date" wire:model="departure_date" name="departure_date"
                                            id="departure_date"
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
                                        class="block text-sm font-medium text-gray-700 modal_query_lable">Source
                                        Type<span class="text-danger">*</span>
                                    </label>
                                    <select name="source_type" wire:model="source_type"
                                        class="form-control font-12 {{ $errors->has('source_type') ? '!border-danger focus:border-danger focus:ring-danger' : '' }}">
                                        <option value="" selected hidden>Select source type</option>
                                        @foreach ($queryTypes as $item_query)
                                        <option value="{{$item_query['name']}}"
                                            {{ old('source_type') == $item_query['name'] ? 'selected' : '' }}>
                                            {{$item_query['name']}}</option>
                                        @endforeach
                                    </select>

                                    @error('source_type') <span
                                        class="text-danger text-sm font-12">{{ $message }}</span> @enderror
                                </div>
                                <div class="relative w-full" wire:click.away="$set('night_halt_status', 0)">
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
                                        <div class="px-3 py-2 text-sm text-gray-500">No results found.</div>
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
                        <div>
                            <div class="text-end">
                                <button type="submit" wire:click.prevent="submitForm"
                                    class="ti-btn ti-btn-primary-full !py-1 pt-0 ti-btn-wave  me-[0.375rem]">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
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
                                    wire:change="GetCategory($event.target.value)" wire:key="category-0" wire:model="selectedCategory">
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
                                                            wire:keyup="validateDaysAndNights($event.target.value)"
                                                            class="form-control form-control-sm"
                                                            value="{{$nightHalt['day']}}" readonly>
                                                        <span>D</span>
                                                    </div>
                                                    <div class="spacer">/</div>
                                                    <div class="nd-group">
                                                        <input type="text" wire:model="nightHalt.night"
                                                            wire:keyup="validateDaysAndNights($event.target.value)"
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
                                                        placeholder="Itinerary Journey"  value="{{$nightHalt['night_distribution']}}" readonly>
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
    </div>
    <div wire:loading class="loader"
        wire:target="NewPresetItinerary, changeDestination,submitForm,setNightHalt,nightHaltSubmitForm,GetCategory,NewNightHalt,updateJourneyDivision,changePackageType,changeHotelCategory,changeMealPlan,getDestination, addExtraChild, removeExtraChild, enableChildren,changeNationalityType,changeArrivalDate">
        <div class="spinner">
            <img src="{{asset('build/assets/images/media/loader.svg')}}" alt="">
        </div>
    </div>
</div>
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
