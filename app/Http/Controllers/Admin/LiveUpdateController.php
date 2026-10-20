<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LiveUpdate;
use App\Models\Post;
use Illuminate\Http\Request;

class LiveUpdateController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $data = $request->validate([
            'content' => ['required', 'string', 'max:2000'],
            'is_key_moment' => ['nullable', 'boolean'],
        ]);
        $data['post_id'] = $post->id;
        $data['user_id'] = auth()->id();
        $data['is_key_moment'] = $request->boolean('is_key_moment');
        LiveUpdate::create($data);

        return back()->with('success', 'Update liveblog ditambahkan.');
    }

    public function destroy(LiveUpdate $update)
    {
        $update->delete();

        return back()->with('success', 'Update liveblog dihapus.');
    }
}
