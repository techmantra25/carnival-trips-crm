@extends('layouts.master')

@section('styles')

<!-- Jsvector Maps -->
{{-- <link rel="stylesheet" href="{{asset('build/assets/libs/jsvectormap/css/jsvectormap.min.css')}}"> --}}

@endsection

@section('content')
    <livewire:dashboard-index/>
<!-- row closed -->
@endsection

@section('scripts')

<!-- Apex Charts JS -->
{{-- <script src="{{asset('build/assets/libs/apexcharts/apexcharts.min.js')}}"></script> --}}


<!-- JSVector Maps JS -->
{{-- <script src="{{asset('build/assets/libs/jsvectormap/js/jsvectormap.min.js')}}"></script> --}}

<!-- JSVector Maps MapsJS -->
{{-- <script src="{{asset('build/assets/libs/jsvectormap/maps/world-merc.js')}}"></script> --}}
@vite('resources/assets/js/us-merc-en.js')

<!-- CRM-Dashboard -->
@vite('resources/assets/js/index.js')

@endsection
