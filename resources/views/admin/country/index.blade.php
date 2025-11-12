@extends('layouts.master')
@section('styles')
@endsection

@section('title', $pageTitle) <!-- Dynamic page title -->

@section('content')
<div class="md:flex block items-center justify-between mb-6 mt-1 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0 uppercase">{{$childHeader}}</h5>
        <nav>
            <ol class="flex items-center whitespace-nowrap min-w-0">
                <li class="text-[12px]">
                    <a class="flex items-center text-primary hover:text-primary" href="javascript:void(0);"> 
                        {{$parentHeader}} 
                        <i class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                    </a>
                </li>
                <li class="text-[12px]"> 
                    <a class="flex items-center text-textmuted" href="javascript:void(0);">{{$childHeader}}</a> 
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- Start:: row-10 -->
<div class="grid grid-cols-12 gap-6">
    <div class="xl:col-span-8 col-span-12">
        <div class="box custom-box">
            <div class="box-body">
                <!-- Search Form -->
                <form method="GET" action="{{ route('admin.country.index') }}" class="mb-4">
                    <div class="input-group !mb-0">
                        <input 
                            type="text" 
                            name="country" 
                            value="{{ request('country') }}" 
                            placeholder="Search by Country Code or Name" 
                            class="form-control form-control-sm"
                        >
                        <button type="submit" class="change-text-button ti-btn ripple ti-btn-primary-full !mb-0">Search</button>
                        <a href="{{ route('admin.country.index') }}" class="ti-btn ti-btn-danger-full text-white ti-btn-icon me-2 !mb-0"><i class="mdi mdi-refresh"></i></a>
                    </div>
                </form>

                <!-- Country Table -->
                <div class="table-responsive">
                    @if($country_codes->isEmpty())
                        <p class="text-center mt-4">No country codes found.</p>
                    @else
                        <x-global-table 
                            :items="$country_codes" 
                            :fields="['country_name', 'phone_code', 'number_length', 'status']" 
                            dataType="country_codes" 
                            :extra="[]"
                        />
                        {{ $country_codes->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Form for Create/Update -->
    <div class="xl:col-span-4 col-span-12">
        @if(isset($update_item))
            <div class="bg-custom_card">
                <div class="box-header">
                    <h6 class="uppercase text-black">Update {{$childHeader}}</h6>
                </div>
                <div class="box-body">
                    <form action="{{ route('admin.country.update') }}" method="post">
                        @csrf
                        <x-form-field 
                            type="text" 
                            name="country_code" 
                            label="Country Code" 
                            :value="old('country_code', $update_item->country_code ?? '')"
                        />
                        @error('country_code')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror

                        <x-form-field 
                            type="text" 
                            name="country_name" 
                            label="Country Name" 
                            :value="old('country_name', $update_item->country_name ?? '')"
                        />
                        @error('country_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror

                        <x-form-field 
                            type="text" 
                            name="phone_code" 
                            label="Phone Code" 
                            :value="old('phone_code', $update_item->phone_code ?? '')"
                        />
                        @error('phone_code')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror

                        <x-form-field 
                            type="number" 
                            name="phone_length" 
                            label="Phone Length" 
                            :value="old('phone_length', $update_item->phone_length ?? '')"
                        />
                        @error('phone_length')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror

                        <div class="flex justify-end mt-3">
                            <x-input-field type="hidden" name="id" value="{{$update_item->id}}" />
                            <a href="{{ route('admin.country.index') }}" class="ti-btn ti-btn-danger-full !py-1 !px-2 me-2">
                                <i class="fa-solid fa-caret-left"></i> Back
                            </a>
                            <x-form-submit-button text="Update" class="ti-btn ti-btn-primary-full !py-1 !px-2" />
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="box custom-box">
                <div class="box-header">
                    <h6 class="uppercase">New Country</h6>
                </div>
                <div class="box-body">
                    <form action="{{ route('admin.country.store') }}" method="post">
                        @csrf
                        <x-form-field type="text" name="country_code" label="Country Code" :value="old('country_code')" />
                        @error('country_code')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror

                        <x-form-field type="text" name="country_name" label="Country Name" :value="old('country_name')" />
                        @error('country_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror

                        <x-form-field type="text" name="phone_code" label="Phone Code" :value="old('phone_code')" />
                        @error('phone_code')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror

                        <x-form-field type="number" name="phone_length" label="Phone Length" :value="old('phone_length')" />
                        @error('phone_length')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                        <input type="hidden" name="status" value="1">

                        <div class="flex justify-end mt-3">
                            <x-form-submit-button text="Submit" class="ti-btn ti-btn-primary-full !py-1 !px-2" />
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
<!-- End:: row-10 -->
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
