<?php

namespace App\Jobs;

use App\Models\Admin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\MailTemplateService;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmployeeWelcomeMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $employee;
    protected $plainPassword;
    /**
     * Create a new job instance.
     */
     public function __construct(Admin $employee, string $plainPassword)
    {
        $this->employee = $employee;
        $this->plainPassword = $plainPassword;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        MailTemplateService::send(
            $this->employee->email,
            'employee_welcome',
            [
                'employee_name' => $this->employee->name,
                'company_name' => 'TechOrigin Pvt. Ltd.',
                'portal_link' => 'https://portal.techorigin.in/login',
                'temporary_password' => $this->plainPassword,
            ],
            ['company_name' => env('MAIL_FROM_NAME')],
            env('MAIL_FROM_ADDRESS'),
            env('MAIL_FROM_NAME')
        );
    }
}
