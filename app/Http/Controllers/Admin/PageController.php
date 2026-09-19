<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
public function create()
    {
        return view('admin.pages.form', ['page' => new Page(['status' => 'published'])]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:pages,slug'],
            'content' => ['required', 'string'],
            'status' => ['required', 'in:draft,published'],
        ]);
        $data['slug'] ??= Str::slug($data['title']);
        Page::create($data);

        return redirect()->route('admin.pages.index')->with('success', 'Halaman dibuat.');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.form', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:pages,slug,'.$page->id],
            'content' => ['required', 'string'],
            'status' => ['required', 'in:draft,published'],
        ]);
        $data['slug'] = Str::slug($data['slug'] ?: $data['title']);
        $page->update($data);

        return redirect()->route('admin.pages.index')->with('success', 'Halaman diperbarui.');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return back()->with('success', 'Halaman dihapus.');
    }
}
