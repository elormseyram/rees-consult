<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactReply extends Mailable
{
    use Queueable, SerializesModels;

    public $contactMessage;
    public $replyBody;

    public function __construct(ContactMessage $contactMessage, $replyBody)
    {
        $this->contactMessage = $contactMessage;
        $this->replyBody = $replyBody;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Re: ' . $this->contactMessage->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact.reply',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
