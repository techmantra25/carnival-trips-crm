<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\HotelImage;
use App\Models\HotelPriceChartType;
use App\Models\HotelSeasionTime;
use App\Models\HotelPriceChart;
use App\Helpers\CustomHelper;
use Illuminate\Support\Facades\DB;

class HotelRepository
{
    // Hotel
    // public function getAllHotel($limit = 10)
    // {
    //     $query = Hotel::orderBy('name', 'ASC');  // Start by ordering the cities
    //     // // dd($division);
    //     // // Apply division filter if provided
    //     // if (!empty($division)) {
    //     //     $query->where('name', 'like', "%{$division}%");
    //     // }

    //     // // Apply destination filter if provided
    //     // if (!empty($destination)) {
    //     //     $query->where('state', $destination);
    //     // }

    //     // Paginate the results
    //     $paginatedStates = $query->paginate($limit);  // $limit is the number of results per page

    //     // Get the total number of records (excluding pagination)
    //     $totalRecords = Hotel::orderBy('name', 'ASC')->get(); 

    //     // Return both the paginated results and the total record count
    //     return [
    //         'hotel' => $paginatedStates,
    //         'totalRecords' => $totalRecords,
    //     ];
    // }

    public function getAllHotel($limit = 10, $filters = [])
    {
        $query = Hotel::orderBy('name', 'ASC');

        if (!empty($filters['destination'])) {
            $query->where('destination', $filters['destination']);
        }

        if (!empty($filters['division'])) {
            $query->where('division', $filters['division']);
        }

        if (!empty($filters['hotel_category'])) {
            $query->where('hotel_category', $filters['hotel_category']);
        }
    
        if (!empty($filters['quick_search'])) {
            $quickSearch = $filters['quick_search'];
            $query->where(function ($q) use ($quickSearch) {
                $q->where('name', 'like', "%$quickSearch%")
                    // ->orWhere('phone_code', 'like', "%$quickSearch%")
                    ->orWhere('mobile_number', 'like', "%$quickSearch%")
                    ->orWhere('number_of_rooms', 'like', "%$quickSearch%")
                    ->orWhere('whatsapp_number', 'like', "%$quickSearch%")
                    ->orWhere('email1', 'like', "%$quickSearch%")
                    ->orWhere('email2', 'like', "%$quickSearch%");
            });
        }


        // Paginate results
        $paginatedHotels = $query->paginate($limit);
        // Return paginated hotels
        return [
            'hotels' => $paginatedHotels,
        ];
    }


    // Hotel Master
    public function storeHotel(array $data)
    {
        DB::beginTransaction(); // Start the transaction
        try {
            $hotel = new Hotel;
            $hotel->name = ucwords($data['name']);
            $hotel->destination = $data['destination'];
            $hotel->division = $data['division'];
            $hotel->hotel_category = $data['hotel_category'];
            $hotel->phone_code = "+91";
            $hotel->mobile_number = $data['mobile'];
            $hotel->whatsapp_number = $data['whatsapp'];
            $hotel->email1 = $data['email'];
            $hotel->email2 = $data['secndary_email'];
            $hotel->address = $data['address'];
            $hotel->number_of_rooms = 0; // Initialize
    
            $hotel->save();

            // Create New hotel Rooms
            // dd($data['rooms']);
            if (isset($data['rooms']) && is_array($data['rooms'])) {
                foreach ($data['rooms'] as $key => $roomData) {
                    $bed = (int) ($roomData['no_of_bed'] ?? 0);
                    $extra = (int) ($roomData['extra_bed'] ?? 0);
                    $mattress = (int) ($roomData['mattress'] ?? 0);
                    $capacity = (int) ($roomData['capacity'] ?? 0);

                    $room = new Room;
                    $room->room_category = $roomData['room'];
                    $room->room_type = $roomData['room_type'];
                    $room->room_name = ucfirst($roomData['room']) . ' - ' . ucfirst($roomData['room_type']);
                    $room->hotel_id = $hotel->id; // Associate with the newly created hotel
                    $room->no_of_rooms = $roomData['no_of_room'];
                    $room->no_of_beds = $bed;
                    $room->extra_bed = $extra;
                    $room->mattress = $mattress;
                    // $room->capacity = $bed + $extra + $mattress;
                    $room->capacity = $capacity;
                    $room->positions = $key+1;
                    
                      // Safely handle amenities (ensure it's an array)
                    $room->ammenities = isset($roomData['amenities']) ? implode(',', (array) $roomData['amenities']) : null;
                    $room->save();
                    
                  // Update hotel room count
                    $hotel->number_of_rooms += $roomData['no_of_room'];
                }
                
                $hotel->save(); // Save updated room count
            }

            $imageData = [];
         
            // Main Image
            if (isset($data['image'])) {
                // Generate a unique filename
                $dynamicText = rand(1111, 9999);
                $uploadedPath = CustomHelper::uploadImage($data['image'], 'hotel', $dynamicText, $hotel->name);
                $hotel->image = $uploadedPath;
                $hotel->save();
            }

            // Additional Images
            if (isset($data['images']) && is_array($data['images'])) {
              
                foreach ($data['images'] as $image) {
                    // Generate a unique filename
                    $dynamicText = rand(1111, 9999);
                    $uploadedPath = CustomHelper::uploadImage($image, 'hotel', $dynamicText, $hotel->name);
            
                    // Add to the batch insert array
                    $imageData[] = [
                        'hotel_id' => $hotel->id,
                        'image_path' => $uploadedPath,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            
                // Batch insert into the database
                if (!empty($imageData)) {
                    HotelImage::insert($imageData);
                }
            }
            
            DB::commit(); 
            return $hotel;
    
        } catch (\Exception $e) {
            DB::rollBack(); 
            // Log the exception for debugging
            \Log::error("Error storing hotel: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            throw $e; // Re-throw the exception to handle it in the calling code
        }
    }

    // public function updateHotel(Request $request,$id, array $data)
    public function updateHotel(array $data)
    {
        DB::beginTransaction(); // Start the transaction
        try {
            $hotel = Hotel::find($data['id']);
            $hotel->name = ucwords($data['name']);
            $hotel->destination = $data['destination'];
            $hotel->division = $data['division'];
            $hotel->hotel_category = $data['hotel_category'];
            $hotel->phone_code = "+91";
            $hotel->mobile_number = $data['mobile'];
            $hotel->whatsapp_number = $data['whatsapp'];
            $hotel->email1 = $data['email'];
            $hotel->email2 = $data['secndary_email'];
            $hotel->address = $data['address'];
            $hotel->number_of_rooms = 0; // Initialize
    
            $hotel->save();

            // Create New hotel Rooms
            // dd($data['rooms']);
            if (isset($data['rooms']) && is_array($data['rooms'])) {
                foreach ($data['rooms'] as $key => $roomData) {
                    $bed = (int) ($roomData['no_of_bed'] ?? 0);
                    $extra = (int) ($roomData['extra_bed'] ?? 0);
                    $mattress = (int) ($roomData['mattress'] ?? 0);
                    $capacity = (int) ($roomData['capacity'] ?? 0);

                    if($roomData['id']){
                        $room = Room::find($roomData['id']);
                    }else{
                         $room = new Room;
                    }
                    $room->room_category = $roomData['room'];
                    $room->room_type = $roomData['room_type'];
                    $room->room_name = ucfirst($roomData['room']) . ' - ' . ucfirst($roomData['room_type']);
                    $room->hotel_id = $hotel->id; // Associate with the newly created hotel
                    $room->no_of_rooms = $roomData['no_of_room'];
                    $room->no_of_beds = $bed;
                    $room->extra_bed = $extra;
                    $room->mattress = $mattress;
                    // $room->capacity = $bed + $extra + $mattress;
                    $room->capacity = $capacity;
                    $room->positions = $key+1;
                    
                      // Safely handle amenities (ensure it's an array)
                    $room->ammenities = isset($roomData['amenities']) ? implode(',', (array) $roomData['amenities']) : null;
                    $room->save();
                    
                  // Update hotel room count
                    $hotel->number_of_rooms += $roomData['no_of_room'];
                }
                
                $hotel->save(); // Save updated room count
            }

            $imageData = [];
         
            // Main Image
            if (isset($data['image'])) {
                // Generate a unique filename
                $dynamicText = rand(1111, 9999);
                $uploadedPath = CustomHelper::uploadImage($data['image'], 'hotel', $dynamicText, $hotel->name);
                $hotel->image = $uploadedPath;
                $hotel->save();
            }

            // Additional Images
            if (isset($data['images']) && is_array($data['images'])) {
              
                foreach ($data['images'] as $image) {
                    // Generate a unique filename
                    $dynamicText = rand(1111, 9999);
                    $uploadedPath = CustomHelper::uploadImage($image, 'hotel', $dynamicText, $hotel->name);
            
                    // Add to the batch insert array
                    $imageData[] = [
                        'hotel_id' => $hotel->id,
                        'image_path' => $uploadedPath,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            
                // Batch insert into the database
                if (!empty($imageData)) {
                    HotelImage::insert($imageData);
                }
            }
            
            DB::commit(); 
            return $hotel;
    
        } catch (\Exception $e) {
            DB::rollBack(); 
            // Log the exception for debugging
            \Log::error("Error storing hotel: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            throw $e; // Re-throw the exception to handle it in the calling code
        }
    }
    
    

    
    public function getHotelById($id)
    {
        return Hotel::with('rooms')->find($id);
    }

    public function deleteHotel($id){
        $hotel = Hotel::findOrFail($id);
        $hotel->delete();
        return $hotel;
    }
    public function RoomWisePriceStore(array $data)
    {
        foreach ($data['title'] as $key => $item) {
        // Create or update the price chart type
            $price_chart_type = HotelPriceChartType::updateOrCreate(
                [
                    'hotel_id' => $data['hotel_id'],
                    'room_id' => $data['room_id'],
                    'title' => $item,
                ],
                [
                    'rack_rate' => $data['rack_rate'][$key] ?? 0,
                    'gst' => $data['gst'][$key] ?? 0,
                    'type' => $data['chart_type'][$key],
                ]
            );
            // Process each plan_title and its corresponding plan_item
            foreach ($data['plan_title'][$key] as $plan_index => $plan_title) {
                $plan_items = $data['plan_item'][$key]; // Array of plan items
                $item_prices = $data['item_price'][$key]; // Corresponding prices
            
                foreach ($plan_items as $group_key => $plan_item_group) { // $plan_item_group is each inner array
                    // Check if item prices for the group key exist
                    if (!isset($item_prices[$group_key]) || !is_array($item_prices[$group_key])) {
                        return response()->json([
                            'success' => false,
                            'message' => "Item prices for group key '{$group_key}' are missing or invalid.",
                            'error' => 'Invalid data structure'
                        ], 400);
                    }
            
                    // Loop through the item prices using valid indices
                    // dd($item_prices[$group_key]);
                    foreach ($item_prices[$group_key] as $index => $price) {
                        foreach ($plan_item_group as $item_key => $plan_item) {
                            // Insert or update data
                            // dd($item_prices[$group_key][$item_key], $plan_item_group);
                            $insert = HotelPriceChart::updateOrCreate(
                                [
                                    'hotel_id' => $data['hotel_id'],
                                    'room_id' => $data['room_id'],
                                    'price_chart_type_id' => $price_chart_type->id,
                                    'plan_title' => $group_key,
                                    'plan_item' => $plan_item,
                                ],
                                [
                                    'item_price' => $item_prices[$group_key][$item_key],
                                ]
                            );
                        }
                    }
                }
            }
            
            
        }
        return true;
    }



}
