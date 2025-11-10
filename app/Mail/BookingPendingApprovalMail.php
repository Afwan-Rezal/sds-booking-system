<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class BookingPendingApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;

    /**
     * Create a new message instance.
     */
    public function __construct(array $mailData)
    {
        //
        $this->mailData = $mailData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $fromEmail = $this->mailData['from_email'] ?? 'noreply@sds-booking.com';
        $fromName = $this->mailData['from_name'] ?? 'SDS Booking System';

        $subjectPrefix = $this->mailData['room'] ?? '';
    
        return new Envelope(
            from: new Address($fromEmail, $fromName),
            subject: trim("{$subjectPrefix} Booking Pending Approval Mail"),
        );
    }

    /**
     * Get the message content definition.
     */

    public function content(): Content
    {
        return new Content(
            view: 'mail.booking_pending_approval_mail',
            with: [
                'mailData' => $this->mailData,
            ],
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
