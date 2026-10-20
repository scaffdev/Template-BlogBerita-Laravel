@extends('layouts.admin')
@section('title', ($category->exists ? 'Edit' : 'Tambah').' Kategori')
@section('content')
<form method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" class="bg-white rounded shadow p-4 max-w-xl space-y-3 text-sm">
@csrf @if($category->exists) @method('PUT') @endif
<label class="block">Nama<input name="name" value="{{ old('name', $category->name) }}" required class="mt-1 w-full border rounded px-3 py-2"></label>
<label class="block">Slug (kosongkan = otomatis)<input name="slug" value="{{ old('slug', $category->slug) }}" class="mt-1 w-full border rounded px-3 py-2 font-mono text-xs"></label>
<label class="block">Induk<select name="parent_id" class="mt-1 w-full border rounded px-3 py-2"><option value="">— Tanpa induk —</option>@foreach($parents as $p)<option value="{{ $p->id }}" @selected(old('parent_id',$category->parent_id)==$p->id)>{{ $p->name }}</option>@endforeach</select></label>
<label class="block">Deskripsi<textarea name="description" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('description', $category->description) }}</textarea></label>
<label class="block">Urutan tampil<input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" class="mt-1 w-full border rounded px-3 py-2"></label>
<button class="bg-red-600 text-white px-4 py-2 rounded">Simpan</button>
</form>
@endsection
