<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WebsiteStayHelpEmail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public int $timeSpent;

    public function __construct(User $user, int $timeSpentMinutes)
    {
        $this->user = $user;
        $this->timeSpent = $timeSpentMinutes;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Need Any Help? We\'re Here for You!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.website-stay-help',
        );
    }
}