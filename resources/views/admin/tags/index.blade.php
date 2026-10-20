@extends('layouts.admin')
@section('title', 'Tag')
@section('content')
<h1 class="text-xl font-bold mb-4">Tag</h1>
<form method="POST" action="{{ route('admin.tags.store') }}" class="bg-white rounded shadow p-3 mb-4 flex gap-2 text-sm">@csrf<input name="name" required placeholder="Nama tag baru…" class="border rounded px-3 py-2 flex-1"><button class="bg-red-600 text-white px-4 py-2 rounded">+ Tambah</button></form>
<div class="bg-white rounded shadow overflow-x-auto"><table class="w-full text-sm">
<thead><tr class="text-left text-gray-500 border-b"><th class="p-3">Nama</th><th class="p-3">Slug</th><th class="p-3 text-right">Artikel</th><th class="p-3">Aksi</th></tr></thead>
<tbody>@foreach($tags as $t)<tr class="border-b"><td class="p-3 font-medium">{{ $t->name }}</td><td class="p-3 font-mono text-xs">{{ $t->slug }}</td><td class="p-3 text-right">{{ $t->posts_count }}</td>
<td class="p-3"><form method="POST" action="{{ route('admin.tags.update', $t) }}" class="inline">@csrf @method('PUT')<input name="name" value="{{ $t->name }}" class="border rounded px-2 py-1 text-xs w-28"><button class="text-red-700 text-xs ml-1">Ubah</button></form>
<form method="POST" action="{{ route('admin.tags.destroy', $t) }}" class="inline ml-2" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="text-red-600 text-xs">Hapus</button></form></td></tr>@endforeach</tbody>
</table></div>
<div class="mt-4">{{ $tags->links() }}</div>
@endsection
