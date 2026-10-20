<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Request $request, string $slug)
    {
        $post = Post::published()->where('slug', $slug)->firstOrFail();

        $existing = Like::where('user_id', auth()->id())->where('post_id', $post->id)->first();
        if ($existing) {
            $existing->delete();
            $post->decrement('likes_count');
            $liked = false;
        } else {
            Like::create(['user_id' => auth()->id(), 'post_id' => $post->id]);
            $post->increment('likes_count');
            $liked = true;
        }

        if ($request->wantsJson()) {
            return response()->json(['liked' => $liked, 'count' => $post->fresh()->likes_count]);
        }

        return back();
    }
}
