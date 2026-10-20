<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendNewsletterJob;
use App\Mail\NewsletterMail;
use App\Models\NewsletterLog;
use App\Models\Post;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SubscriberController extends Controller
{
    public function index()
    {
        $subscribers = Subscriber::latest()->paginate(30);
        $total = Subscriber::count();
        $confirmed = Subscriber::where('is_confirmed', true)->whereNull('unsubscribed_at')->count();

        return view('admin.subscribers.index', compact('subscribers', 'total', 'confirmed'));
    }

    public function destroy(Subscriber $subscriber)
    {
        $subscriber->delete();

        return back()->with('success', 'Subscriber dihapus.');
    }

    public function broadcastForm()
    {
        $posts = Post::where('status', 'published')->latest('published_at')->limit(10)->get();

        return view('admin.subscribers.broadcast', compact('posts'));
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'post_ids' => ['nullable', 'array'],
            'post_ids.*' => ['exists:posts,id'],
            'body' => ['nullable', 'string'],
        ]);
        $posts = ! empty($data['post_ids'])
            ? Post::whereIn('id', $data['post_ids'])->get()
            : Post::where('status', 'published')->latest('published_at')->limit(5)->get();

        // Diproses via job (queue sync = langsung jalan bila queue=sync)
        SendNewsletterJob::dispatch($data['subject'], $posts->pluck('id')->all(), $data['body'] ?? null);

        try {
            NewsletterLog::create([
                'subject' => $data['subject'],
                'body' => $data['body'] ?? null,
                'recipients_count' => Subscriber::where('is_confirmed', true)->whereNull('unsubscribed_at')->count(),
                'created_by' => auth()->id(),
            ]);
        } catch (\Throwable $e) {
        }

        // Fallback langsung bila queue sync belum worker: kirim sinkron ke confirmed
        if (config('queue.default') === 'sync') {
            $subs = Subscriber::where('is_confirmed', true)->whereNull('unsubscribed_at')->pluck('email');
            foreach ($subs as $email) {
                try {
                    Mail::to($email)->send(new NewsletterMail($data['subject'], $posts, $data['body'] ?? null, md5($email)));
                } catch (\Throwable $e) {
                }
            }
        }

        return redirect()->route('admin.subscribers.index')->with('success', 'Broadcast newsletter dikirim ke antrean.');
    }
}
