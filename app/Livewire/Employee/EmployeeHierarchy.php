<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use App\Helpers\CustomHelper;
use App\Models\Admin;

class EmployeeHierarchy extends Component
{
    public $superAdmin;
    public $teamLeads;
    public $common;

    public function mount()
    {
        $this->common = CustomHelper::setHeadersAndTitle('Employee Hierarchy', 'Tree View');

        $this->superAdmin = Admin::with('designation')->where('role','super_admin')->first(); // Root
        $this->teamLeads = Admin::with(['designation', 'subordinates.designation'])
            ->where('role', 'team_lead')
            ->get();
    }
    public function render()
    {
        return view('livewire.employee.employee-hierarchy');
    }
}
