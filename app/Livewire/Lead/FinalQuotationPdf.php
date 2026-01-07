<?php

namespace App\Livewire\Lead;

use Livewire\Component;
use App\Models\SendedLeadItinerary;
use Illuminate\Support\Facades\Request;
use App\Models\SendedLeadItineraryDetail;
use App\Models\ItineraryTemplate;
use App\Models\ItineraryTemplateDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
class FinalQuotationPdf extends Component
{
    public $sent_lead_itinerary,$leadData,$total_amount,$destinationName;
    public $first_page_bg_image;
    public $second_page_bg_image;
    public $third_page_bg_image;
    public $itinerary = [];
    public function mount($code){
       $this->sent_lead_itinerary = SendedLeadItinerary::where('itinerary_code', $code)
        ->firstOrFail();
        $this->leadData = $this->sent_lead_itinerary->lead;

        $destinationTemplate = ItineraryTemplate::where('destination_id', $this->leadData->travel_location)->first();
        $this->destinationName = optional($this->leadData->destination)->name ?? 'this destination';
        if(!$destinationTemplate){
            session()->flash(
                'text_error',
                "Please setup itinerary template for {$this->destinationName}."
            );
        }
        $templateFirstImage = ItineraryTemplateDetail::where('itinerary_template_id', $destinationTemplate->id)->where('header', 'banner_section')->where('field', 'main_banner')->first();
        $this->first_page_bg_image = $templateFirstImage?$templateFirstImage->value:"assets/final_quotation_images/logo8.jpg";

        $templateSecondImage = ItineraryTemplateDetail::where('itinerary_template_id', $destinationTemplate->id)->where('header', 'great_experience')->where('field', 'great_experience_image')->first();
        $this->second_page_bg_image = $templateSecondImage?$templateSecondImage->value:asset('assets/final_quotation_images/logo9.jpg');

        $templatethirdImage = ItineraryTemplateDetail::where('itinerary_template_id', $destinationTemplate->id)->where('header', 'about_destination')->where('field', 'about_destination_image')->first();
        $this->third_page_bg_image = $templatethirdImage?$templatethirdImage->value:asset('assets/final_quotation_images/logo9.jpg');

        $this->itinerary = [
            'name' => $this->sent_lead_itinerary->lead->customer_name ?? 'N/A',
            'mobile' => isset($this->sent_lead_itinerary->lead) 
            ? $this->sent_lead_itinerary->lead->country_code . $this->sent_lead_itinerary->lead->customer_mobile
            : 'N/A',

            'email' => $this->sent_lead_itinerary->lead->customer_email ?? 'N/A',
            'travel_dates' => Carbon::parse($this->sent_lead_itinerary->lead->arrival_date)->format('d M Y') . 
                            ' to ' . 
                            Carbon::parse($this->sent_lead_itinerary->lead->departure_date)->format('d M Y'),
            'travel_duration' => $this->sent_lead_itinerary->lead->travel_duration_text ?? 'N/A',
            'arrival_date' => Carbon::parse($this->sent_lead_itinerary->lead->arrival_date)->format('d M Y'),
            'departure_date' => Carbon::parse($this->sent_lead_itinerary->lead->departure_date)->format('d M Y'),
            'number_of_adults' => $this->sent_lead_itinerary->lead->number_of_adults ?? 0,
            'number_of_children' => $this->sent_lead_itinerary->lead->number_of_children ?? 0,
            'children_data' => json_decode($this->sent_lead_itinerary->lead->children_data, true) ?? [],
            'number_of_travellor' => $this->sent_lead_itinerary->lead->number_of_travellor ?? 0,
            'number_of_rooms' => $this->sent_lead_itinerary->lead->number_of_rooms ?? 0,
            'extra_mattress' => $this->sent_lead_itinerary->lead->extra_mattress ?? 'N/A',
            'meal_type' => $this->sent_lead_itinerary->lead->meal_type ?? 'N/A',
            'destination' => $this->sent_lead_itinerary->destination->name ?? 'N/A',
            'inclusions' => SendedLeadItineraryDetail::where('sended_lead_itinerary_id', $this->sent_lead_itinerary->id)
                            ->where('field', 'like', 'inclusions_%')
                            ->where('header', 'inclusion_exclusions')
                            ->pluck('value')
                            ->toArray(),
            'exclusions' => SendedLeadItineraryDetail::where('sended_lead_itinerary_id', $this->sent_lead_itinerary->id)
                ->where('field', 'like', 'exclusions_%')
                ->where('header', 'inclusion_exclusions')
                ->pluck('value')
                ->toArray(),
            'trip_highligts' => SendedLeadItineraryDetail::where('sended_lead_itinerary_id', $this->sent_lead_itinerary->id)
                ->where('field', 'like', 'trip_highlights_%')
                ->where('header', 'about_destination')
                ->pluck('value')
                ->toArray(),
        ];


    }
    
    public function render()
    {
        $this->total_amount = SendedLeadItineraryDetail::where('sended_lead_itinerary_id', $this->sent_lead_itinerary->id)->sum('price');
        return view('livewire.lead.final-quotation-pdf');
    }
}
