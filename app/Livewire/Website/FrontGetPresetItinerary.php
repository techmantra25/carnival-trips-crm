<?php

namespace App\Livewire\Website;

use Livewire\Component;
use Illuminate\Support\Facades\Request;
use App\Helpers\CustomHelper;
use App\Models\Lead;
use App\Models\City;
use App\Models\LeadUrlShare;
use App\Models\ItineraryTemplate;
use App\Models\LeadUrlClick;

class FrontGetPresetItinerary extends Component
{
    public $title,$lead,$itinerary,$template,$lead_url_share;
    public $divisions_journeies = [];
    public $clickLogId;

    public function mount($destination_slug, $itinerary_slug, $encryptedSharedLinkId)
    {
        $shared_link_id = CustomHelper::secure_decode_id($encryptedSharedLinkId);
        $this->lead_url_share = LeadUrlShare::with('lead')->find($shared_link_id);
        if(!$this->lead_url_share){
            abort(404);
        }
     
        $this->lead = Lead::findOrFail($this->lead_url_share->lead_id);

        $this->itinerary = $this->lead_url_share->itinerary;
        if(!$this->lead || !$this->itinerary){
            abort(404);
        }
        $this->template = ItineraryTemplate::with('detail')->where('destination_id',$this->lead->travel_location)->first();

        $division_ids = explode(',',$this->itinerary->stay_by_journey);
        $night_journeys  = explode(',', $this->itinerary->night_journey);

        $map = [];
        
        foreach ($division_ids as $i => $value) {
            $index = $i + 1; // 1-based index
        
            if (!isset($map[$value])) {
                $city = City::find($value);
                $map[$value] = [
                    'city_id' => $value,
                    'city_name' => $city ? $city->name : null,
                    'count' => 0,
                    'index' => [],
                ];
            }
        
            $map[$value]['count']++;
            $map[$value]['index'][] = $index;
        }
        
        $this->divisions_journeies= array_values($map);
        
        // Example: "John’s Customized Tour Itinerary for Shillong & Cherrapunjee"
        $this->title = "{$this->lead->customer_name}’s Customized Tour Itinerary for {$this->lead->destination->name} ({$this->lead->travel_duration_text})";

    }

     public function incrementClick()
    {
        if ($this->clickLogId) {
            LeadUrlClick::where('id', $this->clickLogId)->increment('click_count');
        }
    }

     public function setExitTime()
    {
        if ($this->clickLogId) {
            LeadUrlClick::where('id', $this->clickLogId)->update([
                'exit_time' => now()->toDateTimeString()
            ]);
        }
    }

    public function startNewClickLog()
    {
        // Close old session if still active
        if ($this->clickLogId) {
            LeadUrlClick::where('id', $this->clickLogId)->update([
                'exit_time' => now()->toDateTimeString()
            ]);
        }

        // Start new session
        $log = LeadUrlClick::create([
            'lead_id' => $this->lead->id,
            'lead_url_share_id' => $this->lead_url_share->id,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'referrer' => Request::server('HTTP_REFERER'),
            'entry_time' => now()->toDateTimeString(),
        ]);

        $this->clickLogId = $log->id;
    }
    public function closeClickLog()
    {
        if ($this->clickLogId) {
            LeadUrlClick::where('id', $this->clickLogId)->update([
                'exit_time' => now()->toDateTimeString()
            ]);

            $this->clickLogId = null; // reset for next session
        }
    }
    public function updateExitTime()
    {
        if ($this->clickLogId) {
            LeadUrlClick::where('id', $this->clickLogId)->update([
                'exit_time' => now()->toDateTimeString()
            ]);
        }
    }
    public function render()
    {
        return view('livewire.website.front-get-preset-itinerary')->layout('layouts.frontend.master', ['title' => $this->title]);
    }
}
