<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;

class ArchiveController extends Controller
{
    public function show(int $tahun, int $bulan)
    {
        abort_unless($bulan >= 1 && $bulan <= 12, 404);
        abort_unless($tahun >= 2000 && $tahun <= (int) now()->addYear()->format('Y'), 404);

        $posts = Post::published()
            ->whereYear('published_at', $tahun)
            ->whereMonth('published_at', $bulan)
            ->recent()
            ->with(['author', 'category'])
            ->paginate(12)
            ->withQueryString();

        $label = \Carbon\Carbon::create($tahun, $bulan, 1)->locale('id')->translatedFormat('F Y');

        return view('frontend.archive.show', compact('posts', 'tahun', 'bulan', 'label'));
    }
}
