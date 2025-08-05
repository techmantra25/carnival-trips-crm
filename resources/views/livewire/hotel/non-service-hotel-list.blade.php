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
                <div class="box-header flex justify-end">
                    <div>
                        <div class="grid grid-cols-1 hover:grid-cols-6">
                            <label for="">
                                <span class="badge gap-2 bg-danger/10 text-danger uppercase">
                                    Destinations
                                </span>
                            </label>
                            <!-- Division Select -->
                            <select name="destination_list"
                                class="placeholder:text-textmuted text-sm selected_seasion_type"
                                wire:model="destination_id"
                                wire:change="getDestination($event.target.value)" 
                                wire:key="destination-0">
                               <option value="" disabled {{ $destination_id === '' ? 'selected' : '' }}>
                                    Filter destination
                                </option>
                                @foreach ($destinations as $destination_item)
                                    <option value="{{$destination_item->id}}">
                                        {{ ucfirst($destination_item->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <div class="grid grid-cols-1 hover:grid-cols-6">
                            <label for="">
                                <span class="badge gap-2 bg-danger/10 text-danger uppercase">
                                    Divisions
                                </span>
                            </label>
                            <!-- Division Select -->
                            <select name="division_list"
                                class="placeholder:text-textmuted text-sm selected_seasion_type"
                                wire:model="division_id"
                                wire:change="getDivision($event.target.value)" 
                                wire:key="division-0">
                               <option value="" disabled {{ $division_id === '' ? 'selected' : '' }}>
                                    Filter divisions
                                </option>
                                @foreach ($divisions as $division_item)
                                    <option value="{{$division_item->id}}">
                                        {{ ucfirst($division_item->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <div class="grid grid-cols-1 hover:grid-cols-6" wire:ignore>
                            <label for="">
                                <span class="badge gap-2 bg-danger/10 text-danger uppercase">
                                    Hotels
                                </span>
                            </label>
                            <!-- Hotel Select -->
                            <select name="hotel_list" id="hotel_list"
                                class="placeholder:text-textmuted text-sm selected_seasion_type select2"
                                wire:model="hotel_id"
                                wire:key="filter-hotel-0">
                               <option value="" disabled {{ $hotel_id === '' ? 'selected' : '' }}>
                                    Filter hotels
                                </option>
                                @foreach ($filter_hotels as $filter_hotel_item)
                                    <option value="{{$filter_hotel_item->id}}">
                                        {{ ucfirst($filter_hotel_item->hotel_name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="prism-toggle mt-5">
                        <a href="javascript:void(0)" wire:click="toggleCreateModal(1)"
                            class="ti-btn ti-btn-primary-full !py-1 pt-0 ti-btn-wave  me-[0.375rem]"><i
                                class="fa-solid fa-plus"></i>Add New Hotel</a>
                    </div>
                    <div class="mt-5">
                        <a href="javascript:void(0)"
                            class="ti-btn ti-btn-sm ti-btn-soft-danger !border !border-danger/20" wire:click="ResetFields">
                            <i class="ri-refresh-line"></i>
                        </a>
                    </div>
                </div>
                 <div class="box-body">
                    <div class="flex justify-between">
                        <div>
                            <div class="badge bg-outline-success cursor-pointer">
                                <span>No of Result: {{count($hotels)}}</span>
                            </div>
    
                        </div>
                        <div class="flex">
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="table-responsive">
                            <table
                                class="table whitespace-nowrap table-bordered table-bordered-primary border-primary/10 min-w-full">
                                <thead class="uppercase">
                                    <tr class="border-b !border-primary/30">
                                        <th scope="col" class="!text-center">SL No.</th>
                                        <th scope="col" class="!text-center">Hotel Name</th>
                                        <th scope="col" class="!text-center">Division</th>
                                        <th scope="col" class="!text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($hotels as $index => $item)
                                        <tr wire:key="hotel-{{ $item->id }}">
                                          <td class="!text-center !p-1 w-[10%]"><span class="badge bg-primary/10 text-primary">{{$index+1}}</span>
                                            </td>
                                           <td scope="row" class="align-top !p-1 w-[25%]">
                                                <div class="mb-1 text-[0.875rem] font-semibold">
                                                    <a href="javascript:void(0);" class="badge bg-danger/10 text-danger custom_button_text">{{$item->hotel_name}}</a>
                                                </div>
                                                <div class="flex items-center">
                                                    <div class="me-4">
                                                        <span class="avatar avatar-xxl bg-light">
                                                            <img src="{{asset($item->image)}}" alt="">
                                                        </span>
                                                    </div>
                                                    <div>
                                                        {{$item->address}}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="!text-center">{{optional($item->city)->name??"N/A"}}</td>
                                            <td class="!text-center">
                                                <button wire:click="editHotel({{ $item->id }})" class="ti-btn ti-btn-sm ti-btn-soft-info !border !border-info/20">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                                <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-soft-danger !border !border-danger/20" id="action-button-{{$item->id}}" wire:click="removeExistingHotel({{$item->id}})">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                    <tr>
                                        <td class="align-top !text-center" colspan="4">
                                            <div class="alert alert-danger !text-black !text-xs">
                                                Hotels are not added.
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
        <div id="assign_cab"
            class="hs-overlay {{$active_new_hotel_modal==0?"hidden":""}} fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div
                class="hs-overlay-open:mt-7 ti-modal-box mt-0 ease-out lg:!max-w-4xl lg:w-full m-3 lg:!mx-auto modal_md_width bg-white rounded-lg">
                <form wire:submit.prevent="saveHotel">
                    <div class="ti-modal-content p-20">
                        <div class="ti-modal-header flex justify-end items-center">
                            <button type="button"
                                class="text-gray-400 hover:text-gray-600 focus:outline-none badge gap-2 bg-danger/10 text-danger"
                                wire:click="toggleCreateModal(0)">
                                <i class="fa-solid fa-xmark text-lg text-dark"></i>
                            </button>
                        </div>
                        <div class="ti-modal-body text-start">
                            <!-- Destinations -->
                            <div class="grid grid-cols-1 hover:grid-cols-6 mb-2">
                                <label>
                                    <span class="badge gap-2 bg-primary/10 text-primary uppercase">Destinations</span>
                                </label>
                                <select name="destination_list"
                                    class="placeholder:text-textmuted text-sm selected_seasion_type"
                                    wire:model="destination_id"
                                    wire:change="getDestination($event.target.value)" 
                                    wire:key="destination-0">
                                <option value="" disabled {{ $destination_id === '' ? 'selected' : '' }}>
                                        Filter destination
                                    </option>
                                    @foreach ($destinations as $destination_item)
                                        <option value="{{$destination_item->id}}">
                                            {{ ucfirst($destination_item->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('destination_id') <span class="text-danger text-sm font-12">{{ $message }}</span> @enderror
                            </div>
                            <!-- Divisions -->
                            <div class="grid grid-cols-1 hover:grid-cols-6 mb-2">
                                <label>
                                    <span class="badge gap-2 bg-primary/10 text-primary uppercase">Divisions</span>
                                </label>
                                <select name="division_list"
                                    class="placeholder:text-textmuted text-sm selected_seasion_type {{ $errors->has('division_id') ? '!border-danger focus:border-danger focus:ring-danger' : '' }}"
                                    wire:model="division_id"
                                    wire:change="getDivision($event.target.value)" 
                                    wire:key="division-0">
                                    <option value="" disabled {{ $division_id === '' ? 'selected' : '' }}>
                                        Select divisions
                                    </option>
                                    @foreach ($divisions as $division_item)
                                        <option value="{{ $division_item->id }}">
                                            {{ ucfirst($division_item->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('division_id') <span class="text-danger text-sm font-12">{{ $message }}</span> @enderror
                            </div>

                            <!-- Hotel Name -->
                            <div class="grid grid-cols-1 hover:grid-cols-6 mb-2">
                                <label>
                                    <span class="badge gap-2 bg-primary/10 text-primary uppercase">Hotel Name</span>
                                </label>
                                <input type="text" wire:model="hotel_name"
                                    placeholder="hotel name" id="hotel_name"
                                    class="form-control form-control-lg placeholder:text-textmuted text-sm font-12 {{ $errors->has('hotel_name') ? '!border-danger focus:border-danger focus:ring-danger' : '' }}" value="{{$hotel_name}}">
                                @error('hotel_name') <span class="text-danger text-sm font-12">{{ $message }}</span> @enderror
                            </div>

                            <!-- Image Upload -->
                            <div class="grid grid-cols-1 hover:grid-cols-6 mb-2 new-activity">
                                <div class="flex justify-between items-start">
                                    <div>
                                        @if ($image)
                                            <div class="flex flex-wrap gap-2 mt-2">
                                                <div class="w-24 h-24 border rounded overflow-hidden">
                                                    <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="w-full h-full object-cover">
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="pl-4">
                                        <label class="file-upload-container">
                                            <span class="choose-text">Choose Image</span>
                                            <input type="file" wire:model="image" class="file-input" accept="image/*">
                                        </label>
                                    </div>
                                </div>
                                @error('image') <span class="text-danger text-sm font-12">{{ $message }}</span> @enderror
                            </div>

                            <!-- Hotel Address -->
                            <div class="grid grid-cols-1 hover:grid-cols-6 mb-2">
                                <label>
                                    <span class="badge gap-2 bg-primary/10 text-primary uppercase">Hotel Address</span>
                                </label>
                                <textarea wire:model="hotel_address" name="hotel_address" id="hotel_address"
                                    class="form-control form-control-lg placeholder:text-textmuted text-sm font-12 {{ $errors->has('hotel_address') ? '!border-danger focus:border-danger focus:ring-danger' : '' }}">
                                </textarea>
                                @error('hotel_address') <span class="text-danger text-sm font-12">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="ti-model-footer">
                            <div class="text-end mt-3">
                                <button type="submit" class="ti-btn ti-btn-primary-full !py-1 pt-0 ti-btn-wave me-[0.375rem]">
                                    <i class="fa-solid fa-save"></i> {{ $edit_mode ? 'Update' : 'Save' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div wire:loading class="loader">
        <div class="spinner">
            <img src="{{asset('build/assets/images/media/loader.svg')}}" alt="">
        </div>
    </div>
</div>
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.addEventListener('showConfirm', function (event) {
        let itemId = event.detail[0].itemId;
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('deleteItem', itemId); // Calls Livewire method directly
            }
        });
    });
    window.addEventListener('FetchContent', function (event) {
        let hotel_name = event.detail[0].hotel_name;
        let hotel_address = event.detail[0].hotel_address;
        document.getElementById('hotel_name').value = hotel_name;
        document.getElementById('hotel_address').value = hotel_address;
    });

    $(document).ready(function () {
        $('.select2').select2();
        $('#hotel_list').select2({
            placeholder: "Filter by hotel",
            allowClear: true // optional: adds "x" to clear selections
        });
        $('#hotel_list').on('change', function (e) {
            var value = $(this).select2("val");
            @this.call('getHotel', value);
        });
    });
</script>
@endsection