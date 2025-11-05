<div>
    <div class="date-pick-row !mb-3">
        <div class="inputs-wrapper flex justify-end">
            <x-form-field type="select" name="destination" label="Destination"
                :options="$destinations->pluck('name', 'id')->toArray()" :value="old('destination')"
                wire:model="selectedDestination" wire:change="GetDivisions($event.target.value)"
                class="placeholder:text-textmuted text-sm selected_seasion_type" :selectedText="'Choose Destination'" />
            <!-- Division Select -->
            <x-form-field type="select" name="division" label="Division" :options="$divisions" :value="old('division')"
                wire:model="selectedDivision" wire:change="changeDivision($event.target.value)"
                class="placeholder:text-textmuted text-sm selected_seasion_type" :selectedText="'Choose Division'" />
            <!-- Hotel Category Select -->
            <x-form-field type="select" name="hotel_category" label="Category" :options="$hotelCategories"
                :value="old('hotel_category')" wire:model="selectedCategory"
                wire:change="changeCategory($event.target.value)"
                class="placeholder:text-textmuted text-sm selected_seasion_type" :selectedText="'Choose Category'" />
            <!-- Hotels Select -->
            <div style="width: 250px;">
                <x-form-field type="select" name="hotel" label="Hotel" :options="$hotels"
                    :value="old('hotel', $selectedHotel)"
                    wire:change="changeHotel($event.target.value)"
                    wire:model="selectedHotel"
                    class="placeholder:text-textmuted text-sm selected_seasion_type choose_hotels"
                    :selectedText="'Choose Hotel'" />
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table whitespace-nowrap table-bordered table-bordered-primary border-primary/10 min-w-full">
            <thead class="uppercase">
                <tr class="border-b !border-primary/30">
                    <th scope="col" class="!text-center">SL No.</th>
                    <th scope="col" class="!text-center">Start Date</th>
                    <th scope="col" class="!text-center">End Date</th>
                    <th scope="col" class="!text-center">Block Type</th>
                    <th scope="col" class="!text-center">Email Sent</th>
                    <th scope="col" class="!text-center">Sent By</th>
                    <th scope="col" class="!text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($availabilityRequests as $index => $request)
                    <tr>
                        <td class="!text-center">
                            <span class="badge bg-primary/10 text-primary">{{ $index + 1 }}</span>
                        </td>
                        <td class="!text-center">{{ $request->start_date }}</td>
                        <td class="!text-center">{{ $request->end_date }}</td>
                        <td class="!text-center">{{ $request->block_type }}</td>
                        <td class="!text-center">
                            @if($request->email_sent)
                                <span class="badge bg-success/10 text-success">Yes</span>
                            @else
                                <span class="badge bg-danger/10 text-danger">No</span>
                            @endif
                        </td>
                        <td class="!text-center">
                            <div>
                                <span class="badge bg-warning/10 text-warning" title="Send mail by Member">
                                    <strong class="ms-1 text-black"> 
                                         @if($request->admin)
                                            <span class="text-black"><i class="fa-regular fa-user me-1"></i></span>{{ $request->admin->name }} <br> ({{ $request->admin->email }})
                                        @else
                                            N/A
                                        @endif
                                    </strong>
                                </span>
                            </div>
                        </td>
                        <td class="!text-center">
                            <a href="#" 
                            class="badge bg-primary/10 text-primary">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="!text-center text-gray-500">No availability records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>
