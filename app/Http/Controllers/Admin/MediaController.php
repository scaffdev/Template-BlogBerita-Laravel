<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\ImageService;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    private function ensurePrivileged(): void
    {
        if (! in_array(auth()->user()->role, ['editor', 'admin'], true)) {
            abort(403, 'Hanya editor dan admin yang boleh mengelola media.');
        }
    }

    public function index()
    {
        $media = Media::latest()->paginate(24);

        return view('admin.media.index', compact('media'));
    }

    public function store(Request $request, ImageService $images)
    {
        $request->validate([
            'files' => ['required', 'array'],
            'files.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:255'],
            'credit' => ['nullable', 'string', 'max:255'],
        ]);
        foreach ($request->file('files') as $file) {
            $path = $images->upload($file, 'media');
            Media::create([
                'file_name' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'alt_text' => $request->input('alt_text'),
                'caption' => $request->input('caption'),
                'credit' => $request->input('credit'),
                'user_id' => auth()->id(),
            ]);
        }

        return back()->with('success', 'Gambar berhasil diunggah.');
    }

    public function update(Request $request, Media $medium)
    {
        $this->ensurePrivileged();
        $data = $request->validate([
            'alt_text' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:255'],
            'credit' => ['nullable', 'string', 'max:255'],
        ]);
        $medium->update($data);

        return back()->with('success', 'Metadata media diperbarui.');
    }

    public function destroy(Media $medium, ImageService $images)
    {
        $this->ensurePrivileged();
        $images->delete($medium->file_name);
        $medium->delete();

        return back()->with('success', 'Media dihapus.');
    }
}
