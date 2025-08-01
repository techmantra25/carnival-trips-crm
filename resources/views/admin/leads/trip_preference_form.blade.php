@extends('layouts.master')

@section('title','Trip Preference Form')

@section('content')
<div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">
            {{ $common['childHeader'] }}
        </h5>

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
</div>
    <livewire:lead.trip-preference-question-answer-crud />
<!-- End:: Shared History Section -->

@endsection

@section('scripts')

@endsection
