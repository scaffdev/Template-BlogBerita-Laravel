@extends('layouts.app')

@section('title', 'Semua Artikel - ' . ($siteName ?? config('app.name')))
@section('meta_description', 'Jelajahi semua artikel terbaru, filter berdasarkan kategori atau pencarian.')

@section('content')
<h1 class="text-2xl font-extrabold">Semua Artikel</h1>

<form method="GET" action="/artikel" class="mt-4 flex flex-col sm:flex-row gap-2 bg-white border rounded-xl p-3">
  <label for="q" class="sr-only">Kata kunci</label>
  <input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="Cari judul..." class="flex-1 border rounded-lg px-3 py-2 text-sm min-h-[44px]">
  <label for="kategori" class="sr-only">Kategori</label>
  <select id="kategori" name="kategori" class="border rounded-lg px-3 py-2 text-sm min-h-[44px]">
    <option value="">Semua kategori</option>
    @foreach($categories as $cat)
      <option value="{{ $cat->slug }}" @selected(request('kategori')===$cat->slug)>{{ $cat->name }}</option>
    @endforeach
  </select>
  <button class="px-5 py-2 bg-blue-700 text-white text-sm rounded-lg min-h-[44px]">Filter</button>
</form>

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mt-6">
  @forelse($posts as $post)
    @include('components.post-card', ['post' => $post])
  @empty
    <p class="text-sm text-gray-600 col-span-full">Tidak ada artikel ditemukan.</p>
  @endforelse
</div>
@include('components.pagination', ['paginator' => $posts])
@endsection
