<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Lead;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardIndex extends Component
{
    public $filter_type = 'year', $start_date, $end_date, $month, $year,$authUser;

    public $leadCounts = [];
    public $confirmedCounts = [], $destinationStats = [], $chartData  =[];

    public function mount()
    {
        $this->authUser = Auth::user();
        // $this->start_date = date('Y-m-01');
        // $this->end_date = date('Y-m-d');
        $this->year = date('Y');
    }
    public function FilterTypeUpdate($value){
        $this->filter_type = $value;
    }
    public function MonthUpdate(){
        
    }
    public function YearUpdate(){

    }
    public function StartDateUpdate(){

    }
    public function EndDateUpdate(){

    }

    public function refreshFilters(){
        $this->reset(['filter_type','month','year']);
        $this->start_date = date('Y-m-01');
        $this->end_date = date('Y-m-d');
    }
    public function loadDashboardData(){
        $query = Lead::query();
        // Apply filters based on selected filter type
        $query->when($this->filter_type=='date', function ($query){
            $query->when($this->start_date && $this->end_date, function ($q){
                $q->whereBetween('created_at',[
                    Carbon::parse($this->start_date)->startOfDay(),
                    Carbon::parse($this->end_date)->endOfDay(),
                ]);
            });
        });

        $query->when($this->filter_type=='month', function ($q){
            $q->whereMonth('created_at', $this->month)
            ->whereYear('created_at', $this->year);
        });

        $query->when($this->filter_type=='year', function ($q){
            $q->whereYear('created_at', $this->year);
        });
        // Limit to leads generated from "lead"
        $query->where('generate_from', 'lead');

        // Role-based data filtering
        $query->when(in_array($this->authUser->role, ['team_lead', 'member']), function ($q) {
            if ($this->authUser->role === 'team_lead') {
                $q->where('team_lead_id', $this->authUser->id);
            } elseif ($this->authUser->role === 'member') {
                $q->where('assigned_to_id', $this->authUser->id);
            }
        });

        $leads = $query->get();

         // Count total leads and confirmed leads by source
        $sources = ['Facebook', 'Instagram', 'Website'];

        foreach ($sources as $source) {
            $this->leadCounts[strtolower($source)] = $leads->where('source_type', $source)->count();
            $this->confirmedCounts[strtolower($source)] = $leads->where('source_type', $source)->where('status', 'Confirmed')->count();
        }

        $groupedLeads = $leads->groupBy('travel_location');

        $this->destinationStats = [];

        foreach ($groupedLeads as $locationId => $group) {
            $totalLeads = $group->count();
            $confirmedLeads = $group->where('status', 'Confirmed')->count();

            $this->destinationStats[] = [
                'location_id' => $locationId,
                'location_name' => optional($group->first()->destination)->name,
                'total' => $totalLeads,
                'confirmed' => $confirmedLeads,
                'percentage' => $totalLeads > 0 ? round(($confirmedLeads / $totalLeads) * 100, 2) : 0
            ];
        }

        // Total leads count
        $this->leadCounts['total'] = $leads->count();
        $this->confirmedCounts['total'] = $leads->where('status', 'Confirmed')->count();

    }

    public function generateChartData()
    {
        $sources = ['Facebook', 'Instagram', 'Website'];
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $datasets = [];
        $globalMax = 0;
        foreach ($sources as $source) {
            $year = $this->year ?? now()->year;
            
            $monthlyCounts = DB::table('leads')
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                ->where('source_type', $source)
                ->whereYear('created_at', (int) $year)
                ->groupByRaw('MONTH(created_at)')
                ->pluck('total', 'month')
                ->toArray();
                
            // Fill 12 months (even if empty, set to 0)
            $data = [];
            for ($i = 1; $i <= 12; $i++) {
                $value = $monthlyCounts[$i] ?? 0;
                $data[] = $value;
                if ($value > $globalMax) {
                    $globalMax = $value;
                }
            }
        
            $datasets[] = [
                'label' => $source,
                'data' => $data,
                'backgroundColor' => match($source) {
                    'Facebook' => '#1D4ED8',
                    'Instagram' => '#E11D48',
                    'Website' => '#EAB308',
                }
            ];
        }
        $chartMax = ceil($globalMax * 1.1);
        return [
            'labels' => $labels,
            'chartMax' => round($chartMax),
            'datasets' => $datasets
        ];
        
    }
    public function render()
    {
        $this->chartData = $this->generateChartData();
        $this->dispatch('refreshComponent', ['chartData'=>$this->chartData]);
        $this->loadDashboardData();
        return view('livewire.dashboard-index');
    }
}
