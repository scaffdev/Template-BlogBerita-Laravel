<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;

class AuthorController extends Controller
{
    public function show(string $username)
    {
        $author = User::where('username', $username)->firstOrFail();

        $posts = Post::published()
            ->where('user_id', $author->id)
            ->recent()
            ->with(['category', 'tags'])
            ->paginate(9)
            ->withQueryString();

        return view('frontend.author.show', compact('author', 'posts'));
    }
}
