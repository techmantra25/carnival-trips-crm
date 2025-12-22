<?php
namespace App\Services;

use App\Models\MailLog;
use Illuminate\Support\Facades\Mail;

class MailTemplateService
{
    public function send($to, $templateSlug, $subject, $content, $fromAddress = null, $fromName = null, $attachments = [])
    {
        try {
            // Create mail log (pending)
            $log = MailLog::create([
                'email'         => $to,
                'template_slug' => $templateSlug, 
                'subject'       => $subject,   //  email subject
                'status'        => 'pending',
            ]);
            // Send email using your static email wrapper view
            Mail::send('emails.dynamic', $content, function ($message) use ($to, $subject, $fromAddress, $fromName, $attachments) {
                $message->to($to)
                        ->subject($subject);

                if ($fromAddress) {
                    $message->from($fromAddress, $fromName);
                }

                // Attach multiple files
                foreach ($attachments as $file) {
                    if (file_exists($file)) {
                        $message->attach($file);
                    }
                }
            });
            // Update log after success
            $log->update([
                'status' => 'success',
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
