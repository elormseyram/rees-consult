<?php

namespace App\Mail;

use App\Services\MailSettings;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Renders an admin-authored EmailTemplate. Not queueable on purpose — this
 * host has no queue worker, so mail is sent inline.
 */
class TemplatedMail extends Mailable
{
    use SerializesModels;

    public function __construct(
        public string $renderedSubject,
        public string $renderedBody,
    ) {
    }

    public function envelope(): Envelope
    {
        $replyTo = MailSettings::replyTo();

        return new Envelope(
            subject: $this->renderedSubject,
            replyTo: $replyTo ? [$replyTo] : [],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.templated',
            with: ['body' => $this->renderedBody],
        );
    }
}
