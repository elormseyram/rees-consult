<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewServiceApplicationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $type;
    public $submission;

    /**
     * Create a new message instance.
     *
     * @param string $type
     * @param mixed $submission
     */
    public function __construct($type, $submission)
    {
        $this->type = $type;
        $this->submission = $submission;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $typeName = '';
        if ($this->type === 'signup') {
            $typeName = 'Service Prep Course Registration';
        } elseif ($this->type === 'school') {
            $typeName = 'School Application';
        } elseif ($this->type === 'job') {
            $typeName = 'Job Placement Application';
        }

        $senderName = trim(($this->submission->first_name ?? '') . ' ' . ($this->submission->last_name ?? ''));
        if (empty($senderName)) {
            $senderName = 'Applicant';
        }

        return new Envelope(
            subject: 'New Submission: ' . $typeName . ' - ' . $senderName,
            replyTo: [
                $this->submission->email ?? 'noreply@reesconsult.com'
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new_service_application_notification',
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
