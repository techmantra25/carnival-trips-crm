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

    <!-- Modal Form -->
 @if($modalMode==true)
    <div class="mb-6">
        <div class="box custom-box">
            <div class="box-header">
                <h6 class="uppercase">{{ $updateMode ? 'Edit Employee' : 'Add New Employee' }}</h6>
            </div>
            <div class="box-body">
               <div class="grid grid-cols-12 sm:gap-6">
                    <div class="xl:col-span-4">
                        <label class="block text-sm font-medium text-gray-700 modal_query_lable">Name<span class="text-danger">*</span></label>
                        <input type="text" wire:model.defer="name" class="form-control" autocomplete="off" placeholder="Enter Name">
                        @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                   
                    <div class="xl:col-span-4">
                        <label class="block text-sm font-medium text-gray-700 modal_query_lable">Email<span class="text-danger">*</span></label>
                        <input type="email" wire:model.defer="email" class="form-control" autocomplete="off" placeholder="Enter Email">
                        @error('email') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                     <div class="xl:col-span-4">
                        <label class="block text-sm font-medium text-gray-700 modal_query_lable">
                            Role <span class="text-danger">*</span>
                        </label>
                        <div class="box-body sm:flex items-center gap-4 flex-wrap">
                            @php
                                if(in_array($authUser->role, ['team_lead', 'member'])){
                                    $roles = ['member'];
                                }else{
                                    $roles = ['admin', 'team_lead', 'member'];
                                }
                                
                            @endphp

                            @foreach($roles as $value)
                                <div class="form-check flex items-center gap-2">
                                    <input class="form-check-input"
                                        type="radio"
                                        id="role_{{ $value }}"
                                        value="{{ $value }}"
                                        wire:model.defer="role"
                                        name="role">
                                    <label class="form-check-label uppercase cursor-pointer" for="role_{{ $value }}">
                                        {{ str_replace('_', ' ', $value) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('role') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="xl:col-span-4">
                        <label class="block text-sm font-medium text-gray-700 modal_query_lable">Phone<span class="text-danger">*</span></label>
                        <input type="number" wire:model.defer="phone" class="form-control" autocomplete="off" placeholder="Enter Phone">
                        @error('phone') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="xl:col-span-4">
                        <label class="block text-sm font-medium text-gray-700 modal_query_lable">WhatsApp</label>
                        <input type="number" wire:model.defer="whatsapp" class="form-control" autocomplete="off" placeholder="Enter Whatsapp">
                        @error('whatsapp') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="xl:col-span-4">
                        <label class="block text-sm font-medium text-gray-700 modal_query_lable">Designation<span class="text-danger">*</span></label>
                        <select wire:model.defer="designation_id" class="form-control">
                            <option value="">Select designation</option>
                            @foreach($designations as $designation)
                                <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                            @endforeach
                        </select>
                        @error('designation_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="xl:col-span-12">
                        <label class="block text-sm font-medium text-gray-700 modal_query_lable">Address</label>
                        <textarea wire:model.defer="address" class="form-control" autocomplete="off" placeholder="Enter Address" rows="3"></textarea>
                        @error('address') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="xl:col-span-8">
                        <label class="block text-sm font-medium text-gray-700 modal_query_lable">Password</label>
                        <input type="password" wire:model.defer="password" class="form-control" autocomplete="off" placeholder="Set Password">
                        @error('password') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="xl:col-span-4">
                        <label class="block text-sm font-medium text-gray-700 modal_query_lable">Team Lead<span class="text-danger">*</span></label>
                        <select wire:model.defer="team_lead" class="form-control">
                            <option value="">Select Admin</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                            @endforeach
                        </select>
                        @error('team_lead') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="xl:col-span-12">
                        <label class="block text-sm font-medium text-gray-700 modal_query_lable">Destinations</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-2">
                            @foreach($states as $state)
                                <label class="inline-flex items-center">
                                    <input type="checkbox" wire:model.defer="destination_ids" value="{{ $state->id }}" class="form-checkbox text-primary">
                                    <span class="ml-2 text-sm text-gray-600">{{ $state->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('destination_ids') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button type="button" wire:click="resetInput" class="ti-btn ti-btn-danger-full px-4 py-1 mr-2">Back</button>
                    @if($updateMode)
                        <button wire:click="updateData" class="ti-btn ti-btn-primary-full px-4 py-1">Update</button>
                    @else
                        <button wire:click="store" class="ti-btn ti-btn-primary-full px-4 py-1">Submit</button>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endif
    <!-- Employee Table Section -->
    @if($modalMode==false)
    <div class="grid grid-cols-12 gap-6">
        <div class="xl:col-span-12 col-span-12">
            <div class="box custom-box">
                <div class="p-2">
                     @if (session()->has('success'))
                        <div class="alert alert-success mb-1">
                            {{ session('success') }}
                        </div>
                    @endif
                </div>
                <div class="box-header flex justify-end">
                    <div>
                        <div class="grid grid-cols-1 hover:grid-cols-6">
                            <label for="">
                                <span class="badge gap-2 bg-danger/10 text-danger uppercase">
                                    Destinations
                                </span>
                            </label>
                            <select wire:model="filter_destination" class="placeholder:text-textmuted text-sm selected_seasion_type" wire:change="filterDestination($event.target.value)">
                                <option value="">All Destinations</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="prism-toggle mt-5">
                        <a href="javascript:void(0)" wire:click="NewEmployee('Yes')"
                            class="ti-btn ti-btn-primary-full !py-1 pt-0 ti-btn-wave  me-[0.375rem]"><i
                                class="fa-solid fa-plus"></i>Add New Employee</a>
                    </div>
                    <div class="mt-5">
                        <a href="javascript:void(0)" wire:click="resetInput"
                            class="ti-btn ti-btn-sm ti-btn-soft-danger !border !border-danger/20">
                            <i class="ri-refresh-line"></i>
                        </a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="flex justify-between">
                        <div class="badge bg-outline-success cursor-pointer">
                            <span>No of Result: {{$totalEmployees}}</span>
                        </div>
                        <div>
                            <input type="text" class="badge bg-outline-primary w-xs refresh_component" wire:model="search" wire:keyup="QuickSearch($event.target.value)" placeholder="Quick Search..">
                        </div>
                    </div>
                    <table class="table whitespace-nowrap min-w-full mt-1">
                        <thead class="bg-secondary/10 uppercase">
                            <tr class="border-b border-defaultborder">
                                <th class="!text-left px-4 py-2">SL</th>
                                <th class="!text-left px-4 py-2">Employee</th>
                                <th class="!text-center">Designation</th>
                                <th class="!text-center">Team Lead</th>
                                <th class="!text-center">Status</th>
                                <th class="!text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $index => $employee)
                            <tr wire:key="employee-{{ $employee->id }}" class="border-b">
                                <!-- SL -->
                                <td class="px-4 py-3 text-gray-700">{{ $index + 1 }}</td>

                                <!-- Name + Email with Initial Badge -->
                                <td class="px-4 py-3">
                                    <div class="flex items-center space-x-3">
                                        <!-- Initials Badge -->
                                       @php
                                            $nameParts = explode(' ', trim($employee->name));
                                            $first = strtoupper($nameParts[0][0] ?? '');
                                            $second = strtoupper($nameParts[1][0] ?? '');
                                            $initials = $first . $second;

                                            $bgColors = [
                                                'bg-purple-100 text-purple-600',
                                                'bg-green-100 text-green-600',
                                                'bg-blue-100 text-blue-600',
                                                'bg-yellow-100 text-yellow-600',
                                                'bg-pink-100 text-pink-600',
                                            ];
                                            $badgeClass = $bgColors[$index % count($bgColors)];
                                        @endphp

                                        <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $badgeClass }} font-semibold">
                                            {{ $initials }}
                                        </div>

                                        <!-- Name and Email -->
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ $employee->name }}
                                                @php
                                                    $role = $employee->role;
                                                    $roleBadgeClass = match($role) {
                                                        'admin' => 'bg-danger/10 text-danger',
                                                        'team_lead' => 'bg-green-100 text-green-600',
                                                        'member' => 'bg-blue-100 text-blue-600',
                                                        default => 'bg-gray-100 text-gray-600',
                                                    };
                                                @endphp
                                                <span class="badge !rounded-full {{ $roleBadgeClass }}">
                                                    {{ ucwords(str_replace('_', ' ', $role)) }}
                                                </span>
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $employee->email }}</div>
                                            <div class="text-sm text-gray-500">+91 {{ $employee->phone }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Designation -->
                                <td class="!text-center">
                                    {{ $employee->designation->name ?? '-' }}
                                </td>

                                <!-- Team Lead -->
                                <td class="!text-center">{{ $employee->leader->name ?? '-' }}</td>

                                <!-- Status Toggle -->
                                <td class="!text-center">
                                    <div wire:click="toggleStatus({{ $employee->id }})"
                                        class="toggle toggle-sm cursor-pointer {{ $employee->status ? 'toggle-success on' : 'toggle-danger off' }}">
                                        <span></span>
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="!text-center">
                                    <button wire:click="edit({{ $employee->id }})" class="ti-btn ti-btn-sm ti-btn-soft-info">
                                        <i class="ti ti-pencil"></i>
                                    </button>
                                    @if(in_array($authUser->role, ['super_admin', 'admin']))
                                        <button wire:click="delete({{ $employee->id }})" class="ti-btn ti-btn-sm ti-btn-soft-danger">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    @endif
                                    @if($employee->role == "team_lead")
                                        <button wire:click="showTeamMembers({{ $employee->id }})"
                                            class="ti-btn ti-btn-secondary !py-1 !px-2 shadow-sm ti-btn-wave me-[0.375rem]">
                                            Team Members
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                    <div class="mt-4">
                        {{ $employees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

   @if($showTeamModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full p-6 relative overflow-hidden">

                <!-- Close Button -->
                {{-- <button wire:click="$set('showTeamModal', false)"
                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-2xl leading-none font-semibold">
                    &times;
                </button> --}}

                <!-- Modal Title -->
                <h2 class="text-xl font-bold text-gray-800 mb-5 border-b pb-2">
                    Team Members of <span class="text-primary">{{ $selectedLeaderName }}</span>
                </h2>

                <!-- Member List -->
                @if($teamMembers->isEmpty())
                    <p class="text-gray-500 text-sm">No team members assigned.</p>
                @else
                    <div class="overflow-y-auto max-h-96">
                        <table class="w-full text-sm text-left text-gray-700 border">
                            <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                                <tr>
                                    <th class="px-4 py-2 border-b">SL</th>
                                    <th class="px-4 py-2 border-b">Name</th>
                                    <th class="px-4 py-2 border-b">Email</th>
                                    <th class="px-4 py-2 border-b">Phone</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($teamMembers as $i => $member)
                                <tr>
                                    <td class="px-4 py-2">{{ $i + 1 }}</td>
                                    <td class="px-4 py-2 font-medium">{{ $member->name }}</td>
                                    <td class="px-4 py-2 text-gray-600">{{ $member->email }}</td>
                                    <td class="px-4 py-2 text-gray-600">+91 {{ $member->phone }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <!-- Footer -->
                <div class="mt-5 text-right">
                    <button wire:click="$set('showTeamModal', false)"
                        class="px-4 py-2 bg-red-200 hover:bg-red-300 text-sm font-medium rounded-md text-dark">
                        Close
                    </button>
                </div>
            </div>
        </div>
        @endif




    <div wire:loading class="loader">
        <div class="spinner">
            <img src="{{ asset('build/assets/images/media/loader.svg') }}" alt="">
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.addEventListener('showConfirm', function (event) {
        let itemId = event.detail[0].itemId;
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('deleteItem', itemId);
            }
        });
    });
    window.addEventListener('refreshComponent', function (event) {
        document.querySelectorAll('.refresh_component').forEach(element => {
            element.value = ''; // Clear input fields
        });
    });
</script>

@endsection
