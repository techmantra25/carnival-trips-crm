<?php

namespace App\Jobs;

use App\Models\LeadUrlShare;
use App\Services\MailTemplateService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 3;
    public $timeout = 120;
    public int $sharedLinkId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $sharedLinkId)
    {
        $this->sharedLinkId = $sharedLinkId;
    }

    /**
     * Execute the job.
     */
    public function handle(MailTemplateService $mailService): void
    {
        $LeadUrlShare = LeadUrlShare::with(['itinerary', 'lead'])
            ->find($this->sharedLinkId);
        if (
            !$LeadUrlShare ||
            !$LeadUrlShare->lead ||
            !$LeadUrlShare->lead->customer_email
        ) {
            return;
        }

        $subject = "Hi, {$LeadUrlShare->lead->customer_name}, Your Dreem Itinerary ({$LeadUrlShare->itinerary->itinerary_syntax}) Awaits! 🎉";

        $mailService->send(
            $LeadUrlShare->lead->customer_email,
            'preset_itinerary_link',
            $subject,
            [
                'template_type'   => 'preset_itinerary_link',
                'recipient_name'  => $LeadUrlShare->lead->customer_name,
                'itinerary_link'  => $LeadUrlShare->links,
                'itinerary'       => $LeadUrlShare->itinerary->itinerary_syntax,
                'company_name'    => env('MAIL_FROM_NAME'),
                'sender_name'     => optional(auth('admin')->user())->name ?? env('MAIL_FROM_NAME'),
                'sender_mobile'   => optional(auth('admin')->user())->phone ?? '',
                'subject'         => $subject,
            ],
            env('MAIL_FROM_ADDRESS'),
            env('MAIL_FROM_NAME'),
            []
        );
    }

    /**
     * Handle job failure.
     */
    public function failed(Exception $exception): void
    {
        report($exception);
    }
}
