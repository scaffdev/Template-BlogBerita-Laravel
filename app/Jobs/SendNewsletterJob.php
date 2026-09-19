<?php

namespace App\Jobs;

use App\Mail\NewsletterMail;
use App\Models\Post;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNewsletterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $subject,
        public array $postIds = [],
        public ?string $body = null
    ) {
    }

    public function handle(): void
    {
        $posts = Post::whereIn('id', $this->postIds)->get();
        if ($posts->isEmpty()) {
            $posts = Post::where('status', 'published')->latest('published_at')->limit(5)->get();
        }
        Subscriber::where('is_confirmed', true)->whereNull('unsubscribed_at')
            ->chunkById(200, function ($subs) use ($posts) {
                foreach ($subs as $sub) {
                    try {
                        Mail::to($sub->email)->send(new NewsletterMail($this->subject, $posts, $this->body, $sub->token));
                    } catch (\Throwable $e) {
                    }
                }
            });
    }
}
