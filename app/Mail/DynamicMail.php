<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DynamicMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $html;
    public $subject;
    public $to;
    public $from;
    public $fromName;

    public function __construct($to, $subject, $html, $from = null, $fromName = null)
    {
        $this->subject = $subject;
        $this->html = $html;
        $this->to = $to;
        $this->from = $from;
        $this->fromName = $fromName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.dynamic',
            with: ['content' => $this->html]
        );
    }

    public function build()
    {
        $mail = $this->from($this->from ?? ENV('MAIL_FROM_ADDRESS'), 
                            $this->fromName ??  ENV('MAIL_FROM_NAME'))
                     ->subject($this->subject)
                     ->view('emails.dynamic', ['html' => $this->html]);
        return $mail;
    }

    public function attachments(): array
    {
        return [];
    }
}
