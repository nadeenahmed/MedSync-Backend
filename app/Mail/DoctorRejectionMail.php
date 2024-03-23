<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class DoctorRejectionMail extends Mailable
{
    use Queueable, SerializesModels;
    public $doctorName;
    public $rejectionReason;
    /**
     * Create a new message instance.
     */
    public function __construct($doctorName,$rejectionReason)
    {
        $this->doctorName = $doctorName;
        $this->rejectionReason = $rejectionReason;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('medsync6@gmail.com'),
            subject: 'Request Rejected',
        );
    }


    public function build()
    {
        return $this->view('mails.rejectionMail')
                    ->with([
                        'doctorName' => $this->doctorName,
                        'rejectionReason' => $this->rejectionReason
                    ]);
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.rejectionMail',
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
