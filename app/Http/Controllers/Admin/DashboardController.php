<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPublished = Post::where('status', 'published')->count();
        $totalDraft = Post::where('status', 'draft')->count();
        $pendingReview = Post::where('status', 'pending')->count();
        $totalViews = (int) Post::sum('views_count');

        $popular = Post::with('category')->where('status', 'published')
            ->orderByDesc('views_count')->limit(5)->get();
        $latestPending = Post::with(['author', 'category'])->where('status', 'pending')
            ->latest()->limit(5)->get();

        $views14 = Post::select(DB::raw('DATE(published_at) as d'), DB::raw('SUM(views_count) as v'))
            ->where('status', 'published')
            ->where('published_at', '>=', now()->subDays(14))
            ->groupBy('d')->orderBy('d')->get();
        $labels = [];
        $values = [];
        for ($i = 13; $i >= 0; $i--) {
            $day = now()->subDays($i)->toDateString();
            $labels[] = $day;
            $row = $views14->firstWhere('d', $day);
            $values[] = (int) ($row->v ?? 0);
        }

        $topAuthors = User::whereIn('role', ['author', 'editor', 'admin'])
            ->withCount(['posts as published_posts_count' => fn ($q) => $q->where('status', 'published')])
            ->withSum(['posts as total_views' => fn ($q) => $q->where('status', 'published')], 'views_count')
            ->orderByDesc('total_views')->limit(5)->get();

        return view('admin.dashboard', compact(
            'totalPublished', 'totalDraft', 'pendingReview', 'totalViews',
            'popular', 'latestPending', 'labels', 'values', 'topAuthors'
        ));
    }
}
