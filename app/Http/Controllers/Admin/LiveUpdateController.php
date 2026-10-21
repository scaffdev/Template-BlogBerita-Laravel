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
        $user = auth()->user();
        if (! in_array($user->role, ['editor', 'admin'], true) && $post->user_id !== $user->id) {
            abort(403, 'Anda hanya boleh mengelola liveblog artikel sendiri.');
        }
        $data['post_id'] = $post->id;
        $data['user_id'] = auth()->id();
        $data['is_key_moment'] = $request->boolean('is_key_moment');
        LiveUpdate::create($data);

        return back()->with('success', 'Update liveblog ditambahkan.');
    }

    public function destroy(LiveUpdate $update)
    {
        $user = auth()->user();
        if (! in_array($user->role, ['editor', 'admin'], true) && $update->user_id !== $user->id) {
            abort(403);
        }
        $update->delete();

        return back()->with('success', 'Update liveblog dihapus.');
    }
}
