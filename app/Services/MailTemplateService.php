<?php
namespace App\Services;

use App\Mail\DynamicMail;
use App\Models\EmailTemplate;
use App\Models\MailLog;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MailTemplateService
{
    public static function send($to, $templateSlug, $data = [], $subject_data = [], $fromAddress = null, $fromName = null)
    {
        // Fetch the template
        $template = EmailTemplate::where('slug', $templateSlug)
                        ->where('active', true)
                        ->firstOrFail();

        // Create mail log
        $log = MailLog::create([
            'email' => $to,
            'template_slug' => $templateSlug,
            'status' => 'pending',
        ]);

        try {
            // Replace placeholders in template body
            $body = $template->body;
            foreach ($data as $key => $value) {
                $body = str_replace('{{'.$key.'}}', $value, $body);
            }
            // Now inject CKEditor content
            if(isset($data['content'])) {
                $body = str_replace('{!! content !!}', $data['content'], $body);
            }
            // Replace placeholders in template subject
            $subject = $template->subject;
            foreach ($subject_data as $sub_key => $sub_value) {
                $subject = str_replace('{{'.$sub_key.'}}', $sub_value, $subject);
            }
            // Create DynamicMail instance
            Mail::send('emails.dynamic', ['html' => $body], function ($message) use ($to, $subject, $fromAddress, $fromName) {
                $message->to($to)
                        ->subject($subject)
                        ->from($fromAddress, $fromName);
            });
            // Update log
            $log->update(
                [
                    'status' => 'success',
                    'subject' => $subject,
                    'mail_body' => $body,
                ]
            );
            return true;

        } catch (\Exception $e) {
            \Log::error('Mail send error: '.$e->getMessage());
            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);
            return false;
        }
    }

}
