<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class DoctorApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $doctorName;
    /**
     * Create a new message instance.
     */
    public function __construct($doctorName)
    {
        $this->doctorName = $doctorName;
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('medsync6@gmail.com'),
            subject: 'Request Approved',
        );
    }

    /**
     * Get the message content definition.
     */
    public function build()
    {
        return $this->view('mails.approvalMail')
                    ->with([
                        'doctorName' => $this->doctorName
                    ]);
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.approvalMail',
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
