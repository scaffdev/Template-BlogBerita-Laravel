<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Setting;
use App\Observers\PostObserver;
use App\Policies\CommentPolicy;
use App\Policies\PostPolicy;
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
            $settings = Setting::all()->pluck('value', 'key')->toArray();
        } catch (\Throwable $e) {
            $settings = [];
        }

        try {
            $menuCategories = Category::orderBy('sort_order')->orderBy('name')->limit(10)->get();
        } catch (\Throwable $e) {
            $menuCategories = collect();
        }

        View::share('siteSettings', $settings);
        View::share('menuCategories', $menuCategories);
    }
}
