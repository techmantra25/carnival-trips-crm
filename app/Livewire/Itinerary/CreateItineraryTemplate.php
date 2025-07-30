<?php

namespace App\Livewire\Itinerary;

use Livewire\Component;
use App\Models\ItineraryTemplate;
use App\Models\ItineraryTemplateDetail;
use App\Helpers\CustomHelper;
use Livewire\WithFileUploads;
use App\Models\ItineraryBanner;
use App\Models\City;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CreateItineraryTemplate extends Component
{

    use WithFileUploads;
    public $destination_name;
    public $template_id;
    public $template;
    public $main_banner, $great_experience_title, $great_experience_text, $great_experience_image, $about_destination_image,$about_destination_title, $about_destination_text;
    public $mainBanner = [];
    public $trip_highlights = []; 
    public $great_experience_sub_detail = []; 
    public $inclusions = []; 
    public $exclusions = []; 
    public $destinationImages = []; 
    public $greatexperienceimage; 
    public $aboutdestinationimage; 
    public $uploadDestinationSlider = []; 
    public $selectedDivision;
    public $uploadMainBanner;
    public function mount($encryptedId){
        $templateExists = ItineraryTemplate::with('destination')->find($encryptedId);
        $this->template =$templateExists;
        $this->template_id =$encryptedId;
        $this->destination_name =$templateExists->destination?$templateExists->destination->name:"N/A";
        $city = City::where('state_id', $templateExists->destination_id)->first();
        if($city){
            $this->selectedDivision = $city->id;
        }
        $this->ExistingData();
        $this->loadDestinationImages();
        $this->loadGreatExperienceImage();
        $this->loadAboutDestinationImage();

    }

    public function ExistingData(){
        $this->main_banner = ItineraryTemplateDetail::where('itinerary_template_id', $this->template_id)->where('header', 'banner_section')->where('field', 'main_banner')->value('value');
        // About Great Experience
        $this->great_experience_title = ItineraryTemplateDetail::where('itinerary_template_id', $this->template_id)->where('header', 'great_experience')->where('field', 'great_experience_title')->value('value');
        
        $this->great_experience_text = ItineraryTemplateDetail::where('itinerary_template_id', $this->template_id)->where('header', 'great_experience')->where('field', 'great_experience_text')->value('value');
        // About Destination
        $this->about_destination_title = ItineraryTemplateDetail::where('itinerary_template_id', $this->template_id)->where('header', 'about_destination')->where('field', 'about_destination_title')->value('value');

        $this->about_destination_text = ItineraryTemplateDetail::where('itinerary_template_id', $this->template_id)->where('header', 'about_destination')->where('field', 'about_destination_text')->value('value');

        $this->trip_highlights = ItineraryTemplateDetail::where('itinerary_template_id', $this->template_id)->where('header', 'about_destination')->where('field', 'like', 'trip_highlights_%')->pluck('value')->toArray();

        $details = ItineraryTemplateDetail::where('itinerary_template_id', $this->template_id)
        ->where('header', 'great_experience')
        ->where('field', 'like', 'great_experience_sub_detail_%')
        ->get();

        $subDetails = [];

        foreach ($details as $index=>$item) {
             
            $subDetails[$index]['title'] = $item->value;
            $subDetails[$index]['description'] = $item->description;
        }

        $this->great_experience_sub_detail = $subDetails;
        
        $this->inclusions = ItineraryTemplateDetail::where('itinerary_template_id', $this->template_id)->where('header', 'inclusion_exclusions')->where('field', 'like', 'inclusions_%')->pluck('value')->toArray();
        $this->exclusions = ItineraryTemplateDetail::where('itinerary_template_id', $this->template_id)->where('header', 'inclusion_exclusions')->where('field', 'like', 'exclusions_%')->pluck('value')->toArray();

    }
    public function loadDestinationImages() {
        // Fetch all slider images from DB
        $this->destinationImages = ItineraryTemplateDetail::where('itinerary_template_id', $this->template_id)
            ->where('header', 'about_destination')
            ->where('field', 'slider_image')
            ->pluck('value')
            ->toArray();
    }
    public function loadGreatExperienceImage() {
        // Fetch all slider images from DB
        $this->greatexperienceimage = ItineraryTemplateDetail::where('itinerary_template_id', $this->template_id)
            ->where('header', 'great_experience')
            ->where('field', 'great_experience_image')
            ->value('value');
    }
    public function loadAboutDestinationImage() {
        // Fetch all slider images from DB
        $this->aboutdestinationimage = ItineraryTemplateDetail::where('itinerary_template_id', $this->template_id)
            ->where('header', 'about_destination')
            ->where('field', 'about_destination_image')
            ->value('value');
    }

    public function addAboutDescHighlight()
    {
        $this->trip_highlights[] = ''; // Add an empty input field
    }
    public function addGreatExperienceSubDetail()
    {
        $this->great_experience_sub_detail[] = [
            'title' => '',
            'description' => ''
        ];
    }
    public function addAboutInclusion()
    {
        $this->inclusions[] = ''; // Add an empty input field
    }
    public function addAboutExclusion()
    {
        $this->exclusions[] = ''; // Add an empty input field
    }

    public function removeAboutDescHighlight($index)
    {
        unset($this->trip_highlights[$index]); // Remove highlight
        $this->trip_highlights = array_values($this->trip_highlights); // Re-index array
        $this->deleteHighlightFromDB($index);
    }
    public function removeGreatExperienceSubDetail($index)
    {
        unset($this->great_experience_sub_detail[$index]);
        $this->great_experience_sub_detail = array_values($this->great_experience_sub_detail);
        $this->deleteGreatExperienceSubDetailFromDB($index);
    }
    public function removeAboutInclusion($index)
    {
        unset($this->inclusions[$index]); // Remove highlight
        $this->inclusions = array_values($this->inclusions); // Re-index array
        $this->deleteInclusionFromDB($index);
    }
    public function removeAboutExclusion($index)
    {
        unset($this->exclusions[$index]); // Remove highlight
        $this->exclusions = array_values($this->exclusions); // Re-index array
        $this->deleteExclusionFromDB($index);
    }
    public function deleteHighlightFromDB($index)
    {
        $fieldName = 'trip_highlights_' . $index;
        ItineraryTemplateDetail::where('itinerary_template_id', $this->template_id)
            ->where('header', 'about_destination')
            ->where('field', $fieldName)
            ->delete();
    }
    public function deleteGreatExperienceSubDetailFromDB($index)
    {
        $fieldName = 'great_experience_sub_detail_' . $index;
        ItineraryTemplateDetail::where('itinerary_template_id', $this->template_id)
            ->where('header', 'great_experience')
            ->where('field', $fieldName)
            ->delete();
    }
    public function deleteInclusionFromDB($index)
    {
        $fieldName = 'inclusions_' . $index;
        ItineraryTemplateDetail::where('itinerary_template_id', $this->template_id)
            ->where('header', 'inclusion_exclusions')
            ->where('field', $fieldName)
            ->delete();
    }
    public function deleteExclusionFromDB($index)
    {
        $fieldName = 'exclusions_' . $index;
        ItineraryTemplateDetail::where('itinerary_template_id', $this->template_id)
            ->where('header', 'inclusion_exclusions')
            ->where('field', $fieldName)
            ->delete();
    }

    public function updatedUploadMainBanner()
    {
        // Validate Image
        DB::beginTransaction();
        try {
            $this->validate([
                'uploadMainBanner' => 'image|max:2048', // 2MB Max
            ]);
            $store = new ItineraryBanner;
            $store->division_id = $this->selectedDivision;
            $store->destination_id = $this->template->destination_id;

            $uploadedFiles = $this->uploadMainBanner ?? null;
            if ($uploadedFiles) {
                $dynamicText =rand(1111,9999);
                $destinationName = $this->destination_name; // Assuming you have a division name
                $uploadedPath = CustomHelper::uploadImage($uploadedFiles, $dynamicText, $destinationName, 'itinerary_banners');
                $store->image = $uploadedPath;
            }
            $store->save();
            DB::commit();
            session()->flash('success', 'Banner uploaded successfully!');
        } catch (\Exception $e) {
            $this->reset(['uploadMainBanner']);
            DB::rollBack(); // Rollback on error
            session()->flash('error', 'Failed: ' . $e->getMessage());
        }
        
    }

    public function updateduploadDestinationSlider()
    {
        DB::beginTransaction();

        try {
            // Validate each uploaded image
            $this->validate([
                'uploadDestinationSlider.*' => 'image|max:2048', // 2MB max per image
            ]);

            foreach ($this->uploadDestinationSlider as $image) {
                if ($image) {
                    $dynamicText = rand(1111, 9999);
                    $uploadedPath = CustomHelper::uploadImage($image, $dynamicText, $this->destination_name, 'itinerary_template');

                    // Save file path in database
                    ItineraryTemplateDetail::create([
                        'itinerary_template_id' => $this->template_id,
                        'header' => 'about_destination',
                        'field' => 'slider_image',
                        'value' => asset($uploadedPath),
                    ]);
                }
            }

            DB::commit();

            // Reload images after upload
            $this->loadDestinationImages();

            session()->flash('success', 'Images uploaded successfully!');
            $this->reset('uploadDestinationSlider'); // Reset input after upload

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Upload failed: ' . $e->getMessage());
        }
    }
    public function updatedGreatExperienceImage()
    {
        DB::beginTransaction();

        try {
            // Validate uploaded image
            $this->validate([
                'great_experience_image' => 'image|max:2048', // 2MB max
            ]);

            // Store the image
            $dynamicText = rand(1111, 9999);
            $uploadedPath = CustomHelper::uploadImage($this->great_experience_image, $dynamicText, $this->destination_name, 'itinerary_template');

            // Save file path in database
            ItineraryTemplateDetail::updateOrCreate(
                [
                    'itinerary_template_id' => $this->template_id,
                    'header' => 'great_experience',
                    'field' => 'great_experience_image',
                ],
                [
                    'value' => asset($uploadedPath),
                ]
            );

            DB::commit();

            // Optionally reset the file input
             $this->loadGreatExperienceImage();
            session()->flash('success', 'Images uploaded successfully!');
            $this->reset('great_experience_image');
           
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to upload image: ' . $e->getMessage());
        }
    }
    public function updatedAboutDestinationImage()
    {
        DB::beginTransaction();

        try {
            // Validate uploaded image
            $this->validate([
                'about_destination_image' => 'image|max:2048', // 2MB max
            ]);

            // Store the image
            $dynamicText = rand(1111, 9999);
            $uploadedPath = CustomHelper::uploadImage($this->about_destination_image, $dynamicText, $this->destination_name, 'itinerary_template');

            // Save file path in database
            ItineraryTemplateDetail::updateOrCreate(
                [
                    'itinerary_template_id' => $this->template_id,
                    'header' => 'about_destination',
                    'field' => 'about_destination_image',
                ],
                [
                    'value' => asset($uploadedPath),
                ]
            );

            DB::commit();

            // Optionally reset the file input
             $this->loadAboutDestinationImage();
            session()->flash('success', 'Images uploaded successfully!');
            $this->reset('about_destination_image');
           
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to upload image: ' . $e->getMessage());
        }
    }

    public function ItineraryImageDelete($slider_image){
        DB::beginTransaction();
    
        try {
            $item = ItineraryTemplateDetail::where('itinerary_template_id', $this->template_id)
                ->where('value', $slider_image)
                ->first();
    
            if ($item) {
                // Delete file from storage
                $filePath = $item->value; // Example: "storage/itinerary/west-bangal-5280-20250312093144.jpg"

                // Check if file exists using correct path
                if (Storage::exists(str_replace('storage/', 'public/', $filePath))) {
                    Storage::delete(str_replace('storage/', 'public/', $filePath));
                }
    
                // Delete from database
                $item->delete();
    
                // Reload updated images list
                $this->loadDestinationImages();
                $this->loadGreatExperienceImage();
                $this->loadAboutDestinationImage();
    
                DB::commit();
                session()->flash('success', 'Image deleted successfully!');
            } else {
                session()->flash('error', 'Image not found in the database!');
            }
    
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Deletion failed: ' . $e->getMessage());
        }
    }
    public function UpdateByKeyUp($header, $field, $value){
        ItineraryTemplateDetail::updateOrCreate(
            [
                'itinerary_template_id' => $this->template_id,
                'header' => $header,
                'field' => $field,
            ],
            [
                'value' => $value,
            ]
        );
    }
    public function UpdateByKeyUpValue($header, $field, $value){
        ItineraryTemplateDetail::updateOrCreate(
            [
                'itinerary_template_id' => $this->template_id,
                'header' => $header,
                'field' => $field,
            ],
            [
                'value' => $value,
            ]
        );
    }
    public function UpdateByKeyUpDesc($header, $field, $value){
        ItineraryTemplateDetail::updateOrCreate(
            [
                'itinerary_template_id' => $this->template_id,
                'header' => $header,
                'field' => $field,
            ],
            [
                'description' => $value,
            ]
        );
    }
    public function render()
    {
        $this->mainBanner = ItineraryBanner::where('destination_id', $this->template->destination_id)->get();
        return view('livewire.itinerary.create-itinerary-template');
    }
}
