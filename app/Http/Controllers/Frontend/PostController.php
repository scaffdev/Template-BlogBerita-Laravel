<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Redirect;
use App\Services\SeoService;
use Illuminate\Http\Request;

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
        $post = Post::published()->where('slug', $slug)->with(['author', 'category', 'tags'])->first();

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

        // Throttling views via session: 1x per 5 menit per post
        $key = 'viewed_post_' . $post->id;
        if (! session()->has($key) || now()->diffInMinutes(session()->get($key)) >= 5) {
            if (method_exists($post, 'incrementViews')) {
                $post->incrementViews();
            } else {
                $post->increment('views_count');
            }
            session()->put($key, now());
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

        return view('frontend.posts.show', compact('post', 'related', 'comments', 'jsonLd', 'breadcrumbJsonLd'));
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
        $seo = app(SeoService::class);
        $jsonLd = $seo->jsonLdNewsArticle($post);
        $breadcrumbJsonLd = $seo->jsonLdBreadcrumb($post);

        return view('frontend.posts.show', compact('post', 'related', 'comments', 'jsonLd', 'breadcrumbJsonLd'));
    }
}
