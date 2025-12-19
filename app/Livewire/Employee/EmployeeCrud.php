<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use App\Models\Admin;
use App\Models\State;
use App\Models\Designation;
use App\Helpers\CustomHelper;
use Livewire\WithPagination;
use App\Services\MailTemplateService;
use App\Jobs\SendEmployeeWelcomeMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Exception;

class EmployeeCrud extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $common,$authUser;
    public $name, $designation_id, $email, $phone, $address, $whatsapp, $password, $team_lead,$role = 'member';
    public $destination_ids = []; // for multi-select
    public $designations = [], $admins = [], $states = []; // load in mount()
    public $employee_id;
    public $updateMode = false;
    public $modalMode = false;
    public $filter_destination = null;
    public $search = '';
    public $showTeamModal = false;
    public $teamMembers = [];
    public $selectedLeaderId,$selectedLeaderName;

    public function mount()
    {
        // Fetch the authenticated admin user
        $this->authUser = Auth::guard('admin')->user(); 

        // Set common page headers and title
        $this->common = CustomHelper::setHeadersAndTitle('Employee Management', 'Employee List');

        // Fetch admins list
        // - If the logged-in user is a 'team_lead' or 'member', return only their own record
        // - Otherwise, return all admins
        $this->admins = Admin::select('id', 'name')
        ->when(in_array($this->authUser->role, ['team_lead', 'member']), function ($query){
            $query->where('id', $this->authUser->id);
        })
        ->get();
       

        $this->designations = Designation::where('status', 1)->get();
        $this->states = State::where('status', 1)->orderBy('name', 'ASC')->get();

    }
    public function NewEmployee($value){
        $this->modalMode = $value==="Yes"?true:false;
    }

    public function resetInput()
    {
        $this->search = '';
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->filter_destination = null;
        $this->role = 'member';
        $this->designation_id = null;
        $this->employee_id = null;
        $this->updateMode = false;
        $this->modalMode = false;
        $this->dispatch('refreshComponent');
        $this->resetErrorBag();       // Clear validation errors
        $this->resetValidation();
    }

   public function store(MailTemplateService $mailService)
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('admins', 'email')->whereNull('deleted_at'),
                'regex:/^[A-Za-z0-9._%+-]+@carrnivaltrips\.com$/i'
            ],
            'role' => 'required|in:admin,team_lead,member',
            'phone' => 'required|string|max:10',
            'whatsapp' => 'required|string|max:10',
            'address' => 'nullable|string',
            'designation_id' => 'required|exists:designations,id',
            'password' => 'required|string|min:6',
            'team_lead' => 'required|exists:admins,id',
            'destination_ids' => 'array',
            'destination_ids.*' => 'exists:states,id',
        ], [
            'email.regex' => 'The email must be a valid email address ending with @carrnivaltrips.com',
        ]);

        DB::beginTransaction();

        try {
            $password = Hash::make($this->password);

            $employee = Admin::create([
                'name'           => ucwords($this->name),
                'email'          => $this->email,
                'phone'          => $this->phone,
                'role'           => $this->role,
                'whatsapp'       => $this->whatsapp,
                'address'        => $this->address,
                'designation_id' => $this->designation_id,
                'team_lead'      => $this->team_lead,
                'password'       => $password,
            ]);

            // Sync destinations
            $employee->destinations()->sync($this->destination_ids);
            // Send welcome email
            $mailService->send(
                $employee->email,
                'employee_welcome',                 // Template slug
                'Welcome to Carrnival Trips 🎉',     // Subject
                [
                    'employee_name'     => $employee->name,
                    'company_name'      => env('MAIL_FROM_NAME'),
                    'portal_link'       => asset('/'),
                    'temporary_password'=> $this->password,
                    'template_type'=> "employee_welcome",
                    'sender_name'         => Auth::guard('admin')->user()->name,
                    'sender_mobile'       => Auth::guard('admin')->user()->phone, 
                ],
                env('MAIL_FROM_ADDRESS'),
                env('MAIL_FROM_NAME')
            );

            //  Commit ONLY if email is successful
            DB::commit();

            $this->resetInput();
            session()->flash('success', 'Employee created successfully.');

        } catch (Exception $e) {
            DB::rollBack();

            report($e);

            session()->flash('error', 'Employee creation failed. Email could not be sent.');
        }
    }

   public function edit($id)
    {
        $employee = Admin::findOrFail($id);

        $this->employee_id = $employee->id;
        $this->name = $employee->name;
        $this->email = $employee->email;
        $this->role = $employee->role;
        $this->phone = $employee->phone;
        $this->whatsapp = $employee->whatsapp;
        $this->address = $employee->address;
        $this->designation_id = $employee->designation_id;
        $this->team_lead = $employee->team_lead;
        $this->destination_ids = $employee->destinations()->pluck('states.id')->toArray(); // Auto-fill

        $this->updateMode = true;
        $this->modalMode = true;
    }

    public function updateData(MailTemplateService $mailService)
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'regex:/^[A-Za-z0-9._%+-]+@carrnivaltrips\.com$/i',
                Rule::unique('admins', 'email')->ignore($this->employee_id)->whereNull('deleted_at'),
            ],
            'phone' => 'required|string|max:10',
            'role' => 'required|in:admin,team_lead,member',
            'whatsapp' => 'required|string|max:10',
            'address' => 'nullable|string',
            'designation_id' => 'required|exists:designations,id',
            'team_lead' => 'required|exists:admins,id',
            'destination_ids' => 'array',
            'destination_ids.*' => 'exists:states,id',
        ],[
            'email.regex' => 'The email must be a valid email address ending with @carrnivaltrips.com',
        ]);

        $employee = Admin::findOrFail($this->employee_id);

        DB::beginTransaction();

        try {
            // Update main fields
            $employee->update([
                'name' => ucwords($this->name),
                'email' => $this->email,
                'role' => $this->role,
                'phone' => $this->phone,
                'whatsapp' => $this->whatsapp,
                'address' => $this->address,
                'designation_id' => $this->designation_id,
                'team_lead' => $this->team_lead,
            ]);

            // Update password only if provided
            if ($this->password) {
                $employee->update([
                    'password' => Hash::make($this->password)
                ]);

                // Send welcome email with new password
                $mailService->send(
                    $employee->email,
                    'employee_welcome',                 // Template slug
                    'Welcome to Carrnival Trips 🎉',     // Subject
                    [
                        'employee_name'     => $employee->name,
                        'company_name'      => env('MAIL_FROM_NAME'),
                        'portal_link'       => asset('/'),
                        'temporary_password'=> $this->password,
                        'template_type'     => "employee_welcome",
                        'sender_name'       => Auth::guard('admin')->user()->name,
                        'sender_mobile'     => Auth::guard('admin')->user()->phone, 
                    ],
                    env('MAIL_FROM_ADDRESS'),
                    env('MAIL_FROM_NAME')
                );
            }

            // Sync destinations
            $employee->destinations()->sync($this->destination_ids);

            DB::commit();

            $this->resetInput();
            session()->flash('success', 'Employee updated successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            session()->flash('error', 'Employee update failed. Email could not be sent.');
        }
    }

    public function delete($id)
    {
        $this->dispatch('showConfirm', ['itemId' => $id]);
    }

    public function deleteItem($id)
    {
        $employee = Admin::find($id);
        if ($employee) {
            $employee->delete(); // Soft delete will occur
            session()->flash('success', 'Employee deleted successfully.');
        } else {
            session()->flash('error', 'Employee not found.');
        }
    }

    public function toggleStatus($id)
    {
        $employee = Admin::findOrFail($id);
        $employee->status = !$employee->status;
        $employee->save();
    }

    public function QuickSearch($value)
    {
        $this->search = $value;
        $this->resetPage(); 
    }

    public function filterDestination($value)
    {
        $this->filter_destination = $value;
        $this->resetPage(); 
    }
    public function showTeamMembers($leaderId)
    {
         $this->selectedLeaderId = $leaderId;

        // Get team lead
        $leader = Admin::find($leaderId);
        $this->selectedLeaderName = $leader?->name ?? 'Team Lead';

        // Fetch members under the team lead
        $this->teamMembers = Admin::where('team_lead', $leaderId)->get();

        $this->showTeamModal = true;
    }
    public function render()
    {
        $query = Admin::with('designation', 'destinations')
            ->where('id', '!=', 1)
            ->whereNull('deleted_at');

        // Role-based filtering
        // dd($authUser->role);
        if (in_array($this->authUser->role, ['team_lead', 'member'])) {
            if ($this->authUser->role === 'team_lead') {
                $query->where('team_lead', $this->authUser->id)
                    ->where('id', '!=', $this->authUser->id); // exclude self;
            } elseif ($this->authUser->role === 'member') {
                $query->where('id', $this->authUser->id); // show only own data
            }
        }

        // Search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('phone', 'like', '%' . $this->search . '%')
                ->orWhere('whatsapp', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->orWhere('role', 'like', '%' . $this->search . '%')
                ->orWhereHas('designation', function ($d) {
                    $d->where('name', 'like', '%' . $this->search . '%');
                });
            });
        }

        // Destination filter
        if ($this->filter_destination) {
            $query->whereHas('destinations', function ($q) {
                $q->where('states.id', $this->filter_destination);
            });
        }

        $employees = $query->latest()->paginate(20);
        $totalEmployees = $employees->total(); // fixed for pagination

        return view('livewire.employee.employee-crud', compact('employees', 'totalEmployees'));
    }

}
