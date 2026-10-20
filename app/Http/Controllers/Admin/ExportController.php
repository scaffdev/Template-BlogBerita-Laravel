<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function postsCsv(): StreamedResponse
    {
        $rows = Post::with(['author', 'category'])->orderByDesc('views_count')->limit(2000)->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Judul', 'Slug', 'Status', 'Kategori', 'Penulis', 'Views', 'Likes', 'Disimpan', 'Terbit']);
            foreach ($rows as $p) {
                fputcsv($out, [
                    $p->title, $p->slug, $p->status,
                    $p->category?->name, $p->author?->name,
                    $p->views_count, $p->likes_count ?? 0, $p->bookmarks_count ?? 0,
                    optional($p->published_at)->format('Y-m-d H:i'),
                ]);
            }
            fclose($out);
        }, 'laporan-artikel-'.date('Ymd').'.csv', ['Content-Type' => 'text/csv']);
    }

    public function authorsCsv(): StreamedResponse
    {
        $rows = User::whereIn('role', ['author', 'editor', 'admin'])
            ->withCount(['posts as published_count' => fn ($q) => $q->where('status', 'published')])
            ->withSum(['posts as total_views' => fn ($q) => $q->where('status', 'published')], 'views_count')
            ->orderByDesc('total_views')->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Nama', 'Username', 'Peran', 'Artikel Terbit', 'Total Views']);
            foreach ($rows as $a) {
                fputcsv($out, [$a->name, $a->username, $a->role, $a->published_count, $a->total_views ?? 0]);
            }
            fclose($out);
        }, 'laporan-penulis-'.date('Ymd').'.csv', ['Content-Type' => 'text/csv']);
    }
}
