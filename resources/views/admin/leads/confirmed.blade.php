@extends('layouts.master')
    @section('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('build/assets/libs/flatpickr/flatpickr.min.css')}}">
    @endsection
    <!-- Jquery Cdn -->
            <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

    @section('title','Lead Management') <!-- This sets the page title dynamically -->
    @section('content')
        <!-- Start:: row-10 -->
        <livewire:lead.confirmed-lead-index/>
        <!-- End:: row-10 -->
    @endsection

    @section('scripts')
        
    
    <!-- Select2 Cdn -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{asset('build/assets/libs/flatpickr/flatpickr.min.js')}}"></script>

    <!-- Internal Select-2.js -->
    @vite('resources/assets/js/select2.js')
    @vite('resources/assets/js/date&time_pickers.js')
@endsection
