<?php

namespace App\Livewire;

use Livewire\Component;

class DashboardIndex extends Component
{
    public $filter_type = 'date', $start_date, $end_date, $month, $year;

    public function mount()
    {
        $this->start_date = date('Y-m-01');
        $this->end_date = date('Y-m-d');
    }
    public function FilterTypeUpdate($value){
        $this->filter_type = $value;
    }

    public function refreshFilters(){
        // Logic to refresh data or query based on filters
        // Example:
        // $this->emit('filtersUpdated');
    }
    public function render()
    {
        return view('livewire.dashboard-index');
    }
}
