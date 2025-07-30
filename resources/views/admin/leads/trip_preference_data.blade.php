@extends('layouts.master')

@section('title','Lead Management')

@section('content')
<div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">
            {{ $common['childHeader'] }}
        </h5>

        <div class="mt-1 text-sm text-gray-600">
            <span class="font-semibold">Customer:</span> {{ $lead->customer_name ?? 'N/A' }} |
            <span class="font-semibold">Mobile:</span> {{ $lead->customer_mobile ?? 'N/A' }}
        </div>

        <nav>
            <ol class="flex items-center whitespace-nowrap min-w-0 text-[12px] mt-1">
                <li>
                    <a class="flex items-center text-primary hover:text-primary" href="javascript:void(0);">
                        {{ $common['parentHeader'] }}
                        <i class="ti ti-chevrons-right flex-shrink-0 mx-2 text-textmuted rtl:rotate-180"></i>
                    </a>
                </li>
                <li>
                    <a class="flex items-center text-textmuted" href="javascript:void(0);">
                        {{ $common['childHeader'] }}
                    </a>
                </li>
            </ol>
        </nav>
    </div>

    <div class="ti-btn-list">
        <a href="{{ route('admin.leads.index') }}"
           class="ti-btn ti-btn-danger-full !py-1 !px-2 ti-btn-wave me-[0.375rem]">
            <i class="fa-solid fa-caret-left me-1"></i> Back
        </a>
    </div>
</div>
    <livewire:lead.trip-preference-data :lead_id="$lead_id" />
<!-- End:: Shared History Section -->

@endsection

@section('scripts')
@endsection
