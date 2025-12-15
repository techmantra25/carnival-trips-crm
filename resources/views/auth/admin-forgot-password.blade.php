@extends('layouts.custom-master2')

@section('styles')
@endsection

@section('content')

<div class="container">
    <div class="grid grid-cols-12 authentication authentication-basic items-center h-full text-defaultsize text-defaulttextcolor">

        <div class="xxl:col-span-4 xl:col-span-4 lg:col-span-4 md:col-span-3 sm:col-span-2"></div>

        <div class="xxl:col-span-4 xl:col-span-4 lg:col-span-4 md:col-span-6 sm:col-span-8 col-span-12">

            <div class="my-[2.5rem] flex justify-center">
                <a href="{{ url('index') }}">
                    <img src="{{ asset('build/assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="desktop-logo">
                    <img src="{{ asset('build/assets/images/brand-logos/desktop-dark.png') }}" alt="logo" class="desktop-dark">
                </a>
            </div>

            <div class="box">
                <div class="box-body !p-[3rem]">

                    @php $step = session('step') ?? 'email'; @endphp

                    <!-- Page Title -->
                    <p class="h5 font-semibold mb-2 text-center !text-defaulttextcolor">
                        @if($step == 'email') Forgot Password @endif
                        @if($step == 'verify_otp') Verify OTP @endif
                        @if($step == 'reset_password') Reset Password @endif
                    </p>

                    <!-- Subtext -->
                    <p class="mb-4 text-[#8c9097] opacity-[0.7] font-normal text-center">
                        @if($step == 'email') Enter your email to receive an OTP @endif
                        @if($step == 'verify_otp') Enter the OTP sent to your email @endif
                        @if($step == 'reset_password') Enter your new password @endif
                    </p>

                    <!-- Success Message -->
                    @if(session('message'))
                        <div class="alert alert-success mb-3 text-center">{{ session('message') }}</div>
                    @endif

                    <!-- STEP 1 — EMAIL -->
                    @if($step == 'email')
                    <form method="POST" action="{{ route('admin.send.otp') }}">
                        @csrf
                        <div class="grid grid-cols-12 gap-y-4">

                            <div class="xl:col-span-12 col-span-12">
                                <label class="form-label text-default">Email Address</label>
                                <input type="email" name="email" class="form-control form-control-lg w-full !rounded-md" required>
                                @error('email') 
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="xl:col-span-12 col-span-12">
                                <button class="ti-btn ti-btn-lg bg-primary text-white w-full">Send OTP</button>
                            </div>

                        </div>
                    </form>
                    @endif

                    <!-- STEP 2 — OTP -->
                    @if($step == 'verify_otp')
                    <form method="POST" action="{{ route('admin.verify.otp') }}">
                        @csrf
                        <div class="grid grid-cols-12 gap-y-4">

                            <div class="xl:col-span-12 col-span-12">
                                <label class="form-label text-default">Enter OTP</label>
                                <input type="text" name="otp" class="form-control form-control-lg w-full !rounded-md" required>
                                @error('otp') 
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="xl:col-span-12 col-span-12">
                                <button class="ti-btn ti-btn-lg bg-success text-white w-full">Verify OTP</button>
                            </div>

                        </div>
                    </form>
                    @endif

                    <!-- STEP 3 — RESET PASSWORD -->
                    @if($step == 'reset_password')
                    <form method="POST" action="{{ route('admin.reset.password') }}">
                        @csrf
                        <div class="grid grid-cols-12 gap-y-4">

                            <div class="xl:col-span-12 col-span-12">
                                <label class="form-label text-default">New Password</label>
                                <input type="password" name="password" class="form-control form-control-lg w-full !rounded-md" required>
                                @error('password') 
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="xl:col-span-12 col-span-12">
                                <label class="form-label text-default">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control form-control-lg w-full !rounded-md" required>
                            </div>

                            <div class="xl:col-span-12 col-span-12">
                                <button class="ti-btn ti-btn-lg bg-primary text-white w-full">Reset Password</button>
                            </div>

                        </div>
                    </form>
                    @endif

                </div>
            </div>

        </div>

        <div class="xxl:col-span-4 xl:col-span-4 lg:col-span-4 md:col-span-3 sm:col-span-2"></div>

    </div>
</div>

@endsection
