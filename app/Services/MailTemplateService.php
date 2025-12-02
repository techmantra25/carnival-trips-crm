<?php
namespace App\Services;

use App\Models\MailLog;
use Illuminate\Support\Facades\Mail;

class MailTemplateService
{
    public function send($to, $subject, $content, $fromAddress = null, $fromName = null)
    {
        // Create mail log (pending)
        $log = MailLog::create([
            'email' => $to,
            'template_slug' => 'mail_send',    // No template slug anymore
            'status' => 'pending',
        ]);

        try {
            // Send email using your static email wrapper view
            Mail::send('emails.dynamic', ['html' => $content], function ($message) use ($to, $subject, $fromAddress, $fromName) {
                $message->to($to)
                        ->subject($subject);

                if ($fromAddress) {
                    $message->from($fromAddress, $fromName);
                }
            });

            // Update log after success
            $log->update([
                'status' => 'success',
                'subject' => $subject,
                'mail_body' => $content,
            ]);

            return true;

        } catch (\Exception $e) {

            // Log the failure
            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            \Log::error('Mail send error: '.$e->getMessage());

            return false;
        }
    }
}
