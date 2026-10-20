<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /** GET /api/posts — JSON publik: daftar artikel terbit. */
    public function posts(Request $request)
    {
        $q = Post::published()->recent()->with(['author:id,name,username', 'category:id,name,slug']);

        if ($request->filled('q')) {
            $q->searchSmart((string) $request->string('q'));
        }
        if ($request->filled('kategori')) {
            $q->whereHas('category', fn ($c) => $c->where('slug', $request->string('kategori')));
        }
        if ($request->filled('tag')) {
            $q->whereHas('tags', fn ($t) => $t->where('slug', $request->string('tag')));
        }

        $posts = $q->paginate(min(30, max(1, $request->integer('per_page', 10))));

        return response()->json([
            'data' => $posts->map(fn ($p) => [
                'title' => $p->title,
                'slug' => $p->slug,
                'url' => url('/artikel/'.$p->slug),
                'excerpt' => $p->excerptOrGenerate(),
                'category' => $p->category?->name,
                'author' => $p->author?->name,
                'published_at' => optional($p->published_at)->toIso8601String(),
                'views' => $p->views_count,
                'reading_time' => $p->reading_time,
                'image' => $p->featured_image ? asset('storage/'.ltrim($p->featured_image, '/')) : null,
            ]),
            'meta' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'total' => $posts->total(),
            ],
        ]);
    }

    /** GET /api/meta — kategori + tag untuk konsumen API/widget. */
    public function meta()
    {
        return response()->json([
            'categories' => Category::orderBy('name')->get(['name', 'slug']),
            'tags' => Tag::orderBy('name')->limit(50)->get(['name', 'slug']),
        ]);
    }
}
