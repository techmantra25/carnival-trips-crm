<?php

namespace App\Livewire\Lead;

use Livewire\Component;
use App\Models\LeadWhatsappMessage;
use App\Models\WhatsappMessageTemplate;
use Illuminate\Support\Facades\Auth;
use App\Models\Lead;

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
    public $selectedCustomer = [], $selectedLeads = []; // store selected lead IDs

    public function mount()
    {
        $this->authUser = Auth::guard('admin')->user();
        $this->messages = LeadWhatsappMessage::where('sent_by', $this->authUser->id)->get();
        $this->collectLead();
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
    }
    public function selectTemplate($templateId){
        $this->selectedTemplateId = $templateId;
        $this->selectedCustomerType = 'all';
         $this->collectLead();
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
    }
    public function render()
    {
        // Start query
        $query = WhatsappMessageTemplate::query();

        // Apply search filter if provided
        if ($this->template_search) {
            $query->where('title', 'like', '%' . $this->template_search . '%');
        }

        // Fetch templates (limit 4 for non-selected templates)
        $templates = $query->orderBy('created_at', 'desc')->limit(4)->get();

        // Ensure selected template is included
        if ($this->selectedTemplateId) {
            $selectedTemplate = WhatsappMessageTemplate::find($this->selectedTemplateId);

            if ($selectedTemplate && !$templates->contains('id', $selectedTemplate->id)) {
                // Add selected template at the beginning
                $templates->prepend($selectedTemplate);
            }
        }

        $this->templates = $templates;
        return view('livewire.lead.whatsapp-campaign-dashboard', [
            'templates' => $this->templates,
            'messages' => $this->messages,
            'search' => $this->search,
        ]);
    }
}
