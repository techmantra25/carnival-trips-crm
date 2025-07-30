<?php

namespace App\Helpers;
use App\Models\Hotel;
use App\Models\HotelPriceChart;
use App\Models\HotelPriceChartType;
use App\Models\HotelSeasionTime;
use App\Models\Inventory;
use App\Models\City;
use App\Models\RouteServiceSummary;
use App\Models\DateWiseHotelPrice;
use App\Models\DestinationSeasonPeriod;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Admin;
use App\Models\State;
use App\Models\Lead;
use App\Models\LeadUrlShare;
use App\Models\ItineraryTemplate;
use App\Models\LeadUrlClick;
use App\Models\Itinerary;

class CustomHelper
{
    public static function setHeadersAndTitle($parentHeader, $childHeader)
    {
        $pageTitle = 'Admin - ' . $parentHeader;
        
        return [
            'parentHeader' => $parentHeader,
            'childHeader' => $childHeader,
            'pageTitle' => $pageTitle,
        ];
    }
    public static function GenerateUniqueId($table, $prefix){
        // Get the current year
        $year = date('Y');

        // Get the latest unique ID from the specified table for the current year
        $latestUniqueId = \DB::table($table)
            ->where('unique_id', 'like', $prefix . $year . '%') // Filter by the prefix and current year
            ->orderBy('unique_id', 'desc') // Get the latest unique ID
            ->value('unique_id');

        // If there is no existing unique ID, start from 1
        if (!$latestUniqueId) {
            $nextId = 1;
        } else {
            // Extract the numeric part after the prefix and year and increment it
            $numericPart = intval(substr($latestUniqueId, strlen($prefix) + 4)); // 4 for 'YYYY'
            $nextId = $numericPart + 1; // Increment the numeric part
        }

        // Create the new unique ID with zero-padding
        $uniqueId = $prefix . $year . str_pad($nextId, 10, '0', STR_PAD_LEFT); // Adjust the padding as needed

        // Check if the generated ID already exists
        $exists = \DB::table($table)->where('unique_id', $uniqueId)->exists();

        // If the ID exists, we need to keep generating a new one (shouldn't happen with this logic)
        while ($exists) {
            $nextId++; // Increment to get a new numeric part
            $uniqueId = $prefix . $year . str_pad($nextId, 10, '0', STR_PAD_LEFT); // Generate a new unique ID
            $exists = \DB::table($table)->where('unique_id', $uniqueId)->exists(); // Check again
        }

        return $uniqueId;
    }
    
    public static function getLeadStatus($status)
    {
        $statuses = [
            1 => ['name' => 'Unattended', 'color' => 'bg-warning'],
            2 => ['name' => 'Follow-up', 'color' => 'bg-info'],
            3 => ['name' => 'Potential Lead', 'color' => 'bg-primary'],
            4 => ['name' => 'Confirmed Lead', 'color' => 'bg-success'],
            5 => ['name' => 'Package Executed', 'color' => 'bg-secondary'],
            6 => ['name' => 'Package Confirmed', 'color' => 'bg-success'],
            7 => ['name' => 'Cancelled', 'color' => 'bg-danger'],
            8 => ['name' => 'Hold', 'color' => 'bg-dark'],
            9 => ['name' => 'Close', 'color' => 'bg-success'],
        ];
    
        return $statuses[$status] ?? ['name' => 'Unknown Status', 'color' => 'bg-muted'];
    }
    public static function formatLeadTime($timestamp)
    {
        $createdAt = \Carbon\Carbon::parse($timestamp);
        $now = \Carbon\Carbon::now();

        $diffInMinutes = $createdAt->diffInMinutes($now);

        if ($diffInMinutes < 1440) {
            // Less than 1 day
            $hours = floor($diffInMinutes / 60);
            $minutes = $diffInMinutes % 60;

            $timeAgo = trim(
                ($hours > 0 ? "$hours hr" : '') .
                ' ' .
                ($minutes > 0 ? "$minutes min" : '')
            ) . ' ago';

        } else {
            $diffInDays = $createdAt->diffInDays($now);

            if ($diffInDays < 365) {
                // Between 1 and 364 days
                $roundedDays = round($diffInDays);
                $timeAgo = $roundedDays . ' day' . ($roundedDays > 1 ? 's' : '') . ' ago';
            } else {
                // 365+ days
                $diffInYears = round($diffInDays / 365);
                $timeAgo = $diffInYears . ' year' . ($diffInYears > 1 ? 's' : '') . ' ago';
            }
        }

        $formattedDate = $createdAt->format('d M Y h.i A');

        return [
            'formatted_date' => $formattedDate,
            'time_ago' => $timeAgo
        ];

    }

    public static function IncompleteHotelPriceChart($hotel_id, $room_id)
    {
        // Check if any incomplete data exists
        $dataCount = HotelPriceChart::where('hotel_id', $hotel_id)
            ->where('room_id', $room_id)
            ->whereNull('item_price')
            ->count();
    
        // Return false if any incomplete data exists or no data exists
        
        if ($dataCount > 0 || !HotelPriceChart::where('hotel_id', $hotel_id)->where('room_id', $room_id)->exists()) {
            return false;
        }
        // Return true if all data is complete
        return true;
    }
    public static function getHotelPriceChart($hotel_id, $room_id, $price_chart_type, $plan_title, $plan_item)
    {
        // dd($hotel_id, $room_id, $price_chart_type, $plan_title, $plan_item);
        // Fetch the price chart type
        $type = HotelPriceChartType::where('hotel_id', $hotel_id)
            ->where('room_id', $room_id)
            ->where('title', $price_chart_type)
            ->first();

        if ($type) {
            // Fetch the item prices based on the filters
            $dataPrices = HotelPriceChart::where('hotel_id', $hotel_id)
                ->where('room_id', $room_id)
                ->where('price_chart_type_id', $type->id)
                ->where('plan_title', $plan_title)
                ->where('plan_item', $plan_item)
                ->first();
            // Check if prices exist
            if ($dataPrices) {
                return [
                    'id' => $dataPrices->id,
                    'item_price' => intval($dataPrices->item_price)
                ];
            }
            return [
                'id' => null,
                'item_price' => ''
            ];
           
        }
        return [
            'id' => null,
            'item_price' => ''
        ];
    }
    public static function get_hotel_seasion_time($hotel_id, $seasion_type_id){
        $data = [];
        $hotel = Hotel::find($hotel_id);
        $item = DestinationSeasonPeriod::where('destination_id', $hotel->destination)
        ->where('season_type_id', $seasion_type_id)
        ->first(['start_date', 'end_date']); 
        $data['start_date']= $item?$item->start_date:null;
        $data['end_date']= $item?$item->end_date:null;
        return $data;
    }

    public static function GetSeason($destination_id,$monthDay){

        $data = DestinationSeasonPeriod::with('season')->where('destination_id', $destination_id)->whereRaw('? BETWEEN start_date AND end_date', [$monthDay])->first();
        $value = $data && $data->season ?$data->season->title:null;

        // If related season exists, return its title
        $value = $data && $data->season ? $data->season->title : null;

        return $value;
        // DestinationSeasonPeriod
    }
    public static function GetDateWiseHotelPrice($existing_price, $room_id, $item_title, $date){
         // Attempt to get the item_price from the database
        $item_price = DateWiseHotelPrice::where('room_id', $room_id)
        ->where('date', $date)
        ->where('item_title', $item_title)
        ->value('item_price');

        // Return the retrieved item_price or the existing price if null
        return intval($item_price ?? 0);
    }
    public static function GetDateWiseHotelInventory($hotel_id, $room_id, $date)
    {
        // Retrieve the inventory record from the database
        $inventory = Inventory::where('hotel_id', $hotel_id)
            ->where('room_id', $room_id)
            ->whereDate('date', $date)
            ->first(['total_unsold', 'block_request_type']);

        // Initialize default values if no record is found
        if (!$inventory) {
            return [
                'total_unsold' => 0,
                'block_request_type' => 0,
            ];
        }

        // Convert inventory object to an array
        $inventoryData = [
            'total_unsold' => $inventory->total_unsold,
            'block_request_type' => $inventory->block_request_type,
        ];

        // Adjust block_request_type while preserving total_unsold
        if ($inventory->block_request_type == 1) {
            $inventoryData['block_request_type'] = 1;
        } elseif ($inventory->block_request_type == 2) {
            $inventoryData['block_request_type'] = 2;
        } else {
            // Default case for block_request_type
            $inventoryData['block_request_type'] = 0;
        }
        return $inventoryData;
    }
    public static function GetDateWiseHotelAllInventory($hotel_id, $date)
    {
        // Retrieve the inventory record from the database
        $inventory = Inventory::where('hotel_id', $hotel_id)
            ->whereDate('date', $date)
            ->first(['total_unsold', 'block_request_type']);

        // Initialize default values if no record is found
        if (!$inventory) {
            return [
                'total_unsold' => 0,
                'block_request_type' => 0,
            ];
        }

        // Convert inventory object to an array
        $inventoryData = [
            'total_unsold' => $inventory->total_unsold,
            'block_request_type' => $inventory->block_request_type,
        ];

        // Adjust block_request_type while preserving total_unsold
        if ($inventory->block_request_type == 1) {
            $inventoryData['block_request_type'] = 1;
        } elseif ($inventory->block_request_type == 2) {
            $inventoryData['block_request_type'] = 2;
        } else {
            // Default case for block_request_type
            $inventoryData['block_request_type'] = 0;
        }
        return $inventoryData;
    }
    public static function GetHotelWiseMaxPrice($existing_price, $room_id, $item_title,$start_date,$end_date){
       
         // Attempt to get the item_price from the database
        $item_price = DateWiseHotelPrice::where('room_id', $room_id)
        ->where('item_title', $item_title)
        ->whereBetween('date', [$start_date, $end_date])
        ->max('item_price');
        // Return the retrieved item_price or the existing price if null
        return intval($item_price ?? $existing_price);
       
    }

    public static function uploadImage($image, $dynamicText=null, $divisionName=null, $folder)
    {
        // Validate the uploaded file
        if (!$image->isValid()) {
            throw new \Exception('Invalid image file.');
        }
    
        // Format division name and dynamic text for filename
        $formattedDivisionName = Str::slug($divisionName); // Convert to URL-friendly string
        $formattedDynamicText = Str::slug($dynamicText);   // Convert to URL-friendly string
    
        // Generate a unique filename with a do-while loop to ensure uniqueness
        $timestamp = now()->format('YmdHis');            // Generate a timestamp
        $extension = $image->getClientOriginalExtension(); // Get the original file extension
    
        // Folder path to check for existing files
        $folderPath = storage_path("app/public/{$folder}");
    
        // Start with an initial filename
        $uniqueFilename = "{$formattedDivisionName}-{$formattedDynamicText}-{$timestamp}.{$extension}";
    
        // Check if the file already exists, and regenerate the filename if necessary
        $counter = 1;  // Counter to append if file already exists
        do {
            // Check if the file exists in the folder
            if (file_exists("{$folderPath}/{$uniqueFilename}")) {
                // If it exists, modify the filename to make it unique
                $uniqueFilename = "{$formattedDivisionName}-{$formattedDynamicText}-{$timestamp}-{$counter}.{$extension}";
                $counter++; // Increment the counter for the next iteration
            } else {
                break; // If it doesn't exist, exit the loop
            }
        } while (true);
    
        // Ensure the folder exists with proper permissions
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0755, true); // Create the folder with 755 permissions
        }
    
        // Store the image in the specified folder
        $path = $image->storeAs($folder, $uniqueFilename, 'public');
    
        // Return the stored file path
        return 'storage/'.$path;
    }
    public static function DestinationWiseSeasonDate($season_id, $destination_id) {
        return DestinationSeasonPeriod::where('destination_id', $destination_id)
            ->where('season_type_id', $season_id)
            ->first(['start_date', 'end_date']); // Returns a single object or null
    }
    public static function formatNightJourney($night_journey_str, $divisions_journey_str)
    {
        $night_journey = explode(',', $night_journey_str);
        $divisions_journey = explode(',', $divisions_journey_str);

        $result = [];
        foreach ($night_journey as $index => $night) {
            $division_id = $divisions_journey[$index] ?? null;
            $division = City::find($division_id);
            $division_code = $division ? $division->code : 'N/A'; // Handle null cases
            $result[] = $night . 'N' . $division_code;
        }

        return implode('+', $result);
    }

    public static function formatDayJourney($divisions_journey){
        $counts = array_count_values($divisions_journey);
        $day_journey = [];
        $index = 1;
        foreach($counts as $id=>$day){
            $day_journey[$index] = [
                'id'=>(int)$id,
                'day'=>$day,
            ];
            $index++;
        }

        $result = [];

        foreach ($day_journey as $k => $journey) {
            $division_id = $journey['id'];
            $division = City::find($division_id);
            $division_code = $division ? $division->code : 'N/A'; // Handle null cases
            $result[] = $journey['day'] . 'N' . $division_code;
        }

        return implode('+', $result);
    }
    public static function RouteExistCheck($route_id, $destination_id){
        return !RouteServiceSummary::where('route_id', $route_id)->where('destination_id', $destination_id)
            ->exists();
    }
    public static function secure_encode_id($id){
        $key = substr(hash('sha256', config('app.key')), 0, 32);
        $iv = substr(hash('sha256', 'custom_iv'), 0, 16); // static IV to make it reversible

        $encrypted = openssl_encrypt($id, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');
    }
    
    public static function secure_decode_id($encrypted){
        $key = substr(hash('sha256', config('app.key')), 0, 32);
        $iv = substr(hash('sha256', 'custom_iv'), 0, 16);

        $encrypted = base64_decode(strtr($encrypted, '-_', '+/'));
        return openssl_decrypt($encrypted, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    }

    public static function getNextTeamLeadIdByLeadCount($destination_id): ?int
    {
        // Get all active team leads
        $teamLeads = Admin::where('role', 'team_lead')
        ->whereHas('assignDestination', function ($empDestination) use ($destination_id) {
            $empDestination->where('state_id', $destination_id);
        })
        ->pluck('id')
        ->toArray();
        if (empty($teamLeads)) {
            return null;
        }

        // Count leads created today
        $todayLeadCount = Lead::whereDate('created_at', Carbon::today())->count();
        
        // Assign based on modulus of current count
        $index = $todayLeadCount % count($teamLeads);
        return $teamLeads[$index];
    }
    public static function ordinal($number)
    {
            $suffix = 'th';
            if (!in_array($number % 100, [11, 12, 13])) {
                switch ($number % 10) {
                    case 1: $suffix = 'st'; break;
                    case 2: $suffix = 'nd'; break;
                    case 3: $suffix = 'rd'; break;
                }
            }
            return $number . $suffix;
    }
    public static function getLeadStatusBadgeColor($status){
        $status = strtolower(trim($status));

        return match (strtolower(trim($status))) {
            'verified lead'       => 'status-verified',
            'link generated'      => 'status-link-generated',
            'hot leads'           => 'status-hot',
            'active lead'         => 'status-active',
            'cnp'                 => 'status-cnp',
            'high intend lead'    => 'status-high-intend',
            'pipeline'            => 'status-pipeline',
            'negotiation'         => 'status-negotiation',
            'confirmed dead'      => 'status-dead',
            'lead cancelled'      => 'status-cancelled',
            'closed'              => 'status-closed',
            'hold'                => 'status-hold',
            default               => 'status-default',
        };
    }

    public static function sendItineraryLinkOnWhatsapp($shared_link_id){
        // dd($shared_link_id);
    }
    public static function sendItineraryLinkOnEmail($shared_link_id){
        // dd($shared_link_id);
    }
    public static function makePresetItineraryLink($lead_id)
    {
        $sharedLinkIds = []; // always return this

        $lead = Lead::find($lead_id);

        if (!$lead || !$lead->itinerary) {
            return $sharedLinkIds; // return empty array if lead or itinerary is missing
        }

        $ownItinerary = $lead->itinerary;

        $destinationSlug = $lead->destination
            ? Str::slug($lead->destination->name)
            : 'destination';

        $extraItinerary = Itinerary::where('destination_id', $ownItinerary->destination_id)
            ->whereIn('total_days', [
                $ownItinerary->total_days + 1,
                $ownItinerary->total_days + 2
            ])
            ->where('id', '!=', $ownItinerary->id)
            ->where('hotel_category', $ownItinerary->hotel_category)
            ->where('type', 'preset')
            ->first();

        $itinerariesToShare = [
            'own'   => $ownItinerary,
            'extra' => $extraItinerary,
        ];

        DB::beginTransaction();

        try {
            foreach ($itinerariesToShare as $key => $itinerary) {
                if (!$itinerary) continue;

                $leadJourneySlug = $lead->travel_in_days . 'day-' . $lead->travel_in_nights . 'nights';

                $leadUrlShare = new LeadUrlShare();
                $leadUrlShare->lead_id = $lead->id;
                $leadUrlShare->itinerary_id = $itinerary->id;
                $leadUrlShare->save();

                $secureId = self::secure_encode_id($leadUrlShare->id);
                $leadUrlShare->links = asset($destinationSlug . '/' . $leadJourneySlug . '/' . $secureId);
                $leadUrlShare->save();
                $sharedLinkIds[$key] = $leadUrlShare->id;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Log the error if needed
        }
        return $sharedLinkIds; // always return array, empty or filled
    }

    public static function getLeadUrlClickStatsByLeadId($lead_id){
        $clicks = DB::table('lead_url_clicks')
        ->where('lead_id', $lead_id)
        ->select('lead_url_share_id', 'entry_time', 'exit_time', 'click_count')
        ->get();

        $grouped = $clicks->groupBy('lead_url_share_id');

        $stats = [];

        foreach ($grouped as $shareId => $records) {
            $totalClicks = 0;
            $totalSeconds = 0;

            foreach ($records as $record) {
                $totalClicks += (int) $record->click_count;

                if ($record->entry_time && $record->exit_time) {
                    $entry = \Carbon\Carbon::parse($record->entry_time);
                    $exit = \Carbon\Carbon::parse($record->exit_time);

                    $diff = strtotime($exit) - strtotime($entry);
                    $totalSeconds += $diff;
                }
            }

            $stats[] = [
                'lead_url_share_id' => $shareId,
                'total_clicks' => $totalClicks,
                'total_time_spent_in_seconds' => $totalSeconds,
                'time_spent_readable' => gmdate("H:i:s", $totalSeconds),
            ];
        }

        return $stats;
    }

    
}
