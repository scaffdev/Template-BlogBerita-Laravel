@extends('layouts.admin')
@section('title', ($page->exists ? 'Edit' : 'Tambah').' Halaman')
@section('content')
<form method="POST" action="{{ $page->exists ? route('admin.pages.update', $page) : route('admin.pages.store') }}" class="bg-white rounded shadow p-4 max-w-3xl space-y-3 text-sm">
@csrf @if($page->exists) @method('PUT') @endif
<label class="block">Judul<input name="title" value="{{ old('title', $page->title) }}" required class="mt-1 w-full border rounded px-3 py-2"></label>
<label class="block">Slug<input name="slug" value="{{ old('slug', $page->slug) }}" class="mt-1 w-full border rounded px-3 py-2 font-mono text-xs"></label>
<label class="block">Status<select name="status" class="mt-1 w-full border rounded px-3 py-2"><option value="published" @selected(old('status',$page->status)=='published')>Terbit</option><option value="draft" @selected(old('status',$page->status)=='draft')>Draft</option></select></label>
<label class="block">Konten<textarea name="content" rows="12" required class="mt-1 w-full border rounded px-3 py-2">{{ old('content', $page->content) }}</textarea></label>
<button class="bg-red-600 text-white px-4 py-2 rounded">Simpan</button>
</form>
@endsection
