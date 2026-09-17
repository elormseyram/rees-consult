<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewApplicationLead extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;
    public array $assessment;
    public array $transcript;

    /**
     * @param array $data        Validated submission
     * @param array $assessment  BANT assessment (rating, willing, able, verdict)
     * @param array $transcript  Ordered label => answer pairs
     */
    public function __construct(array $data, array $assessment, array $transcript)
    {
        $this->data = $data;
        $this->assessment = $assessment;
        $this->transcript = $transcript;
    }

    public function envelope(): Envelope
    {
        $name = trim(($this->data['first_name'] ?? '') . ' ' . ($this->data['last_name'] ?? '')) ?: 'Applicant';
        $rating = $this->assessment['rating'] ?? 'NEW';

        $goalLabels = [
            'test_prep'    => 'Test Prep',
            'study_abroad' => 'Study Abroad',
            'work_abroad'  => 'Work Abroad',
        ];
        $goal = $goalLabels[$this->data['goal'] ?? ''] ?? 'Application';

        return new Envelope(
            subject: "New Application Lead [{$rating}] — {$name} ({$goal})",
            replyTo: [$this->data['email'] ?? 'noreply@reesconsult.com'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.application-lead',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
