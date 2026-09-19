@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
<div class="bg-white rounded shadow p-4"><div class="text-sm text-gray-500">Artikel Terbit</div><div class="text-3xl font-bold">{{ $totalPublished }}</div></div>
<div class="bg-white rounded shadow p-4"><div class="text-sm text-gray-500">Draft</div><div class="text-3xl font-bold">{{ $totalDraft }}</div></div>
<div class="bg-white rounded shadow p-4"><div class="text-sm text-gray-500">Menunggu Review</div><div class="text-3xl font-bold text-amber-600">{{ $pendingReview }}</div></div>
<div class="bg-white rounded shadow p-4"><div class="text-sm text-gray-500">Total Views</div><div class="text-3xl font-bold">{{ number_format($totalViews) }}</div></div>
</div>
<div class="grid lg:grid-cols-2 gap-4 mb-6">
<div class="bg-white rounded shadow p-4">
<h2 class="font-bold mb-3">Menunggu Review</h2>
<table class="w-full text-sm"><tbody>
@forelse($latestPending as $p)<tr class="border-t"><td class="py-2"><a class="text-blue-600" href="{{ route('admin.posts.edit', $p) }}">{{ $p->title }}</a><div class="text-xs text-gray-500">{{ $p->author->name ?? '' }} · {{ $p->created_at->diffForHumans() }}</div></td></tr>@empty<tr><td class="py-2 text-gray-500">Tidak ada antrean.</td></tr>@endforelse
</tbody></table>
</div>
<div class="bg-white rounded shadow p-4">
<h2 class="font-bold mb-3">Terpopuler</h2>
<table class="w-full text-sm"><tbody>
@forelse($popular as $p)<tr class="border-t"><td class="py-2">{{ $p->title }}</td><td class="py-2 text-right font-semibold">{{ number_format($p->views_count) }}</td></tr>@empty<tr><td class="py-2 text-gray-500">Belum ada data.</td></tr>@endforelse
</tbody></table>
</div>
</div>
<div class="bg-white rounded shadow p-4 mb-6">
<h2 class="font-bold mb-3">Trafik 14 Hari</h2>
<canvas id="chart14" height="90"></canvas>
</div>
<div class="bg-white rounded shadow p-4">
<h2 class="font-bold mb-3">Top Penulis</h2>
<table class="w-full text-sm"><thead><tr class="text-left text-gray-500"><th>Penulis</th><th>Artikel</th><th class="text-right">Views</th></tr></thead><tbody>
@foreach($topAuthors as $a)<tr class="border-t"><td class="py-2">{{ $a->name }}</td><td class="py-2">{{ $a->published_posts_count ?? 0 }}</td><td class="py-2 text-right">{{ number_format($a->total_views ?? 0) }}</td></tr>@endforeach
</tbody></table>
</div>
@endsection
@push('scripts')
<script>
const labels = @json($labels), values = @json($values);
const c = document.getElementById('chart14');
if (c) {
  const ctx = c.getContext('2d');
  const max = Math.max(1, ...values);
  const W = c.width = c.offsetWidth || 600, H = c.height = 220;
  ctx.clearRect(0,0,W,H);
  const bw = W / values.length;
  values.forEach((v,i) => {
    const h = (v/max) * (H-30);
    ctx.fillStyle = '#2563eb';
    ctx.fillRect(i*bw+4, H-20-h, bw-8, h);
  });
  ctx.fillStyle = '#666'; ctx.font = '10px sans-serif';
  ctx.fillText(labels[0] ?? '', 4, H-5);
  ctx.fillText(labels[labels.length-1] ?? '', W-70, H-5);
}
</script>
@endpush
