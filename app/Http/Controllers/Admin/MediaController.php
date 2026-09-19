<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\ImageService;
use Illuminate\Http\Request;

class MediaController extends Controller
{
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
            ]);
        }

        return back()->with('success', 'Gambar berhasil diunggah.');
    }

    public function update(Request $request, Media $medium)
    {
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
        $images->delete($medium->file_name);
        $medium->delete();

        return back()->with('success', 'Media dihapus.');
    }
}
