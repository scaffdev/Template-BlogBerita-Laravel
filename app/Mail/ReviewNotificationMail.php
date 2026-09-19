<?php

namespace App\Mail;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Post $post)
    {
    }

    public function build()
    {
        return $this->subject('[Review] '.$this->post->title)
            ->view('emails.review')
            ->with(['post' => $this->post]);
    }
}
