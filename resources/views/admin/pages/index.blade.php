@extends('layouts.admin')
@section('title', 'Halaman Statis')
@section('content')
<div class="flex justify-between items-center mb-4"><h1 class="text-xl font-bold">Halaman</h1><a href="{{ route('admin.pages.create') }}" class="bg-red-600 text-white px-4 py-2 rounded">+ Halaman</a></div>
<div class="bg-white rounded shadow overflow-x-auto"><table class="w-full text-sm">
<thead><tr class="text-left text-gray-500 border-b"><th class="p-3">Judul</th><th class="p-3">Slug</th><th class="p-3">Status</th><th class="p-3">Aksi</th></tr></thead>
<tbody>@foreach($pages as $p)<tr class="border-b"><td class="p-3 font-medium">{{ $p->title }}</td><td class="p-3 font-mono text-xs">/{{ $p->slug }}</td><td class="p-3">{{ $p->status }}</td><td class="p-3"><a href="{{ route('admin.pages.edit', $p) }}" class="text-red-700 mr-2">Edit</a><form method="POST" action="{{ route('admin.pages.destroy', $p) }}" class="inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="text-red-600">Hapus</button></form></td></tr>@endforeach</tbody>
</table></div>
<div class="mt-4">{{ $pages->links() }}</div>
@endsection
