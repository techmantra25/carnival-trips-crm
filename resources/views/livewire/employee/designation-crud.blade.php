<div>
    <div class="md:flex block items-center justify-between mb-6 mt-1 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0 uppercase">
                {{ $common['childHeader'] }}
            </h5>
            <nav>
                <ol class="flex items-center whitespace-nowrap min-w-0">
                    <li class="text-[12px]"> <a class="flex items-center text-primary hover:text-primary" href="javascript:void(0);">{{ $common['parentHeader'] }}<i class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                        </a> </li>
                    <li class="text-[12px]"> <a class="flex items-center text-textmuted" href="javascript:void(0);">{{ $common['childHeader'] }}
                        </a> </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <!-- Table Section -->
        <div class="xl:col-span-8 col-span-12">
            <div class="box custom-box">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table whitespace-nowrap min-w-full">
                            <thead class="bg-secondary/10 uppercase">
                                <tr class="border-b border-defaultborder">
                                    <th class="!text-center">SL</th>
                                    <th class="!text-center">Designation</th>
                                    <th class="!text-center">Status</th>
                                    <th class="!text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($designations as $index => $designation)
                                    <tr wire:key="designation-{{ $designation->id }}">
                                        <td class="border-b border-defaultborder !text-center"> <span class="badge bg-primary/10 text-primary">{{ $index + 1 }}</span></td>
                                        <td class="border-b border-defaultborder !text-center">{{ $designation->name }}</td>
                                        <td class="border-b border-defaultborder !text-center">
                                            <div wire:click="toggleStatus({{ $designation->id }})" class="toggle toggle-sm mb-4 cursor-pointer 
                                                {{ $designation->status  ? 'toggle-success on' : 'toggle-danger off' }}">
                                                <span></span>
                                            </div>
                                        </td>
                                        <td class="border-b border-defaultborder !text-center">
                                            <button wire:click="edit({{ $designation->id }})"
                                                class="ti-btn ti-btn-sm ti-btn-soft-info !border !border-info/20"><i class="ti ti-pencil"></i></button>
                                            <button wire:click="delete({{ $designation->id }})"
                                                class="ti-btn ti-btn-sm ti-btn-soft-danger !border !border-danger/20"><i class="ti ti-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $designations->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="xl:col-span-4 col-span-12">
            <div class="box custom-box">
                <div wire:key="{{ $updateMode ? 'edit-form' : 'add-form' }}">
                    <div class="box-header">
                        <h6 class="uppercase">{{ $updateMode ? 'Edit' : 'Add' }} Designation</h6>
                    </div>
                    <div class="box-body">
                        <div class="form-group mb-4 px-1">
                            <label for="name" class="block text-gray-700 font-medium">Name</label>
                            <input type="text" name="name" wire:model.defer="name" autocomplete="off"
                                class="form-control refresh_component" placeholder="Enter Name">
                            @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex justify-{{$updateMode?'between':'end'}}">
                            @if($updateMode)
                                <a href="javascript:void(0)" wire:click="resetInput" class="change-text-button ti-btn ti-btn-danger-full !py-1 !px-2 ti-btn-wave me-[0.375rem]">
                                    Cancel
                                </a>
                            @endif
                            <a href="javascript:void(0)"
                            wire:click="{{ $updateMode ? 'updateData' : 'store' }}"
                            class="change-text-button ti-btn ti-btn-primary-full !py-1 !px-2 ti-btn-wave me-[0.375rem]">
                                {{ $updateMode ? 'Update' : 'Submit' }}
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div wire:loading class="loader">
        <div class="spinner">
        <img src="{{asset('build/assets/images/media/loader.svg')}}" alt="">
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
                @this.call('deleteItem', itemId); // Calls Livewire method directly
                // Swal.fire("Deleted!", "Your item has been deleted.", "success");
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
