<?php

namespace App\Livewire\Lead;

use Livewire\Component;
use App\Models\LeadUrlShare;
use App\Models\Lead;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SharedItineraryHistory extends Component
{
    public $lead_id;
    public $lead;
    public $expandedShareId = null;
    public $clickStats = [];


    public function mount($lead_id)
    {
        $this->lead_id = $lead_id;
        $this->lead = Lead::find($lead_id);
    }
    public function toggleStats($shareId)
    {
        if ($this->expandedShareId === $shareId) {
            $this->expandedShareId = null; // collapse
            return;
        }

        $this->expandedShareId = $shareId;

        $this->clickStats = collect(DB::table('lead_url_clicks')
            ->where('lead_url_share_id', $shareId)
            ->select('entry_time', 'exit_time', 'click_count')
            ->get())
            ->map(function ($record) {
                $entry = $record->entry_time ? \Carbon\Carbon::parse($record->entry_time) : null;
                $exit = $record->exit_time ? \Carbon\Carbon::parse($record->exit_time) : null;      
               
                $diff = 0;
                if ($entry && $exit) {
                    // Fix for reverse times
                    if ($exit->lt($entry)) {
                        [$entry, $exit] = [$exit, $entry];
                    }
                    $diff = strtotime($exit) - strtotime($entry);
                }
                
                return [
                    'entry_time' => $entry?->format('d M Y h:i A'),
                    'exit_time' => $exit?->format('d M Y h:i A'),
                    'click_count' => $record->click_count,
                    'duration' => gmdate("H:i:s", $diff),
                ];
            });
    }

    public function render()
    {
        $logs = LeadUrlShare::with('lead','itinerary','sharedBy')->where('lead_id', $this->lead_id)
            ->latest()
            ->get();

        return view('livewire.lead.shared-itinerary-history', compact('logs'));
    }
}
