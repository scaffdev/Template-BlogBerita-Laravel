<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;

class FeedController extends Controller
{
    public function feed()
    {
        $posts = Cache::remember('feed_rss', 600, fn () => Post::published()->recent()->with(['author', 'category'])->take(20)->get());

        return response()->view('feed.rss', compact('posts'), 200, [
            'Content-Type' => 'application/rss+xml; charset=UTF-8',
        ]);
    }

    public function sitemap()
    {
        $posts = Cache::remember('sitemap_posts', 600, fn () => Post::published()->recent()->take(1000)->get(['slug', 'updated_at']));
        $categories = Category::all(['slug', 'updated_at']);
        $tags = Tag::all(['slug']);
        $pages = class_exists(Page::class)
            ? Page::where('status', 'published')->get(['slug', 'updated_at'])
            : collect();

        return response()->view('feed.sitemap', compact('posts', 'categories', 'tags', 'pages'), 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    public function newsSitemap()
    {
        $posts = Post::published()
            ->where('published_at', '>=', now()->subDays(2))
            ->recent()
            ->with(['author'])
            ->take(100)->get();

        return response()->view('feed.news', compact('posts'), 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    public function robots()
    {
        $content = "User-agent: *\nAllow: /\n\nSitemap: " . url('/sitemap.xml') . "\n";

        return response($content, 200, ['Content-Type' => 'text/plain']);
    }
}
