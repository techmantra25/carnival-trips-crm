<?php

namespace App\Livewire\Lead;

use Livewire\Component;
use App\Models\Lead;
use App\Models\TripPreferenceLog;

class TripPreferenceData extends Component
{
    public $lead_id;
    public $lead;
    public $preferences_raw = [];

    public function mount($lead_id)
    {
        $this->lead_id = $lead_id;
        $this->lead = Lead::findOrFail($lead_id);

        // Don't group here; just get raw data
        $this->preferences_raw = TripPreferenceLog::where('lead_id', $lead_id)
            ->orderBy('step')
            ->get();
    }

    public function render()
    {
        // Group in render (NOT in mount)
        $preferences = $this->preferences_raw->groupBy('step');

        return view('livewire.lead.trip-preference-data', compact('preferences'));
    }
}
