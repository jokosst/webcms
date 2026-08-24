<?php

namespace App\Mail;

use App\Models\CompanyProfile;
use App\Models\InboxLead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InboxReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $replySubject;
    public string $replyMessage;
    public InboxLead $lead;
    public ?CompanyProfile $profile;

    public function __construct(string $replySubject, string $replyMessage, InboxLead $lead)
    {
        $this->replySubject = $replySubject;
        $this->replyMessage = $replyMessage;
        $this->lead = $lead;
        $this->profile = CompanyProfile::first();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->replySubject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.inbox_reply',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
