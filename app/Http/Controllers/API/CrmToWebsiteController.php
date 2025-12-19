<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\City;
use App\Models\DivisionWiseActivity;
use App\Models\CountryCode;
use App\Models\NonServicesHotel;
use App\Models\Itinerary;
use App\Models\Category;
use App\Models\Lead;
use App\Models\LeadUrlShare;
use App\Models\Admin;
use App\Models\LeadActivityLog;
use App\Helpers\CustomHelper;
use App\Models\ItineraryDetail;
use App\Models\DivisionWiseCab;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class CrmToWebsiteController extends Controller
{
    public function countries(){
        $data = CountryCode::where('status', 1)->orderBy('country_name', 'ASC')->get();
        if (count($data)==0) {
            return response()->json([
                'status' => false,
                'message' => 'country not found.',
            ], 404); // 404 Not Found
        }
    
        // Return the user profile data
        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => 'country retrieved successfully.',
        ], 200);
    }
    
    public function active_destination_wise_itinerary($destination_id){
        $data = Itinerary::where('type', 'preset')->orderBy('total_days', 'DESC')->where('destination_id',$destination_id)->get();
        if (count($data)==0) {
            return response()->json([
                'status' => false,
                'message' => 'itinerary not found.',
            ], 404); // 404 Not Found
        }
    
        // Return the user profile data
        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => 'Itinerary retrieved successfully.',
        ], 200);
    }
    
    public function active_divisions(Request $request){
        
        $division_ids = $request->json('division_ids');
        \Log::info('Raw content:', [$division_ids]);
        
        if (!is_array($division_ids) || count($division_ids) === 0) {
            return response()->json([
                'status' => false,
                'message' => 'division_ids must be a non-empty array.'
            ], 422);
        }
        
        // Fetch all cities by unique IDs
        $cities = City::whereIn('id', array_unique($division_ids))
            ->pluck('name', 'id') // [id => name]
            ->toArray();
    
        // Rebuild response with both id and name
        $mappedDivisions = array_map(function ($id) use ($cities) {
            return [
                'id' => $id,
                'name' => $cities[$id] ?? 'Unknown'
            ];
        }, $division_ids);
        
        if (count($mappedDivisions)==0) {
            return response()->json([
                'status' => false,
                'message' => 'divisions not found.',
            ], 404); // 404 Not Found
        }
    
        // Return the user profile data
        return response()->json([
            'status' => true,
            'data' => $mappedDivisions,
            'message' => 'Division retrieved successfully.',
        ], 200);
    }
    public function country_wise_active_destinations($country_id){
        $data = State::where('status', 1)->orderBy('name', 'ASC')->where('country_code_id',$country_id)->get();
        if (count($data)==0) {
            return response()->json([
                'status' => false,
                'message' => 'destinations not found.',
            ], 404); // 404 Not Found
        }
    
        // Return the user profile data
        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => 'Destinations retrieved successfully.',
        ], 200);
    }
    public function active_category($category_id){
        $data = Category::where('status', 1)->where('id', $category_id)->first();
        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'category not found.',
            ], 404); // 404 Not Found
        }
    
        // Return the user profile data
        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => 'categories retrieved successfully.',
        ], 200);
    }
    public function destination_wise_active_activities($destination_id){
        $division = City::where('state_id', $destination_id)->pluck('id')->all();
        $data = DivisionWiseActivity::whereIn('division_id', $division)->orderBy('name', 'ASC')->select('id','name')->get();
        if (count($data)==0) {
            return response()->json([
                'status' => false,
                'message' => 'activities not found.',
            ], 404); // 404 Not Found
        }
    
        // Return the user profile data
        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => 'Activity retrieved successfully.',
        ], 200);
    }
    public function division_wise_active_cabs($division_id)
    {
        // Eager load cab data
       $cabs = DivisionWiseCab::with(['cab:id,title'])
        ->where('division_id', $division_id)
        ->get()
        ->filter(function ($cab) {
            return $cab->cab; // ensure cab relation exists
        })
        ->map(function ($cab) {
            return [
                'id' => $cab->cab->id,
                'name' => $cab->cab->title,
            ];
        })
        ->values(); // reset keys
    
        if ($cabs->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Cabs not found.',
            ], 404);
        }
    
        return response()->json([
            'status' => true,
            'data' => $cabs,
            'message' => 'Cabs retrieved successfully.',
        ], 200);
    }
    public function division_wise_non_service_hotels($division_id)
    {
        // Eager load cab data
       $hotels = NonServicesHotel::where('city_id', $division_id)
        ->get()
        ->map(function ($hotel) {
            return [
                'id' => $hotel->id,
                'name' => $hotel->hotel_name,
                'address' => $hotel->address,
                'image' => asset($hotel->image), // Full path
            ];
        })
        ->values(); // reset keys
    
        if ($hotels->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Hotels not found.',
            ], 404);
        }
    
        return response()->json([
            'status' => true,
            'data' => $hotels,
            'message' => 'Hotels retrieved successfully.',
        ], 200);
    }

    public function lead_store(Request $request)
    {
        // Check if 'crm_itinerary_id' exists and is not null
        if (!$request->has('crm_itinerary_id') || empty($request->crm_itinerary_id)) {
            return response()->json([
                'status' => false,
                'message' => 'crm_itinerary_id is required.',
            ], 400);
        }

        $Itinerary = Itinerary::find($request->crm_itinerary_id);
        if (!$Itinerary) {
            return response()->json([
                'status' => false,
                'message' => 'Sorry, Itinerary not found.',
            ], 404);
        }

        DB::beginTransaction();

        try {
            $lead = new Lead();
            $lead->unique_id = CustomHelper::GenerateUniqueId('leads', 'LTD');
            $lead->customer_name = ucwords($request->customerName);
            $lead->customer_email = $request->email;
            $lead->generate_from = 'lead';
            $lead->customer_mobile = $request->whatsapp;
            $lead->country_code = '91';
            $lead->customer_whatsapp = $request->whatsapp;

            $lead->travel_location = $Itinerary->destination_id;
            $lead->travel_duration = $Itinerary->total_days;
            $lead->travel_in_days = $Itinerary->total_days;
            $lead->travel_in_nights = $Itinerary->total_nights;
            $lead->travel_duration_text = $Itinerary->itinerary_syntax;
            $lead->hotel_category = $Itinerary->hotel_category;

            $lead->arrival_date = $request->startDate;
            $lead->departure_date = $request->endDate;

            $lead->number_of_adults = $request->travellers;
            $lead->number_of_travellor = $request->travellers;

            $lead->lead_source = "Others";
            $calculatedRooms = round($request->travellers / 2);
            $lead->number_of_rooms = max(1, $calculatedRooms);
            $lead->meal_type = "CP";
            $lead->team_lead_id = CustomHelper::getNextTeamLeadIdByLeadCount($Itinerary->destination_id);
            $lead->assigned_to_id = $lead->team_lead_id;
            $lead->save();

            // Create new Itinerary linked to lead
            $create = new Itinerary;
            $create->type = 'query';
            $create->lead_id = $lead->id;
            $create->destination_id = $Itinerary->destination_id;
            $create->hotel_category = $Itinerary->hotel_category;
            $create->itinerary_syntax = $Itinerary->itinerary_syntax;
            $create->total_days = $Itinerary->total_days;
            $create->total_nights = $Itinerary->total_nights;
            $create->night_journey = $Itinerary->night_journey;
            // $create->divisions_journey = $Itinerary->divisions_journey;
            $create->stay_by_journey = $Itinerary->stay_by_journey;
            $create->itinerary_journey = $Itinerary->itinerary_journey;
            $create->save();


            // Send Itinerary
            $SharedIds =  CustomHelper::makePresetItineraryLink($lead->id);
            if(count($SharedIds)>0){
                foreach($SharedIds as $link_index => $linkItem){
                    $LeadUrlShare = LeadUrlShare::find($linkItem);

                    CustomHelper::sendItineraryLinkOnWhatsapp($linkItem);
                    CustomHelper::sendItineraryLinkOnEmail($linkItem);
                
                    //  Log success
                    LeadActivityLog::create([
                        'lead_id' => $lead->id,
                        'updated_by' => Admin::where('role', 'super_admin')->value('id'),
                        'message' => json_encode([
                            'action' => 'Itinerary Shared Website Lead',
                            'shared_link_ids' => $LeadUrlShare?$LeadUrlShare->links:null,
                            'shared_by' => Admin::where('role', 'super_admin')->value('id'),
                            'timestamp' => now()->toDateTimeString()
                        ])
                    ]);
                }

            }else{
                //  Log failure
                LeadActivityLog::create([
                    'lead_id' => $lead->id,
                    'updated_by' => Auth::guard('admin')->user()->id,
                    'message' => json_encode([
                        'action' => 'Itinerary Share Failed Website Lead',
                        'reason' => 'No matching preset itinerary found',
                        'shared_by' => Admin::where('role', 'super_admin')->value('id'),
                        'timestamp' => now()->toDateTimeString()
                    ])
                ]);
            }
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Lead successfully created.',
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while saving lead.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function itinerary_divisions($itinerary_id){
        $Itinerary = Itinerary::find($itinerary_id);
        if (!$Itinerary) {
            return response()->json([
                'status' => false,
                'message' => 'Sorry, Itinerary not found.',
            ], 404);
        }
        $divisions_journeies = [];
        $division_ids = explode(',', $Itinerary->stay_by_journey);
        $night_journeys = explode(',', $Itinerary->night_journey);
        
        $map = [];
        
        foreach ($division_ids as $i => $value) {
            $index = $i + 1; // 1-based index
        
            if (!isset($map[$value])) {
                $map[$value] = [
                    'city' => $value,
                    'count' => 0,
                    'index' => [],
                ];
            }
        
            $map[$value]['count']++;
            $map[$value]['index'][] = $index;
        }
        
        $division_data = array_values($map);
        
        // Add one more index with static value '1' for $night_journeys
        
        // dd($division_data, $night_journeys);

        foreach($division_data as $key=>$sivision_item){
            $cityData = City::with('itinerary_banner')->find($sivision_item['city']);
            if($cityData){
                 $divisions_journeies[$key] = [
                    "city" => $cityData->name,
                    "day" => $sivision_item['count'],
                    "day_index" => $sivision_item['index'],
                    "banners" => $cityData->itinerary_banner->pluck('image')->map(function ($img) {
                        return asset($img);
                    })->toArray(),
                ];
            }
        }
        return response()->json([
            'status' => true,
            'data' => $divisions_journeies,
            'message' => 'Data Fetch.',
        ], 200);
    }
    public function itinerary_inclusion_exclusion($itinerary_id){

        $Itinerary = Itinerary::find($itinerary_id);
        if (!$Itinerary) {
            return response()->json([
                'status' => false,
                'message' => 'Sorry, Itinerary not found.',
            ], 404);
        }
        $data['inclusions'] = ItineraryDetail::where('itinerary_id', $itinerary_id)->where('header', 'inclusion_exclusions')->where('field', 'like', 'inclusions_%')->pluck('value')->toArray();

        $data['exclusions'] = ItineraryDetail::where('itinerary_id', $itinerary_id)->where('header', 'inclusion_exclusions')->where('field', 'like', 'exclusions_%')->pluck('value')->toArray();

        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => 'Data fetch',
        ], 200);
    }


}
