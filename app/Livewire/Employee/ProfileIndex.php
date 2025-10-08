<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use App\Helpers\CustomHelper;
use Illuminate\Support\Facades\Auth;
use App\Services\MailTemplateService;
use Illuminate\Support\Facades\Hash;

class ProfileIndex extends Component
{
    public $common,$authUser;
    public $name, $email, $phone, $whatsapp, $address;
    public $password, $password_confirmation, $sameAsPhone = false;
    public function mount(){
        $this->authUser = Auth::guard('admin')->user(); 
        $this->name = $this->authUser->name;
        $this->email = $this->authUser->email;
        $this->phone = $this->authUser->phone ?? '';
        $this->whatsapp = $this->authUser->whatsapp ?? '';
        $this->address = $this->authUser->address ?? '';
        $this->sameAsPhone = $this->phone && $this->whatsapp && $this->phone === $this->whatsapp;

        // Set common page headers and title
        $this->common = CustomHelper::setHeadersAndTitle('Profile Management', 'Profile');
    }
    public function marksameAsPhone($value)
    {
        $isChecked = filter_var($value, FILTER_VALIDATE_BOOLEAN);
       
        if ($isChecked) {
            $this->whatsapp = $this->phone;
            // Dispatch event with proper structure
            $this->dispatch('showConfirm', [
                'number' => $this->whatsapp,
                'status' => 1
            ]);
        } else {
            // Optional: reset WhatsApp or leave as is
            $this->dispatch('showConfirm', [
                'number' => '',
                'status' => 0
            ]);
        }
    }


    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|numeric|digits:10',
            'whatsapp' => 'nullable|numeric|digits:10',
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|min:6|same:password_confirmation',
        ]);

        $user = $this->authUser;
        $user->name = $this->name;
        $user->email = $this->email;
        $user->phone = $this->phone;
        $user->whatsapp = $this->whatsapp;
        $user->address = $this->address;

        if (!empty($this->password)) {
            $user->password = Hash::make($this->password);

            MailTemplateService::send(
                $user->email,//mail to
                'employee_password_changed', //mail template slug
                [
                    'employee_name' => $user->name,
                ], // mail body data
                [], //mail subject data
                ENV('MAIL_FROM_ADDRESS'),     // From Email
                ENV('MAIL_FROM_NAME')         // From Name
            );
        }
        $user->save();
        session()->flash('success', 'Profile updated successfully.');
    }
    public function render()
    {
        return view('livewire.employee.profile-index');
    }
}
