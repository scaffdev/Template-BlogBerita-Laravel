<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Models\Post;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function index()
    {
        $posts = Post::published()
            ->whereIn('id', Bookmark::where('user_id', auth()->id())->pluck('post_id'))
            ->with(['author', 'category'])
            ->recent()
            ->paginate(12);

        return view('frontend.bookmarks.index', compact('posts'));
    }

    public function toggle(Request $request, string $slug)
    {
        $post = Post::published()->where('slug', $slug)->firstOrFail();
        $userId = auth()->id();

        $existing = Bookmark::where('user_id', $userId)->where('post_id', $post->id)->first();
        if ($existing) {
            $existing->delete();
            $post->decrement('bookmarks_count');
            $saved = false;
        } else {
            Bookmark::create(['user_id' => $userId, 'post_id' => $post->id]);
            $post->increment('bookmarks_count');
            $saved = true;
        }

        if ($request->wantsJson()) {
            return response()->json(['saved' => $saved, 'count' => $post->fresh()->bookmarks_count]);
        }

        return back()->with('success', $saved ? 'Artikel disimpan ke koleksi Anda.' : 'Artikel dihapus dari simpanan.');
    }
}
