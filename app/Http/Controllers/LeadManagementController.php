<?php

namespace App\Http\Controllers;

use App\Repositories\LeadRepository;
use App\Repositories\CommonRepository;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Helpers\CustomHelper;

class LeadManagementController extends Controller
{
    protected $leadRepository;
    protected $commonRepository;

    public function __construct(LeadRepository $leadRepository, CommonRepository $commonRepository)
    {
        $this->leadRepository = $leadRepository;
        $this->commonRepository = $commonRepository;
    }

    public function index(Request $request)
    {
        return view('admin.leads.index');
    }
    public function confirmed(Request $request)
    {
        return view('admin.leads.confirmed');
    }
    public function lead_log_history($lead_id){
        $common = CustomHelper::setHeadersAndTitle('Lead Management', 'Log History');

        $lead = Lead::findOrFail($lead_id); // Fetch lead with name/email

        return view('admin.leads.lead_log_history', compact('lead_id', 'common', 'lead'));
    }
    public function lead_shared_history($lead_id)
    {
        $lead = Lead::findOrFail($lead_id); // Fetch lead with name/email
        $common = CustomHelper::setHeadersAndTitle('Lead Management', 'Itinerary Sharing History');
        return view('admin.leads.lead_shared_history', compact('lead_id', 'common','lead'));
    }
    public function trip_preference_data($lead_id)
    {
        $lead = Lead::findOrFail($lead_id); // Fetch lead with name/email
        $common = CustomHelper::setHeadersAndTitle('Lead Management', 'Trip Preference Form');
        return view('admin.leads.trip_preference_data', compact('lead_id', 'common','lead'));
    }

    // Additional methods for create, store, show, edit, update, and delete
    public function create(){
        // $cities = $this->commonRepository->getAllActiveCity();
        $destination = $this->commonRepository->getAllActiveState();
        $common = CustomHelper::setHeadersAndTitle('Lead Management', 'Create Lead');
        $UniqueId = CustomHelper::GenerateUniqueId('leads', 'LTD');
        return view('admin.leads.create', compact('common', 'UniqueId', 'destination'));
    }
    public function store(Request $request){
        // dd($request->all());
        $validatedData = $request->validate([
            'unique_id' => 'required|string|unique:leads,unique_id|max:255',
            'customer_name' => 'required|string|max:255',

            'days' => 'nullable|integer',
            'nights' => 'nullable|integer',
            'duration_text' => 'nullable|string|max:255',

            'customer_email' => 'required|email|max:255',
            'customer_mobile' => 'required|digits:10', // Use 'digits' for exact length
            'customer_whatsapp' => 'required|digits:10', // Use 'digits' for exact length
            'travel_location' => 'required|string|max:255',
            'travel_duration' => 'required|string|max:255',
            'arrival_date' => 'required|date', // Use 'date' validation if this is a date field
            'number_of_adults' => 'required|integer|min:1', // Use 'integer' and set 'min' and 'max' for range
            'number_of_children' => 'nullable|integer', // Use 'integer' and set 'min' and 'max' for range
        ], [
            'travel_duration.required' => 'Please select a travel duration.',
            'customer_name.required' => 'Customer name is required.',
            'customer_mobile.digits' => 'Customer mobile must be exactly 10 digits.',
            'customer_whatsapp.digits' => 'Customer WhatsApp number must be exactly 10 digits.',
            'arrival_date.date' => 'The travel date must be a valid date.',
            'number_of_adults.integer' => 'The number of adults must be an integer.',
            'number_of_children.integer' => 'The number of children must be an integer.',
            // Add additional custom messages as needed
        ]);
        try {
            $validatedData['user_id'] = $request->user_id;
            $validatedData['source_type'] = $request->source_type;
            $validatedData['lead_source'] = $request->lead_source;

            $this->leadRepository->createLead($validatedData);
            return redirect()->route('admin.leads.index')->with('success', 'Lead created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function update_status(Request $request){
        $data = $request->except('_token');
        // dd($data);
        try {
            $this->leadRepository->updateLeadStatus($data);
            return redirect()->back()->with('success', 'Lead status updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    function whatsapp_campaign_index(){
        $common = CustomHelper::setHeadersAndTitle('Campaign Management', 'WhatsApp Campaign');
        return view('admin.leads.whatsapp_campaign_index', compact('common'));
    }
}

