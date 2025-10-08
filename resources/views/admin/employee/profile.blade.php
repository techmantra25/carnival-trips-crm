@extends('layouts.master')
@section('styles')
@endsection
@section('title',$common['pageTitle']) <!-- This sets the page title dynamically -->
@section('content')
    
    <!-- Start:: row-10 -->
    <livewire:employee.profile-index/>
    <!-- End:: row-10 -->
@endsection

@section('scripts')
	
@endsection
