<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $subjectLine,
        public $posts,
        public ?string $body = null,
        public ?string $unsubToken = null
    ) {
    }

    public function build()
    {
        return $this->subject($this->subjectLine)
            ->view('emails.newsletter')
            ->with([
                'subjectLine' => $this->subjectLine,
                'posts' => $this->posts,
                'body' => $this->body,
                'unsubToken' => $this->unsubToken,
            ]);
    }
}
