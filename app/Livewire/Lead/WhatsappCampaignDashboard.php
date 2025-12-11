<?php

namespace App\Livewire\Lead;

use Livewire\Component;
use App\Models\LeadWhatsappMessage;
use App\Models\WhatsappMessageTemplate;
use Illuminate\Support\Facades\Auth;
use App\Models\Lead;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Http;

class WhatsappCampaignDashboard extends Component
{
    public $templates,$template_search, $search = '';
    public $messages,$authUser,$selectedTemplateId = null,$selectedCustomerType = 'all';
    public $leads = [
        'allLeads' => [],
        'activeLeads' => [],
        'hotLeads' => [],
        'cnpLeads' => [],
    ];
    public $previewTemplate;
    public $messageType = 'preset';
    public $selectedCustomer = [], $selectedLeads = []; // store selected lead IDs

    public function mount()
    {
        $this->authUser = Auth::guard('admin')->user();
        $this->messages = LeadWhatsappMessage::where('sent_by', $this->authUser->id)->get();
        $this->collectLead();
        $this->getAllTemplates();
    }

    public function getAllTemplates()
    {
        $url = config('whatsapp.domain') . "/api/" . config('whatsapp.version') . "/templates";
        $apiKey = config('whatsapp.api_key');
    
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$apiKey}",
                "Accept: application/json"
            ],
        ]);

        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            dd("cURL Error: " . curl_error($ch));
        }

        curl_close($ch);
        // Convert to array
      
       $this->templates = collect(json_decode($response, true))
        ->filter(function ($tpl) {
            return isset($tpl['Status']) && $tpl['Status'] === 'APPROVED';
        })
        ->map(function ($tpl) {
            return [
                'Id'         => $tpl['Id'] ?? null,
                'name'       => $tpl['name'] ?? '',
                'category'   => $tpl['category'] ?? '',
                'Status'     => $tpl['Status'] ?? '',
                'language'   => $tpl['language'] ?? '',
                'components' => $tpl['components'] ?? [],
            ];
        })
        ->values()   // reset array keys
        ->toArray();

    // dd($this->templates);

    
    }

    public function collectLead(){
        $leads = Lead::when(in_array($this->authUser->role, ['team_lead', 'member']), function ($query) {
            if ($this->authUser->role === 'team_lead') {
                $query->where('team_lead_id', $this->authUser->id);
            } elseif ($this->authUser->role === 'member') {
                $query->where('assigned_to_id', $this->authUser->id);
            }
        })
        ->where('customer_whatsapp', '!=', null) // Ensure whatsapp number is not null
        ->where('generate_from', 'lead')
        ->orderBy('id', 'DESC')
        ->get();
        $allLeads = $leads;
        $activeLeads = $leads->where('status', 'Active Lead');
        $hotLeads = $leads->where('status', 'Hot Leads');
        $cnpLeads = $leads->where('status', 'CNP');
        
        $this->leads = [
            'allLeads' => $allLeads,
            'activeLeads' => $activeLeads,
            'hotLeads' => $hotLeads,
            'cnpLeads' => $cnpLeads,
        ];
        $this->selectedCustomer = $this->leads['allLeads']->pluck('customer_whatsapp')->toArray();
    }
    public function selectTemplate($templateId){
        $this->selectedTemplateId = $templateId;
        $this->previewTemplate = $this->templates[array_search($templateId, array_column($this->templates, 'Id'))];
        $this->toggleCustomerSelection($this->selectedCustomerType);
    }
    public function toggleCustomerSelection($type){
        // Logic to toggle customer selection based on the type
        $this->selectedCustomer = []; // Reset selected customers
        if($type=='all'){
            $this->selectedCustomer = $this->leads['allLeads']->pluck('customer_whatsapp')->toArray();
        }elseif($type=='hot_lead'){
            $this->selectedCustomer = $this->leads['hotLeads']->pluck('customer_whatsapp')->toArray();
        }elseif($type=='active_lead'){
            $this->selectedCustomer = $this->leads['activeLeads']->pluck('customer_whatsapp')->toArray();
        }elseif($type=='cnp'){
            $this->selectedCustomer = $this->leads['cnpLeads']->pluck('customer_whatsapp')->toArray();
        }
        if($type=='select_customers'){
             $this->selectedLeads = [];
        }

        $this->selectedCustomerType = $type;
    }
    public function QuickSearch($query)
    {
        $this->template_search = $query;
    }

     // Toggle selection when row clicked
    public function toggleLead($leadId)
    {
        // fetch the lead record
        $lead = Lead::find($leadId);

        if (!$lead) return;

        // if already selected → remove from both arrays
        if (in_array($leadId, $this->selectedLeads)) {
            $this->selectedLeads = array_diff($this->selectedLeads, [$leadId]);
            $this->selectedCustomer = array_diff($this->selectedCustomer, [$lead->customer_whatsapp]);
        } 
        // else → add to both arrays
        else {
            $this->selectedLeads[] = $leadId;
            if ($lead->customer_whatsapp) {
                $this->selectedCustomer[] = $lead->customer_whatsapp;
            }
        }
        $this->dispatch('refreshComponent');
    }

    public function sendWhatsAppMessage()
    {
        $template = $this->previewTemplate;
        $service = new WhatsAppService();
        foreach (array_unique($this->selectedCustomer) as $phone) {
            $lead = Lead::where('customer_whatsapp', $phone)->first();
            $v1 = $lead ? $lead->customer_name : 'Dear Customer';
            // You can add more variables as needed
            $response = $service->sendTemplate(
                $phone,
                $template['name'],
                $template['language'],
                [], // header params
                [$v1]  // body params
            );
        }
    }

    public function render()
    {
        return view('livewire.lead.whatsapp-campaign-dashboard', [
            'whatsappTemplates' => $this->templates,
            'messages' => $this->messages,
            'search' => $this->search,
        ]);
    }
}
