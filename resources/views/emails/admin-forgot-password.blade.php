@extends('emails.email-template')

@section('content')
    <h2>Hello {{ $name }},</h2>
    <p>Your OTP for password reset is:</p>
    <h1 style="letter-spacing:4px; font-size:32px; color:#007bff;">{{ $otp }}</h1>
    <p>Please do not share this OTP with anyone.</p>
@endsection
