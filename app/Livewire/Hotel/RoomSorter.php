<?php

namespace App\Livewire\Hotel;

use Livewire\Component;
use App\Models\Room;

class RoomSorter extends Component
{
    public $rooms = [];
    public function mount($rooms){
        $this->rooms = $rooms;
    }

    public function updateRoomOrder($orderedIds)
    {
        foreach ($orderedIds as $index => $roomId) {
            Room::where('id', $roomId)->update(['positions' => $index + 1]);
        }

        session()->flash('message', 'Room order updated successfully.');
    }
    public function render()
    {
        return view('livewire.hotel.room-sorter');
    }
}
