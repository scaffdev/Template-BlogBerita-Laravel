<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tag;

class TagController extends Controller
{
    public function show(string $slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $posts = Post::published()
            ->whereHas('tags', fn ($q) => $q->where('tags.slug', $slug)->orWhere('tags.id', $tag->id))
            ->recent()
            ->with(['author', 'category'])
            ->paginate(12)
            ->withQueryString();

        return view('frontend.tag.show', compact('tag', 'posts'));
    }
}
