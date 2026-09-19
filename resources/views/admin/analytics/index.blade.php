@extends('layouts.admin')
@section('title', 'Statistik')
@section('content')
<h1 class="text-xl font-bold mb-4">Statistik</h1>
<div class="bg-white rounded shadow p-4 mb-4"><h2 class="font-bold mb-2">Trafik 30 Hari</h2><canvas id="chart30" height="90"></canvas></div>
<div class="grid lg:grid-cols-3 gap-4 mb-4">
<div class="bg-white rounded shadow p-4"><h2 class="font-bold mb-2">Populer Hari Ini</h2><ol class="text-sm list-decimal ml-5 space-y-1">@foreach($today as $p)<li>{{ $p->title }} <span class="text-gray-500">({{ number_format($p->views_count) }})</span></li>@endforeach</ol></div>
<div class="bg-white rounded shadow p-4"><h2 class="font-bold mb-2">Populer Pekan Ini</h2><ol class="text-sm list-decimal ml-5 space-y-1">@foreach($week as $p)<li>{{ $p->title }} <span class="text-gray-500">({{ number_format($p->views_count) }})</span></li>@endforeach</ol></div>
<div class="bg-white rounded shadow p-4"><h2 class="font-bold mb-2">Populer Bulan Ini</h2><ol class="text-sm list-decimal ml-5 space-y-1">@foreach($month as $p)<li>{{ $p->title }} <span class="text-gray-500">({{ number_format($p->views_count) }})</span></li>@endforeach</ol></div>
</div>
<div class="bg-white rounded shadow p-4"><h2 class="font-bold mb-2">Performa Penulis</h2>
<table class="w-full text-sm"><thead><tr class="text-left text-gray-500"><th>Penulis</th><th>Artikel</th><th class="text-right">Views</th></tr></thead><tbody>
@foreach($perAuthor as $a)<tr class="border-t"><td class="py-2">{{ $a->name }}</td><td class="py-2">{{ $a->articles }}</td><td class="py-2 text-right">{{ number_format($a->views ?? 0) }}</td></tr>@endforeach
</tbody></table></div>
@endsection
@push('scripts')
<script>
const labels = @json($labels), values = @json($values);
const c = document.getElementById('chart30');
if (c) { const ctx = c.getContext('2d'); const max = Math.max(1, ...values);
  const W = c.width = c.offsetWidth || 600, H = c.height = 220; ctx.clearRect(0,0,W,H);
  const bw = W / values.length;
  values.forEach((v,i) => { const h = (v/max)*(H-30); ctx.fillStyle = '#16a34a'; ctx.fillRect(i*bw+2, H-20-h, bw-4, h); });
}
</script>
@endpush
