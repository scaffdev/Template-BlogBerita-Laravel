@extends('layouts.admin')
@section('title', 'Redirect')
@section('content')
<h1 class="text-xl font-bold mb-4">Redirect Slug (SEO)</h1>
<form method="POST" action="{{ route('admin.redirects.store') }}" class="bg-white rounded shadow p-3 mb-4 flex flex-wrap gap-2 text-sm">@csrf
<input name="from_slug" required placeholder="slug-lama" class="border rounded px-3 py-2 flex-1 font-mono text-xs">
<input name="to_slug" required placeholder="slug-baru" class="border rounded px-3 py-2 flex-1 font-mono text-xs">
<select name="status_code" class="border rounded px-3 py-2"><option value="301">301</option><option value="302">302</option></select>
<button class="bg-blue-600 text-white px-4 py-2 rounded">+ Tambah</button></form>
<div class="bg-white rounded shadow overflow-x-auto"><table class="w-full text-sm">
<thead><tr class="text-left text-gray-500 border-b"><th class="p-3">Dari</th><th class="p-3">Ke</th><th class="p-3">Kode</th><th class="p-3">Aksi</th></tr></thead>
<tbody>@foreach($redirects as $r)<tr class="border-b"><td class="p-3 font-mono text-xs">/{{ $r->from_slug }}</td><td class="p-3 font-mono text-xs">/{{ $r->to_slug }}</td><td class="p-3">{{ $r->status_code }}</td><td class="p-3"><form method="POST" action="{{ route('admin.redirects.destroy', $r) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="text-red-600">Hapus</button></form></td></tr>@endforeach</tbody>
</table></div>
<div class="mt-4">{{ $redirects->links() }}</div>
@endsection
