<?php

namespace App\Livewire\Lead;

use Livewire\Component;
use App\Models\LeadActivityLog;
use App\Models\lead;
use Illuminate\Support\Carbon;


class LeadLogHistory extends Component
{
    public $lead_id;
    public $start_date;
    public $end_date,$lead;
    public $logs = [];
    public function mount($lead_id){
        $this->lead_id = $lead_id;
        $this->lead = Lead::findOrFail($lead_id);
        // Default range: last 7 days
        $this->start_date = now()->subDays(7)->format('Y-m-d');
        $this->end_date = now()->format('Y-m-d');
    }
    public function updated($property)
    {
        if (in_array($property, ['start_date', 'end_date'])) {
            $this->loadLogs();
        }
    }
    public function loadLogs()
    {
        $this->logs = LeadActivityLog::where('lead_id', $this->lead_id)
            ->when($this->start_date, fn($q) => $q->whereDate('created_at', '>=', $this->start_date))
            ->when($this->end_date, fn($q) => $q->whereDate('created_at', '<=', $this->end_date))
            ->orderByDesc('created_at')
            ->get();
    }
    public function render()
    {
        $this->loadLogs();
        return view('livewire.lead.lead-log-history');
    }
}
