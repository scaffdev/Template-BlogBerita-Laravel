@extends('layouts.admin')
@section('title', 'Media Library')
@section('content')
<h1 class="text-xl font-bold mb-4">Media Library</h1>
<form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="bg-white rounded shadow p-4 mb-4 text-sm border-2 border-dashed border-gray-300" id="drop">
@csrf
<input type="file" name="files[]" multiple accept="image/*" class="w-full" id="files">
<div class="grid md:grid-cols-3 gap-2 mt-2">
<input name="alt_text" placeholder="Alt text (SEO)" class="border rounded px-3 py-2">
<input name="caption" placeholder="Keterangan" class="border rounded px-3 py-2">
<input name="credit" placeholder="Kredit/sumber foto" class="border rounded px-3 py-2">
</div>
<button class="mt-2 bg-blue-600 text-white px-4 py-2 rounded">Upload</button>
</form>
<div class="grid grid-cols-2 md:grid-cols-4 gap-3">
@foreach($media as $m)
<div class="bg-white rounded shadow overflow-hidden text-xs">
<img src="{{ str_starts_with($m->file_name,'http') ? $m->file_name : asset('storage/'.$m->file_name) }}" alt="{{ $m->alt_text }}" class="w-full h-32 object-cover" loading="lazy">
<div class="p-2">
<div class="font-mono truncate">{{ basename($m->file_name) }}</div>
<div class="text-gray-500 truncate">{{ $m->alt_text ?: '— tanpa alt —' }}</div>
<button onclick="document.getElementById('m{{ $m->id }}').classList.toggle('hidden')" class="text-blue-600 mt-1">Edit alt/caption</button>
<form id="m{{ $m->id }}" method="POST" action="{{ route('admin.media.update', $m) }}" class="hidden mt-2 space-y-1">@csrf @method('PUT')
<input name="alt_text" value="{{ $m->alt_text }}" placeholder="Alt" class="w-full border rounded px-2 py-1">
<input name="caption" value="{{ $m->caption }}" placeholder="Caption" class="w-full border rounded px-2 py-1">
<input name="credit" value="{{ $m->credit }}" placeholder="Credit" class="w-full border rounded px-2 py-1">
<button class="bg-gray-900 text-white px-2 py-1 rounded">Simpan</button>
</form>
<form method="POST" action="{{ route('admin.media.destroy', $m) }}" class="mt-1" onsubmit="return confirm('Hapus file?')">@csrf @method('DELETE')<button class="text-red-600">Hapus</button></form>
</div>
</div>
@endforeach
</div>
<div class="mt-4">{{ $media->links() }}</div>
@endsection
