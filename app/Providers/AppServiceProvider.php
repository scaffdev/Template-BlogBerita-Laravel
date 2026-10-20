<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Tag;
use App\Observers\PostObserver;
use App\Policies\CommentPolicy;
use App\Policies\PostPolicy;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Post::observe(PostObserver::class);

        Gate::policy(Post::class, PostPolicy::class);
        Gate::policy(Comment::class, CommentPolicy::class);

        try {
            $settings = Cache::remember('site_settings', 600, fn () => Setting::all()->pluck('value', 'key')->toArray());
        } catch (\Throwable $e) {
            $settings = [];
        }

        try {
            $menuCategories = Cache::remember('menu_categories', 600, fn () => Category::orderBy('sort_order')->orderBy('name')->limit(10)->get());
        } catch (\Throwable $e) {
            $menuCategories = collect();
        }

        try {
            $hotTags = Cache::remember('hot_tags', 600, fn () => Tag::orderBy('name')->limit(10)->get());
            $tickerPosts = Cache::remember('ticker_posts', 300, fn () => Post::published()->recent()->take(8)->get(['title', 'slug']));
        } catch (\Throwable $e) {
            $hotTags = collect();
            $tickerPosts = collect();
        }

        View::share('siteSettings', $settings);
        View::share('siteName', $settings['site_name'] ?? config('app.name'));
        View::share('menuCategories', $menuCategories);
        View::share('hotTags', $hotTags->isNotEmpty() ? $hotTags : collect());
        View::share('tickerPosts', $tickerPosts);
    }
}
