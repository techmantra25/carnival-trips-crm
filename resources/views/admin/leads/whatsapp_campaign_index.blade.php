@extends('layouts.master')
@section('styles')
     <link rel="stylesheet" href="{{asset('build/assets/libs/whatsapp.css')}}">
@endsection
    @section('title','WhatsApp Campaign Management') <!-- This sets the page title dynamically -->
        @section('content')
            <livewire:lead.whatsapp-campaign-dashboard/>
        @endsection
@section('scripts')
@endsection
