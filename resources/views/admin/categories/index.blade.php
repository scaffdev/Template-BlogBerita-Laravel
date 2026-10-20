@extends('layouts.admin')
@section('title', 'Kategori')
@section('content')
<div class="flex justify-between items-center mb-4"><h1 class="text-xl font-bold">Kategori</h1><a href="{{ route('admin.categories.create') }}" class="bg-red-600 text-white px-4 py-2 rounded">+ Kategori</a></div>
<div class="bg-white rounded shadow overflow-x-auto"><table class="w-full text-sm">
<thead><tr class="text-left text-gray-500 border-b"><th class="p-3">Nama</th><th class="p-3">Slug</th><th class="p-3">Induk</th><th class="p-3 text-right">Artikel</th><th class="p-3">Aksi</th></tr></thead>
<tbody>@foreach($categories as $c)<tr class="border-b"><td class="p-3 font-medium">{{ $c->name }}</td><td class="p-3 font-mono text-xs">{{ $c->slug }}</td><td class="p-3">{{ $c->parent->name ?? '-' }}</td><td class="p-3 text-right">{{ $c->posts_count }}</td><td class="p-3"><a href="{{ route('admin.categories.edit', $c) }}" class="text-red-700 mr-2">Edit</a><form method="POST" action="{{ route('admin.categories.destroy', $c) }}" class="inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="text-red-600">Hapus</button></form></td></tr>@endforeach</tbody>
</table></div>
<div class="mt-4">{{ $categories->links() }}</div>
@endsection
