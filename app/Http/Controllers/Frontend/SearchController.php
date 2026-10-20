<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $kategori = (string) $request->query('kategori', '');
        $sort = (string) $request->query('sort', 'relevan');
        $dari = (string) $request->query('dari', '');
        $sampai = (string) $request->query('sampai', '');
        $penulis = (string) $request->query('penulis', '');

        $query = Post::published()->with(['author', 'category']);

        if ($q !== '') {
            $query->searchSmart($q);
        }

        if ($kategori !== '') {
            $query->whereHas('category', fn ($c) => $c->where('slug', $kategori));
        }

        if ($penulis !== '') {
            $query->whereHas('author', fn ($a) => $a->where('username', $penulis)->orWhere('name', 'like', "%{$penulis}%"));
        }

        if ($dari !== '' && strtotime($dari)) {
            $query->whereDate('published_at', '>=', $dari);
        }
        if ($sampai !== '' && strtotime($sampai)) {
            $query->whereDate('published_at', '<=', $sampai);
        }

        match ($sort) {
            'terbaru' => $query->recent(),
            'populer' => $query->orderByDesc('views_count')->orderByDesc('published_at'),
            default => $q === '' ? $query->recent() : $query->orderByDesc('published_at'),
        };

        $hasFilter = $q !== '' || $kategori !== '' || $penulis !== '' || $dari !== '' || $sampai !== '';
        $posts = $hasFilter
            ? $query->paginate(12)->withQueryString()
            : Post::query()->whereRaw('1 = 0')->paginate(12);

        $suggestions = Post::published()->popular(30)->with(['author', 'category'])->take(6)->get();
        $categories = Category::orderBy('name')->get();
        $authors = User::whereIn('role', ['author', 'editor', 'admin'])->orderBy('name')->limit(50)->get(['name', 'username']);

        return view('frontend.search.index', compact('posts', 'suggestions', 'categories', 'authors', 'q', 'kategori', 'sort', 'dari', 'sampai', 'penulis'));
    }

    /** Autocomplete JSON untuk search-as-you-type (CARI-4). */
    public function suggest(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $posts = Post::published()
            ->searchSmart($q)
            ->with(['category'])
            ->orderByDesc('views_count')
            ->take(7)
            ->get(['id', 'title', 'slug', 'category_id', 'views_count']);

        return response()->json($posts->map(fn ($p) => [
            'title' => $p->title,
            'url' => url('/artikel/'.$p->slug),
            'category' => $p->category?->name,
            'views' => $p->views_count,
        ])->values());
    }
}
