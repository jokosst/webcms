<?php

namespace App\Mail;

use App\Models\CompanyProfile;
use App\Models\LabBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LabBookingStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public LabBooking $booking;
    public string $statusType; // 'approved' or 'rejected'
    public ?string $adminNotes;
    public string $labNames;
    public ?CompanyProfile $profile;

    /**
     * Create a new message instance.
     */
    public function __construct(LabBooking $booking, string $statusType, ?string $adminNotes, string $labNames)
    {
        $this->booking = $booking;
        $this->statusType = $statusType;
        $this->adminNotes = $adminNotes;
        $this->labNames = $labNames;
        $this->profile = CompanyProfile::first();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $statusPrefix = $this->statusType === 'approved' ? '[DISETUJUI]' : '[DITOLAK]';
        $subject = "{$statusPrefix} Status Permohonan Peminjaman Laboratorium Komputer - {$this->booking->booking_code}";

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.lab_booking_status',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
