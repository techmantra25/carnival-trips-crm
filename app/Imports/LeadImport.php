<?php

namespace App\Imports;

use App\Models\Lead;
use App\Models\State;
use App\Models\Category;
use App\Models\LeadUrlShare;
use App\Models\LeadActivityLog;
use App\Models\Itinerary;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use App\Helpers\CustomHelper;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Shared\Date;



class LeadImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            foreach ($rows as $index => $row) {
                $rowData = $row->toArray();
             
                // Destination
                $destinationId = null;

                if (isset($rowData['travel_locationdestination'])) {
                    $existingDestination = State::firstOrCreate(
                        ['name' => $rowData['travel_locationdestination']], // Match by name
                        ['status' => 1,'country_code_id' => 2] // Set default values if creating
                    );

                    $destinationId = $existingDestination->id;
                }
                // Hotel Category
                $categoryId = null;
                if (isset($rowData['hotel_category'])) {
                    $existingCategory = Category::firstOrCreate(
                        ['name' => $rowData['hotel_category']], // Match by name
                        ['status' => 1] // Set default values if creating
                    );

                    $categoryId = $existingCategory->id;
                }

             
                try {
                    // Convert Excel serials to Carbon instances
                    $arrivalCarbon = Carbon::instance(Date::excelToDateTimeObject($row['arrival_date']));
                    $departureCarbon = Carbon::instance(Date::excelToDateTimeObject($row['departure_date']));

                    // Calculate total days
                    $totalNights = round($arrivalCarbon->diffInDays($departureCarbon)); // absolute difference

                    // Format dates
                    $arrivalDate = $arrivalCarbon->format('Y-m-d');
                    $departureDate = $departureCarbon->format('Y-m-d');

                } catch (\Exception $e) {
                    $name = $row['customer_name'] ?? 'Unknown';
                    $mobile = $row['customer_mobile'] ?? 'N/A';
                    $errorMsg = "Date error for lead of {$name} (Mobile: {$mobile}): " . $e->getMessage();
                    throw new \Exception($errorMsg);
                }
                $getNextTeamLeadId = CustomHelper::getNextTeamLeadIdByLeadCount($destinationId);
                $lead = Lead::create([
                    'unique_id'                => CustomHelper::GenerateUniqueId('leads', 'LTD'),
                    'generate_from'            => 'lead',
                    'nationality_type'         => $row['nationality_type'],
                    'customer_name'            => $row['customer_name'],
                    'customer_email'           => $row['customer_email'],
                    'customer_mobile'          => $row['customer_mobile'],
                    'country_code'             => $row['country_code'],
                    'customer_whatsapp'        => $row['customer_whatsapp'],
                    'travel_location'          => $destinationId,
                    'hotel_category'           => $categoryId,
                    'travel_duration'          => $totalNights+1,
                    'travel_in_days'           => $totalNights+1,
                    'travel_in_nights'         => $totalNights,
                    'travel_duration_text' => ($totalNights + 1) . 'D/' . $totalNights . 'N',
                    'arrival_date'             => $arrivalDate,
                    'departure_date'           => $departureDate,
                    'number_of_travellor'      => $row['number_of_travellor'],
                    'number_of_adults'         => $row['number_of_adults'],
                    'number_of_rooms'          => $row['number_of_rooms'],
                    'extra_child'              => $row['extra_child'] ?? 0,
                    'extra_mattress'           => $row['extra_mattress'] ?? 0,
                    'meal_type'                => $row['meal_type'],
                    'lead_type'                => $row['lead_type'],
                    'lead_source'              => $row['lead_source'],
                    'package_type'             => $row['package_type'],
                    'team_lead_id'             => $getNextTeamLeadId,
                    'created_by'               => Auth::guard('admin')->user()->id,
                ]);
                 if($lead){
                    $fetch = Itinerary::where('destination_id',$destinationId)->where('hotel_category',$categoryId)->where('total_days',$lead->travel_in_days)->first();
                    if($fetch){
                        $create  = new Itinerary;
                        $create->type = 'query';
                        $create->lead_id = $lead->id;
                        $create->destination_id = $fetch->destination_id;
                        $create->hotel_category  = $fetch->hotel_category;
                        $create->itinerary_syntax = $fetch->itinerary_syntax;
                        $create->total_days = $fetch->total_days;
                        $create->total_nights = $fetch->total_nights;
                        $create->night_journey = $fetch->night_journey;
                        $create->divisions_journey = $fetch->divisions_journey;
                        $create->stay_by_journey = $fetch->stay_by_journey;
                        $create->itinerary_journey = $fetch->itinerary_journey;
                        $create->save();
                    }else{
                        $create  = new Itinerary;
                        $create->type = 'query';
                        $create->lead_id = $lead->id;
                        $create->destination_id = $destinationId;
                        $create->hotel_category  = $categoryId;
                        $create->total_days = $lead->travel_in_days;
                        $create->total_nights = $lead->travel_in_nights;
                        $create->save();
                    }
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
                                'updated_by' => Auth::guard('admin')->user()->id,
                                'message' => json_encode([
                                    'action' => 'Itinerary Shared From Import',
                                    'shared_link_ids' => $LeadUrlShare?$LeadUrlShare->links:null,
                                    'shared_by' => Auth::guard('admin')->user()->name . ' (' . Auth::guard('admin')->user()->email . ')',
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
                                'action' => 'Itinerary Share Failed',
                                'reason' => 'No matching preset itinerary found',
                                'shared_by' => Auth::guard('admin')->user()->name . ' (' . Auth::guard('admin')->user()->email . ')',
                                'timestamp' => now()->toDateTimeString()
                            ])
                        ]);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lead import failed: ' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }
}
