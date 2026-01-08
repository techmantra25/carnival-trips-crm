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
            // dd($to, $templateSlug, $subject, $content, $fromAddress, $fromName, $attachments);
            // Send email using your static email wrapper view
            Mail::send('emails.dynamic', $content, function ($message) use ($to, $subject, $fromAddress, $fromName, $attachments) {
                $message->to($to)
                        ->subject($subject);

                if ($fromAddress) {
                    $message->from($fromAddress, $fromName);
                }

                // Attach multiple files
                foreach ($attachments as $attachment) {
                if (
                    isset($attachment['data']) &&
                    $attachment['data'] instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile
                ) {
                    $file = $attachment['data'];

                    $message->attach(
                        $file->getRealPath(),
                        [
                            'as'   => $attachment['name'] ?? $file->getClientOriginalName(),
                            'mime' => $attachment['mime'] ?? $file->getMimeType(),
                        ]
                    );
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
            dd($e->getMessage());
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
