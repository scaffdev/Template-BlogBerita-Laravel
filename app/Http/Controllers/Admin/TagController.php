<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
public function store(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:60', 'unique:tags,name']]);
        Tag::create(['name' => $data['name'], 'slug' => Str::slug($data['name'])]);

        return back()->with('success', 'Tag dibuat.');
    }

    public function update(Request $request, Tag $tag)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:60', 'unique:tags,name,'.$tag->id]]);
        $tag->update(['name' => $data['name'], 'slug' => Str::slug($data['name'])]);

        return back()->with('success', 'Tag diperbarui.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return back()->with('success', 'Tag dihapus.');
    }
}
