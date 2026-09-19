<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        $headlines = Post::published()->headline()->latest('published_at')->take(3)->with(['author', 'category'])->get();

        $featured = Post::published()->featured()->latest('published_at')->take(4)->with(['author', 'category'])->get();

        $latest = Post::published()->recent()->with(['author', 'category'])->paginate(9);

        $popular = Post::published()->popular(30)->with(['author', 'category'])->take(5)->get();

        // 4 kategori teratas yang punya post published + 4 post each
        $categories = Category::query()
            ->withCount(['posts' => fn ($q) => $q->published()])
            ->orderByDesc('posts_count')
            ->take(4)
            ->get()
            ->map(function (Category $category) {
                $category->setRelation(
                    'latestPosts',
                    Post::published()->where('category_id', $category->id)
                        ->latest('published_at')->take(4)->with(['author', 'category'])->get()
                );

                return $category;
            });

        return view('frontend.home', compact('headlines', 'featured', 'latest', 'popular', 'categories'));
    }
}
