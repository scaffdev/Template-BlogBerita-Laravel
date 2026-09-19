<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $today = Post::where('status', 'published')->orderByDesc('views_count')->limit(10)->get();
        $week = Post::where('status', 'published')->where('published_at', '>=', now()->subWeek())->orderByDesc('views_count')->limit(10)->get();
        $month = Post::where('status', 'published')->where('published_at', '>=', now()->subMonth())->orderByDesc('views_count')->limit(10)->get();

        $perAuthor = User::whereIn('role', ['author', 'editor', 'admin'])
            ->withCount(['posts as published_count' => fn ($q) => $q->where('status', 'published')])
            ->withSum(['posts as total_views' => fn ($q) => $q->where('status', 'published')], 'views_count')
            ->orderByDesc('total_views')->limit(20)->get();

        $rows = Post::select(DB::raw('DATE(published_at) as d'), DB::raw('SUM(views_count) as v'))
            ->where('status', 'published')->where('published_at', '>=', now()->subDays(30))
            ->groupBy('d')->orderBy('d')->get();
        $labels = [];
        $values = [];
        for ($i = 29; $i >= 0; $i--) {
            $day = now()->subDays($i)->toDateString();
            $labels[] = $day;
            $values[] = (int) ($rows->firstWhere('d', $day)->v ?? 0);
        }

        return view('admin.analytics.index', compact('today', 'week', 'month', 'perAuthor', 'labels', 'values'));
    }
}
