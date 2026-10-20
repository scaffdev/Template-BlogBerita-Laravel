<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Models\Category;
use App\Models\Like;
use App\Models\Post;
use App\Models\Redirect;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::published()->recent()->with(['author', 'category', 'tags']);

        if ($request->filled('kategori')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->string('kategori')));
        }

        if ($request->filled('q')) {
            $query->search($request->string('q'));
        }

        $posts = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('frontend.posts.index', compact('posts', 'categories'));
    }

    public function show(string $slug, Request $request)
    {
        $post = Post::published()->where('slug', $slug)->with(['author', 'category', 'tags', 'liveUpdates'])->first();

        if (! $post) {
            // Cek tabel redirects -> 301 ke slug baru
            if (class_exists(Redirect::class)) {
                $redirect = Redirect::where('from_slug', $slug)->first();
                if ($redirect) {
                    return redirect('/artikel/' . ltrim($redirect->to_slug, '/'), $redirect->status_code ?: 301);
                }
            }
            abort(404);
        }

        // Throttling views via session: 1x per 5 menit per post + buffer cache (hemat DB, PRD STAT-1)
        $key = 'viewed_post_' . $post->id;
        if (! session()->has($key) || now()->diffInMinutes(session()->get($key)) >= 5) {
            $bufKey = 'views_buf_' . $post->id;
            $buf = (int) Cache::get($bufKey, 0) + 1;
            Cache::put($bufKey, $buf, now()->addMinutes(10));
            // Tulis ke DB tiap kelipatan 5 atau langsung jika kecil — ringan & tetap akurat
            if ($buf >= 5) {
                $post->increment('views_count', $buf);
                Cache::forget($bufKey);
            } else {
                $post->timestamps = false;
                $post->increment('views_count');
                $post->timestamps = true;
            }
            session()->put($key, now());
            $post->refresh();
        }

        $related = method_exists($post, 'related')
            ? $post->related(4)
            : Post::published()->where('id', '!=', $post->id)
                ->where('category_id', $post->category_id)
                ->latest('published_at')->take(4)->with(['author', 'category'])->get();

        $comments = $post->comments()
            ->where('status', 'approved')
            ->whereNull('parent_id')
            ->with(['replies' => fn ($q) => $q->where('status', 'approved')->latest()])
            ->latest()
            ->get();

        $seo = app(SeoService::class);
        $jsonLd = $seo->jsonLdNewsArticle($post);
        $breadcrumbJsonLd = $seo->jsonLdBreadcrumb($post);

        $series = $post->seriesPosts(6);
        $userId = auth()->id();
        $isBookmarked = $userId ? Bookmark::where('user_id', $userId)->where('post_id', $post->id)->exists() : false;
        $isLiked = $userId ? Like::where('user_id', $userId)->where('post_id', $post->id)->exists() : false;

        return view('frontend.posts.show', compact('post', 'related', 'comments', 'jsonLd', 'breadcrumbJsonLd', 'series', 'isBookmarked', 'isLiked'));
    }

    public function preview(int $id)
    {
        $post = Post::with(['author', 'category', 'tags'])->findOrFail($id);

        // Hanya pemilik / editor / admin
        $user = auth()->user();
        if ($user->id !== $post->user_id && ! in_array($user->role ?? null, ['admin', 'editor'])) {
            abort(403, 'Anda tidak berhak melihat pratinjau ini.');
        }

        $related = collect();
        $comments = collect();
        $series = collect();
        $isBookmarked = false;
        $isLiked = false;
        $seo = app(SeoService::class);
        $jsonLd = $seo->jsonLdNewsArticle($post);
        $breadcrumbJsonLd = $seo->jsonLdBreadcrumb($post);

        return view('frontend.posts.show', compact('post', 'related', 'comments', 'jsonLd', 'breadcrumbJsonLd', 'series', 'isBookmarked', 'isLiked'));
    }
}
