<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $kategori = (string) $request->query('kategori', '');
        $sort = (string) $request->query('sort', 'relevan');

        $query = Post::published()->with(['author', 'category']);

        if ($q !== '') {
            $query->search($q);
        }

        if ($kategori !== '') {
            $query->whereHas('category', fn ($c) => $c->where('slug', $kategori));
        }

        match ($sort) {
            'terbaru' => $query->recent(),
            'populer' => $query->popular(30),
            default => $q === '' ? $query->recent() : null,
        };

        $posts = ($q !== '' || $kategori !== '')
            ? $query->paginate(12)->withQueryString()
            : Post::query()->whereRaw('1 = 0')->paginate(12);

        // Jika kosong / tanpa query: tampilkan populer sebagai saran
        $suggestions = Post::published()->popular(30)->with(['author', 'category'])->take(5)->get();

        $categories = Category::orderBy('name')->get();

        return view('frontend.search.index', compact('posts', 'suggestions', 'categories', 'q', 'kategori', 'sort'));
    }
}
