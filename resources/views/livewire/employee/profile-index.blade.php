<div>
    <div class="md:flex block items-center justify-between mb-6 mt-1 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0 uppercase">
                {{ $common['childHeader'] }}
            </h5>
            <nav>
                <ol class="flex items-center whitespace-nowrap min-w-0">
                    <li class="text-[12px]"> <a class="flex items-center text-primary hover:text-primary"
                            href="javascript:void(0);">{{ $common['parentHeader'] }}<i
                                class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                        </a> </li>
                    <li class="text-[12px]"> <a class="flex items-center text-textmuted"
                            href="javascript:void(0);">{{ $common['childHeader'] }}
                        </a> </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-x-6">
        <div class="xxl:col-span-4 xl:col-span-12 col-span-12">
            <div class="box overflow-hidden">
                <div class="box-body !p-0">
                    <div class="sm:flex items-start !py-6 px-4 main-profile-cover">
                        <div> <span class="avatar avatar-xxl avatar-rounded online me-4"> <img
                                    src="{{asset('build/assets/images/faces/admin.png')}}" alt=""> </span> </div>
                        <div class="flex-grow main-profile-info">
                            <div class="flex items-center !justify-between">
                                <h6 class="font-semibold mb-1 text-white text-[1rem]">{{$authUser->name}}</h6>
                            </div>
                            @if($authUser->role == "super_admin")
                                <p class="mb-1 !text-white opacity-[0.7]">
                                    {{ ucwords(str_replace('_', ' ', $authUser->role)) }}
                                </p>
                            @else
                                <p class="mb-1 !text-white opacity-[0.7]">{{$authUser->designation?$authUser->designation->name:"N/A"}}</p>
                            @endif
                        </div>
                    </div>
                    <div class="px-6 pt-6 border-b border-dashed dark:border-defaultborder/10">
                        <div class="mb-6">
                            <p class="text-[.9375rem] mb-2 font-semibold">Professional Bio :</p>
                            <p class="text-[0.75rem] text-textmuted opacity-[0.7] mb-0 text-justify" style="text-align: justify; text-justify: inter-word;">
                                I am <b class="!text-defaulttextcolor font-medium">{{ ucwords($authUser->name) }}</b>, 
                                currently serving as <b class="!text-defaulttextcolor font-medium">{{$authUser->designation?$authUser->designation->name:"Member"}}</b> 
                                at our organization. I am committed to contributing to the success of our team by maintaining professionalism, 
                                efficiency, and collaboration in all assigned responsibilities. This bio can be updated anytime to reflect 
                                my growth and achievements within the company.
                            </p>
                        </div>
                    </div>
                    <div class="px-6 pb-6 border-b border-dashed dark:border-defaultborder/10">
                        <p class="text-[.9375rem] mb-2 me-6 font-semibold">Contact Information :</p>
                        <div class="text-textmuted">
                            <p class="mb-2"> <span class="avatar avatar-sm avatar-rounded me-2 bg-light text-textmuted"> <i
                                        class="ri-mail-line align-middle text-[.875rem] text-textmuted"></i> </span> {{$authUser->email}}</p>
                            <p class="mb-2"> <span class="avatar avatar-sm avatar-rounded me-2 bg-light text-textmuted"> <i
                                        class="ri-phone-line align-middle text-[.875rem] text-textmuted"></i> </span> {{$authUser->phone}}</p>
                            <p class="mb-0"> <span class="avatar avatar-sm avatar-rounded me-2 bg-light text-textmuted"> <i
                                        class="ri-map-pin-line align-middle text-[.875rem] text-textmuted"></i> </span>
                               {{$authUser->address}} </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="xxl:col-span-8 xl:col-span-12 col-span-12">
            <div class="grid grid-cols-12 gap-x-6">
                <div class="xl:col-span-12 col-span-12">
                    <div class="box">
                        <div class="!p-4">
                            @if (session()->has('success'))
                                <div class="mt-2 text-green-600 bg-green-100 border border-green-300 rounded-md px-3 py-2 text-sm">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <div class="mb-4 pt-2 main-content-label">Profile Information</div>

                            <form wire:submit.prevent="updateProfile" class="form-horizontal">
                                {{-- Name --}}
                                <div class="form-group mb-3">
                                    <div class="grid grid-cols-12 sm:gap-x-6">
                                        <div class="md:col-span-3 col-span-12">
                                            <label class="form-label">Name</label>
                                        </div>
                                        <div class="md:col-span-9 col-span-12">
                                            <input type="text" wire:model.defer="name" class="form-control  placeholder:text-textmuted" placeholder="Full Name">
                                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Email --}}
                                <div class="form-group mb-3">
                                    <div class="grid grid-cols-12 sm:gap-x-6">
                                        <div class="md:col-span-3 col-span-12">
                                            <label class="form-label">Email</label>
                                        </div>
                                        <div class="md:col-span-9 col-span-12">
                                            <input type="email" wire:model.defer="email" class="form-control  placeholder:text-textmuted" placeholder="Email Address" disabled>
                                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Phone --}}
                                <div class="form-group mb-3">
                                    <div class="grid grid-cols-12 sm:gap-x-6">
                                        <div class="md:col-span-3 col-span-12">
                                            <label class="form-label">Phone</label>
                                        </div>
                                        <div class="md:col-span-9 col-span-12">
                                            <input type="text" wire:model.defer="phone" class="form-control placeholder:text-textmuted" placeholder="Phone Number">
                                            @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- WhatsApp --}}
                                <div class="form-group mb-3">
                                    <div class="grid grid-cols-12 sm:gap-x-6">
                                        <div class="md:col-span-3 col-span-12">
                                            <label class="form-label">WhatsApp</label>
                                        </div>
                                        <div class="md:col-span-9 col-span-12 flex items-center gap-3">
                                            <input type="text" id="whatsapp" wire:model.defer="whatsapp" class="form-control  placeholder:text-textmuted" placeholder="WhatsApp Number">
                                            <label class="flex items-center space-x-1">
                                                <input 
                                                    type="checkbox" 
                                                    wire:model="sameAsPhone" 
                                                    class="form-check-input cursor-pointer" 
                                                    wire:change="marksameAsPhone($event.target.checked)">
                                                <span class="text-[0.8rem]">Same as Phone</span>
                                            </label>
                                            @error('whatsapp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Address --}}
                                <div class="form-group mb-3">
                                    <div class="grid grid-cols-12 sm:gap-x-6">
                                        <div class="md:col-span-3 col-span-12">
                                            <label class="form-label">Address</label>
                                        </div>
                                        <div class="md:col-span-9 col-span-12">
                                            <textarea wire:model.defer="address" class="form-control  placeholder:text-textmuted" rows="2" placeholder="Enter address"></textarea>
                                            @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Password Reset --}}
                                <div class="mb-4 main-content-label">Reset Password</div>

                                <div class="form-group mb-3">
                                    <div class="grid grid-cols-12 sm:gap-x-6">
                                        <div class="md:col-span-3 col-span-12">
                                            <label class="form-label">New Password</label>
                                        </div>
                                        <div class="md:col-span-9 col-span-12">
                                            <input 
                                                type="password" 
                                                wire:model.defer="password" 
                                                class="form-control placeholder:text-textmuted" 
                                                placeholder="New Password" 
                                                autocomplete="new-password">
                                            @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <div class="grid grid-cols-12 sm:gap-x-6">
                                        <div class="md:col-span-3 col-span-12">
                                            <label class="form-label">Confirm Password</label>
                                        </div>
                                        <div class="md:col-span-9 col-span-12">
                                            <input 
                                                type="password" 
                                                wire:model.defer="password_confirmation" 
                                                class="form-control placeholder:text-textmuted" 
                                                placeholder="Confirm Password" 
                                                autocomplete="new-password">
                                            @error('password_confirmation') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>


                                {{-- Submit --}}
                                <button type="submit" class="ti-btn ti-btn-primary-full float-end !my-3 waves-effect waves-light">
                                    Update Profile
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div wire:loading class="loader">
        <div class="spinner">
            <img src="{{ asset('build/assets/images/media/loader.svg') }}" alt="">
        </div>
    </div>
</div>
@section('scripts')
<script>
    window.addEventListener('showConfirm', function (event) {
        let number = event.detail[0].number;   // access by key
        let status = event.detail[0].status;

        if(status == 1){
            document.getElementById('whatsapp').value = number;
        } else {
            document.getElementById('whatsapp').value = "";
        }
    });
</script>

@endsection
