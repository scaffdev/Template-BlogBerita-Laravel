@extends('layouts.admin')
@section('title', 'Artikel')
@section('content')
<div class="flex justify-between items-center mb-4">
<h1 class="text-xl font-bold">Artikel</h1>
<a href="{{ route('admin.posts.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Tulis Artikel</a>
</div>
<form method="GET" class="bg-white rounded shadow p-3 mb-4 flex flex-wrap gap-2 text-sm">
<input name="q" value="{{ request('q') }}" placeholder="Cari judul/slug…" class="border rounded px-3 py-2 flex-1 min-w-[200px]">
<select name="status" class="border rounded px-3 py-2"><option value="">Semua status</option>@foreach(['draft','pending','published','archived'] as $s)<option value="{{ $s }}" @selected(request('status')==$s)>{{ ucfirst($s) }}</option>@endforeach</select>
<select name="category_id" class="border rounded px-3 py-2"><option value="">Semua kategori</option>@foreach($categories as $c)<option value="{{ $c->id }}" @selected(request('category_id')==$c->id)>{{ $c->name }}</option>@endforeach</select>
<button class="bg-gray-900 text-white px-4 py-2 rounded">Filter</button>
</form>
<div class="bg-white rounded shadow overflow-x-auto">
<table class="w-full text-sm">
<thead><tr class="text-left text-gray-500 border-b"><th class="p-3">Judul</th><th class="p-3">Penulis</th><th class="p-3">Status</th><th class="p-3 text-right">Views</th><th class="p-3">Aksi</th></tr></thead>
<tbody>
@foreach($posts as $p)
<tr class="border-b hover:bg-gray-50">
<td class="p-3"><a href="{{ route('admin.posts.edit', $p) }}" class="text-blue-600 font-medium">{{ $p->title }}</a><div class="text-xs text-gray-500">{{ $p->slug }} · {{ $p->category->name ?? '-' }}</div></td>
<td class="p-3">{{ $p->author->name ?? '-' }}</td>
<td class="p-3"><span class="px-2 py-1 rounded text-xs bg-gray-200">{{ $p->status }}</span></td>
<td class="p-3 text-right">{{ number_format($p->views_count ?? 0) }}</td>
<td class="p-3 whitespace-nowrap">
<a href="{{ route('admin.posts.edit', $p) }}" class="text-blue-600 mr-2">Edit</a>
@if($p->trashed())<form method="POST" action="{{ route('admin.posts.restore', $p->id) }}" class="inline">@csrf<button class="text-green-600 mr-2">Restore</button></form>
@else<form method="POST" action="{{ route('admin.posts.destroy', $p) }}" class="inline" onsubmit="return confirm('Pindah ke sampah?')">@csrf @method('DELETE')<button class="text-red-600">Hapus</button></form>@endif
</td>
</tr>
@endforeach
</tbody>
</table>
</div>
<div class="mt-4">{{ $posts->links() }}</div>
@endsection
