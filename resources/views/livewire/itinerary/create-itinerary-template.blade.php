<div>
    <div class="grid grid-cols-12 gap-6">
        <div class="xl:col-span-12 col-span-12">
            <div class="box custom-box">
                <div class="mt-2 mx-2 mb-1">
                    @if (session('success'))
                        <div id="success-message" class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {!! session('error') !!}
                        </div>
                    @endif
                </div>
                <div class="box-header itinerary_box_header">
                    <div class="flex justify-between pb-2">
                        <div class="flex">
                            <div class="mx-2">
                                <p class="badge gap-2 bg-danger/10 text-danger uppercase">Destination</p><hr>
                                <div class="badge bg-outline-primary cursor-pointer !font-normal !text-sm uppercase">
                                    <span> {{$destination_name}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <form wire:submit.prevent="submitForm">
                        <div class="table-responsive mb-2">
                            <table class="table whitespace-nowrap table-bordered table-bordered-primary border-primary/10 min-w-full new-activity">
                                <tbody>
                                    <table class="table table-bordered table-bordered-primary border-primary/10 min-w-full new-activity">
                                        <thead class="bg-primary/20">
                                            <tr>
                                                <th class="!text-center">BANNER SECTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <span class="badge gap-2 bg-danger/10 text-danger uppercase text-small mx-2">Main Banner</span>
                                                    <div class="grid grid-cols-12 sm:gap-x-6 sm:gap-y-4">
                                                        <div class="md:col-span-10 col-span-12 mb-4 itinerary-build">
                                                            <div class="image-preview-container">
                                                                @foreach ($mainBanner as $index => $main_banner)
                                                                    <label class="image-preview-label px-1">
                                                                        <input type="radio" name="main_banner" wire:model="main_banner" value="{{ $main_banner->image }}" class="hidden peer" wire:change="UpdateByKeyUp('banner_section','main_banner',$event.target.value)">
                                                                        <div class="image-preview peer-checked:border-blue-500 relative">
                                                                            <img src="{{ asset($main_banner->image) }}" alt="Image Preview" class="image-thumbnail">
                                                                            <!-- Selected Text -->
                                                                            <div class="absolute bottom-0 left-0 right-0 bg-white text-center text-sm font-semibold py-1 hidden peer-checked:block">
                                                                                Selected
                                                                            </div>
                                                                        </div>
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <div class="md:col-span-2 col-span-12 mb-4">
                                                            <div class="border-l-0 sightseeing_images">
                                                                <label class="file-upload-container">
                                                                    <span class="choose-text">Upload New Banner</span>
                                                                    <input type="file" wire:model="uploadMainBanner" class="file-input" accept="image">
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </tbody>
                            </table>
                            <table class="table table-bordered table-bordered-primary border-primary/10 min-w-full new-activity">
                                <thead class="bg-primary/20">
                                    <tr>
                                        <th class="!text-center uppercase">For Great Experience</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="grid grid-cols-12 mb-4">
                                                <label class="sm:col-span-2 col-span-12 col-form-label "> Title</label>
                                                <div class="sm:col-span-10 col-span-12">
                                                    <input type="text" class="form-control form-control-sm" wire:model="great_experience_title" wire:keyup="UpdateByKeyUp('great_experience','great_experience_title',$event.target.value)">
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-12 mb-4">
                                                <label class="sm:col-span-2 col-span-12 col-form-label">Text</label>
                                                <div class="sm:col-span-10 col-span-12">
                                                    <textarea wire:model="great_experience_text" class="form-control form-control-sm" rows="3" wire:keyup="UpdateByKeyUp('great_experience','great_experience_text',$event.target.value)">
                                                    </textarea>
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-12 mb-4">
                                                <label class="sm:col-span-2 col-span-12 col-form-label">Sub Details</label>
                                                <div class="sm:col-span-8 col-span-12">
                                                    @foreach ($great_experience_sub_detail as $ex_index => $sub_detail)
                                                        <div class="flex items-start gap-2 mb-2 flex-col sm:flex-row">
                                                            {{-- Title --}}
                                                            <textarea 
                                                                class="form-control form-control-sm w-full sm:w-1/2" 
                                                                placeholder="Title"
                                                                wire:model="great_experience_sub_detail.{{ $ex_index }}.title"
                                                                wire:keyup="UpdateByKeyUpValue('great_experience','great_experience_sub_detail_{{ $ex_index }}', $event.target.value)">
                                                            </textarea>

                                                            {{-- Description --}}
                                                            <textarea 
                                                                class="form-control form-control-sm w-full sm:w-1/2" 
                                                                placeholder="Description"
                                                                wire:model="great_experience_sub_detail.{{ $ex_index }}.description"
                                                                wire:keyup="UpdateByKeyUpDesc('great_experience','great_experience_sub_detail_{{ $ex_index }}', $event.target.value)">
                                                            </textarea>

                                                            <button type="button" wire:click="removeGreatExperienceSubDetail({{ $ex_index }})" 
                                                                class="badge bg-outline-danger cursor-pointer !font-normal !text-sm uppercase mt-1 sm:mt-0">
                                                                Remove
                                                            </button>
                                                        </div>
                                                    @endforeach

                                                    <div class="text-end">
                                                        <button type="button" wire:click="addGreatExperienceSubDetail" 
                                                            class="badge bg-outline-success cursor-pointer !font-normal !text-sm uppercase">
                                                            Add Sub Detail
                                                        </button>
                                                    </div>

                                                </div>
                                                <div class="sm:col-span-2 col-span-12">
                                                    <span class="badge gap-2 bg-danger/10 text-danger uppercase text-small mx-2">Great Experience Image</span>
                                                    <div class="itinerary-build">
                                                        <div class="image-preview-container">
                                                            @if ($greatexperienceimage)
                                                                <div class="image-preview peer-checked:border-blue-500 relative !overflow-visible" wire:key="destination-image-{{ $greatexperienceimage }}">
                                                                    <!-- Delete Button (Top-Right Corner) -->
                                                                    <button type="button"
                                                                        class="delete-icon"
                                                                        wire:click="ItineraryImageDelete('{{ $greatexperienceimage }}')">
                                                                        ✖
                                                                    </button>
                                                                    <!-- Image -->
                                                                    <img src="{{ $greatexperienceimage }}" alt="Image Preview" class="image-thumbnail cursor-pointer">
                                                                </div>
                                                            @endif
                                                        </div>
                                                        @error('great_experience_image') 
                                                            <span class="text-red-500">{{ $message }}</span> 
                                                        @enderror
                                                    </div>
                                                        <div class="m-2">
                                                            <div class="border-l-0 sightseeing_images">
                                                            <label class="file-upload-container">
                                                                <span class="choose-text">Upload Image</span>
                                                                <input type="file" wire:model="great_experience_image" class="file-input" accept="image/*">
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-bordered table-bordered-primary border-primary/10 min-w-full new-activity">
                                <thead class="bg-primary/20">
                                    <tr>
                                        <th class="!text-center uppercase">About Destination</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="grid grid-cols-12 mb-4">
                                                <div class="sm:col-span-10 col-span-10">
                                                    <div class="grid grid-cols-12 mb-4">
                                                        <label class="sm:col-span-2 col-span-12 col-form-label ">
                                                            Title</label>
                                                        <div class="sm:col-span-10 col-span-12">
                                                            <input type="text" class="form-control form-control-sm"
                                                                wire:model="about_destination_title"
                                                                wire:keyup="UpdateByKeyUp('about_destination','about_destination_title',$event.target.value)">
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-12 mb-4">
                                                        <label class="sm:col-span-2 col-span-12 col-form-label">Text</label>
                                                        <div class="sm:col-span-10 col-span-12">
                                                            <textarea wire:model="about_destination_text"
                                                                class="form-control form-control-sm" rows="3"
                                                                wire:keyup="UpdateByKeyUp('about_destination','about_destination_text',$event.target.value)">
                                                        </textarea>
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-12 mb-4">
                                                        <label class="sm:col-span-2 col-span-12 col-form-label">Trip
                                                            Highlights</label>
                                                        <div class="sm:col-span-10 col-span-12">
                                                            @foreach ($trip_highlights as $index => $highlight)
                                                            <div class="flex items-center gap-2 mb-2">
                                                                <input type="text" class="form-control form-control-sm"
                                                                    wire:model="trip_highlights.{{ $index }}"
                                                                    wire:keyup="UpdateByKeyUp('about_destination','trip_highlights_{{ $index }}',$event.target.value)">

                                                                {{-- @if ($index > 0)  --}}
                                                                <button type="button"
                                                                    wire:click="removeAboutDescHighlight({{ $index }})"
                                                                    class="badge bg-outline-danger cursor-pointer !font-normal !text-sm uppercase">
                                                                    Remove
                                                                </button>
                                                                {{-- @endif --}}
                                                            </div>
                                                            @endforeach

                                                            <div class="text-end">
                                                                <button type="button" wire:click="addAboutDescHighlight"
                                                                    class="badge bg-outline-success cursor-pointer !font-normal !text-sm uppercase">
                                                                    Add Trip Highlights
                                                                </button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="sm:col-span-2 col-span-10">
                                                    <span
                                                        class="badge gap-2 bg-danger/10 text-danger uppercase text-small mx-2">About Destination Image</span>
                                                    <div class="itinerary-build">
                                                        <div class="image-preview-container">
                                                            @if ($aboutdestinationimage)
                                                            <div class="image-preview peer-checked:border-blue-500 relative !overflow-visible"
                                                                wire:key="destination-image-{{ $aboutdestinationimage }}">
                                                                <!-- Delete Button (Top-Right Corner) -->
                                                                <button type="button" class="delete-icon"
                                                                    wire:click="ItineraryImageDelete('{{ $aboutdestinationimage }}')">
                                                                    ✖
                                                                </button>
                                                                <!-- Image -->
                                                                <img src="{{ $aboutdestinationimage }}" alt="Image Preview"
                                                                    class="image-thumbnail cursor-pointer">
                                                            </div>
                                                            @endif
                                                        </div>
                                                        @error('about_destination_image')
                                                        <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="m-2">
                                                        <div class="border-l-0 sightseeing_images">
                                                            <label class="file-upload-container">
                                                                <span class="choose-text">Upload Image</span>
                                                                <input type="file" wire:model="about_destination_image"
                                                                    class="file-input" accept="image/*">
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-bordered table-bordered-primary border-primary/10 min-w-full new-activity">
                                <thead class="bg-primary/20">
                                    <tr>
                                        <th class="!text-center uppercase">Destination Images for Slider</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="grid grid-cols-12 sm:gap-x-6 sm:gap-y-4">
                                                <div class="md:col-span-10 col-span-12 mb-4 itinerary-build">
                                                    <div class="image-preview-container">
                                                        @foreach ($destinationImages as $slider_index => $slider_image)
                                                        <div class="image-preview peer-checked:border-blue-500 relative !overflow-visible"
                                                            wire:key="destination-image-{{ $slider_image }}">
                                                            <!-- Delete Button (Top-Right Corner) -->
                                                            <button type="button" class="delete-icon"
                                                                wire:click="ItineraryImageDelete('{{ $slider_image }}')">
                                                                ✖
                                                            </button>
                                                            <!-- Image -->
                                                            <img src="{{ $slider_image }}" alt="Image Preview"
                                                                class="image-thumbnail cursor-pointer">
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    @error('uploadDestinationSlider.*')
                                                    <span class="text-red-500">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="md:col-span-2 col-span-12 mb-4">
                                                    <div class="border-l-0 sightseeing_images">
                                                        <label class="file-upload-container">
                                                            <span class="choose-text">Upload Slider Images</span>
                                                            <input type="file" wire:model="uploadDestinationSlider"
                                                                class="file-input" accept="image/*" multiple>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-bordered table-bordered-primary border-primary/10 min-w-full new-activity">
                                <thead class="bg-primary/20">
                                    <tr>
                                        <th class="!text-center uppercase">Inclusion & Exclusions                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="grid grid-cols-12 mb-4">
                                                <label class="sm:col-span-2 col-span-12 col-form-label">Inclusions</label>
                                                <div class="sm:col-span-10 col-span-12">
                                                    @foreach ($inclusions as $index => $highlight)
                                                        <div class="flex items-center gap-2 mb-2">
                                                            <img src="{{asset('build/assets/images/media/checkmark.png')}}" alt="" width="24" height="24">
                                                            <input type="text" class="form-control form-control-sm" 
                                                                wire:model="inclusions.{{ $index }}" 
                                                                wire:keyup="UpdateByKeyUp('inclusion_exclusions','inclusions_{{ $index }}',$event.target.value)">
                                                
                                                            {{-- @if ($index > 0)  --}}
                                                                <button type="button" wire:click="removeAboutInclusion({{ $index }})" 
                                                                    class="badge bg-outline-danger cursor-pointer !font-normal !text-sm uppercase">
                                                                    Remove
                                                                </button>
                                                            {{-- @endif --}}
                                                        </div>
                                                    @endforeach
                                                
                                                    <div class="text-end">
                                                        <button type="button" wire:click="addAboutInclusion" 
                                                            class="badge bg-outline-success cursor-pointer !font-normal !text-sm uppercase">
                                                            Add Inclusion
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="text-primary" style="border: 1px dashed #107bff;">
                                            <div class="grid grid-cols-12 my-4 ">
                                                <label class="sm:col-span-2 col-span-12 col-form-label">Exclusions</label>
                                                <div class="sm:col-span-10 col-span-12">
                                                    @foreach ($exclusions as $index => $highlight)
                                                        <div class="flex items-center gap-2 mb-2">
                                                            <img src="{{asset('build/assets/images/media/cross.png')}}" alt="" width="24" height="24">
                                                            <input type="text" class="form-control form-control-sm" 
                                                                wire:model="exclusions.{{ $index }}" 
                                                                wire:keyup="UpdateByKeyUp('inclusion_exclusions','exclusions_{{ $index }}',$event.target.value)">
                                                
                                                            {{-- @if ($index > 0)  --}}
                                                                <button type="button" wire:click="removeAboutExclusion({{ $index }})" 
                                                                    class="badge bg-outline-danger cursor-pointer !font-normal !text-sm uppercase">
                                                                    Remove
                                                                </button>
                                                            {{-- @endif --}}
                                                        </div>
                                                    @endforeach
                                                
                                                    <div class="text-end">
                                                        <button type="button" wire:click="addAboutExclusion" 
                                                            class="badge bg-outline-success cursor-pointer !font-normal !text-sm uppercase">
                                                            Add Exclusion
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div wire:loading class="loader" wire:target="uploadMainBanner,removeAboutDescHighlight,addAboutDescHighlight,ItineraryImageDelete,uploadDestinationSlider,addAboutInclusion,addAboutExclusion,removeAboutInclusion,removeAboutExclusion,addGreatExperienceSubDetail,removeGreatExperienceSubDetail,great_experience_image,about_destination_image">
        <div class="spinner">
        <img src="{{asset('build/assets/images/media/loader.svg')}}" alt="">
        </div>
    </div>
</div>
