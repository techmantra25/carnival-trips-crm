<?php

namespace App\Http\Controllers;
use App\Models\ItineraryBanner;
use App\Models\Itinerary;
use App\Models\ItineraryTemplate;
use App\Models\Category;
use App\Models\Lead;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\CustomHelper;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;


class ItineraryController extends Controller
{
    public function banners(){
        $common = CustomHelper::setHeadersAndTitle('Itinerary Management', 'Division Wise Banners');
        return view('admin.itinerary.division-wise-banners', compact('common'));
    }
    public function DestinationWisePresetItineraryList($lead_id = null){
        $header = "Preset Itinerary";
        if($lead_id){
            $header = "Build Itinerary";
        }
      
        $common = CustomHelper::setHeadersAndTitle('Itinerary Management', $header);
        return view('admin.itinerary.destination-wise-preset-itinerary-list', compact('common','lead_id'));
    }
    public function CostCalculationQueries(){
        $header = "Queries";
        $common = CustomHelper::setHeadersAndTitle('Cost Calculator', $header);
        return view('admin.itinerary.cost-calculator-query-list', compact('common'));
    }
    public function CostCalculationQueryEdit($encryptedLeadId){
         try {
            $id = Crypt::decrypt($encryptedLeadId);
            $itineraryExists = Itinerary::find($id);
            if (!$itineraryExists) {
                abort(404, 'Query not found.');
            }
    
            $header = "Query Update";
            $common = CustomHelper::setHeadersAndTitle('Cost Calculator', $header);
            if($itineraryExists->lead && $itineraryExists->lead->generate_from=="lead"){
                $back_url = route('admin.leads.index');
            }else{
                $back_url = route('admin.cost_calculator.query_list');
            }
                
            return view('admin.itinerary.cost-calculator-query-edit', compact('common','back_url','id'));
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(403, 'Invalid request.');
        }
       
    }
     public function CostCalculationPDF(){
        $header = "Queries PDF";
        $common = CustomHelper::setHeadersAndTitle('Cost Calculator', $header);
        return view('admin.itinerary.cost-calculator-query-pdf', compact('common'));
    }
    public function downloadTablePdf(){
        // $header = "Queries";
        // $common = CustomHelper::setHeadersAndTitle('Cost Calculator', $header);
        
        // Load the full view and capture only the table section
        $html = view('admin.itinerary.cost-calculator-query-pdf')->render();

        // Use DOMDocument to extract the specific table content by ID
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true); // Suppress HTML parsing warnings
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        // Extract the content of the table with ID 'print-section'
        $tableContent = '';
        $element = $dom->getElementById('print-section');
        if ($element) {
            $tableContent = $dom->saveHTML($element);
        }

        // If table content is empty, show an error message
        if (empty($tableContent)) {
            return redirect()->back()->with('error', 'Table with ID "print-section" not found.');
        }

        // Load the captured table content into the PDF
        $pdf = Pdf::loadHTML($tableContent)
                ->setPaper('A4', 'portrait');

        // Direct download
        return $pdf->download('table-content.pdf');
    }

    public function DestinationWisePostLeadItineraryList(){
        $common = CustomHelper::setHeadersAndTitle('Itinerary Management', 'Post Lead Itinerary');
        return view('admin.itinerary.destination-wise-post-lead-itinerary-list', compact('common'));
    }

    public function DestinationWisePresetItineraryBuilder($encryptedId){
        try {
            $id = Crypt::decrypt($encryptedId);
            $itineraryExists = Itinerary::find($id);
            if (!$itineraryExists) {
                abort(404, 'Itinerary not found.');
            }
    
            if ($itineraryExists->type == "preset") {
                $common = CustomHelper::setHeadersAndTitle('Itinerary Management', 'Build Preset Itinerary');
            } else {
                $common = CustomHelper::setHeadersAndTitle('Itinerary Management', 'Customize Post-Lead Itinerary');
            }
            return view('admin.itinerary.destination-wise-preset-itinerary-builder', compact('common','itineraryExists', 'id'));
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(403, 'Invalid request.');
        }
    }
    public function QueryItineraryCustomization($encryptedId){
        try {
            $id = Crypt::decrypt($encryptedId);
            $itineraryExists = Itinerary::find($id);
            if (!$itineraryExists) {
                abort(404, 'Itinerary not found.');
            }
    
            $common = CustomHelper::setHeadersAndTitle('Itinerary Management', 'Customize Itinerary');
            return view('admin.itinerary.query-itinerary-builder', compact('common','itineraryExists', 'id'));
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(403, 'Invalid request.');
        }
    }
    public function LeadItineraryCustomization(){
        try {
            $common = CustomHelper::setHeadersAndTitle('Itinerary Management', 'Customize Post-Lead Itinerary');
            return view('admin.itinerary.lead-customization-itinerary', compact('common'));
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(403, 'Invalid request.');
        }
    }
    

    public function DestinationWiseItineraryTemplate(){
        $common = CustomHelper::setHeadersAndTitle('Itinerary Management', 'Itinerary Templates');
        return view('admin.itinerary.destination-wise-itinerary-template-list', compact('common'));
    }

    public function DestinationWiseItineraryTemplateBuilder($encryptedId){
        try {
            $id = Crypt::decrypt($encryptedId);
            $itineraryExists = ItineraryTemplate::find($id);
    
            if (!$itineraryExists) {
                abort(404, 'Itinerary template not found.');
            }
    
            $common = CustomHelper::setHeadersAndTitle('Itinerary Management', 'Itinerary Template');
            return view('admin.itinerary.destination-wise-itinerary-template-builder', compact('common','itineraryExists', 'id'));
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(403, 'Invalid request.');
        }
    }
}
