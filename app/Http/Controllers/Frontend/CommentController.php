<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function store(CommentRequest $request, string $slug)
    {
        // Honeypot: field 'website' harus kosong, jika terisi anggap spam
        if ($request->filled('website')) {
            Log::warning('Spam komentar honeypot', ['slug' => $slug, 'ip' => $request->ip()]);
            return back()->with('success', 'Komentar Anda sudah kami terima dan menunggu moderasi.');
        }

        $post = Post::published()->where('slug', $slug)->firstOrFail();

        if (! $post->allow_comments) {
            return back()->with('error', 'Komentar untuk artikel ini ditutup.');
        }

        $validated = $request->validated();

        Comment::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'parent_id' => $validated['parent_id'] ?? null,
            'author_name' => auth()->check() ? auth()->user()->name : ($validated['author_name'] ?? null),
            'author_email' => auth()->check() ? auth()->user()->email : ($validated['author_email'] ?? null),
            'content' => $validated['content'],
            'status' => 'pending',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Komentar Anda menunggu moderasi. Terima kasih!');
    }

    public function report(Request $request, int $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->update(['status' => 'pending']);

        Log::info('Komentar dilaporkan', [
            'comment_id' => $comment->id,
            'post_id' => $comment->post_id,
            'reporter_ip' => $request->ip(),
            'reason' => $request->string('reason', 'dilaporkan pembaca'),
        ]);

        return back()->with('success', 'Terima kasih, komentar tersebut akan ditinjau moderator.');
    }
}
