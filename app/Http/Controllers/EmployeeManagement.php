<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\CustomHelper;

class EmployeeManagement extends Controller
{
    public function index(Request $request)
    {
        $common = CustomHelper::setHeadersAndTitle('Employee Management', 'Employee');
        return view('admin.employee.index', compact('common'));
    }

    public function designationIndex()
    {
        $common = CustomHelper::setHeadersAndTitle('Designation-Employee Management', 'Designation');
        return view('admin.employee.designation_index', compact('common'));
    }
    public function employeeHierarchy()
    {
        $common = CustomHelper::setHeadersAndTitle('Employee Management', 'Employee Hierarchy');
        return view('admin.employee.employee_hierarchy', compact('common'));
    }
}
