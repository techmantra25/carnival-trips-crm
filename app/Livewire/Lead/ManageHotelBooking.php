<?php

namespace App\Livewire\Lead;

use Livewire\Component;
use App\Models\Lead;

class ManageHotelBooking extends Component
{
    public $leadData, $sent_itinerary;
    public $activeTab = 0;
    public function mount($lead_id){
        $this->leadData = Lead::findOrFail($lead_id);
        $this->sent_itinerary = $this->leadData->sent_itinerary;
        $confirmedIndex = collect($this->sent_itinerary)
        ->search(fn ($item) => $item->is_confirmed == 1);

        $this->activeTab = $confirmedIndex !== false ? $confirmedIndex : 0;
    }
    public function render()
    {
        return view('livewire.lead.manage-hotel-booking');
    }
}
