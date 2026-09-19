@extends('layouts.app')

@section('title', $category->name . ' - ' . ($siteName ?? config('app.name')))
@section('meta_description', $category->description ?: ('Kumpulan artikel kategori ' . $category->name))

@section('content')
<nav aria-label="Breadcrumb" class="text-sm text-gray-500 mb-3"><a href="/" class="hover:text-blue-700">Beranda</a> <span aria-hidden="true">›</span> <span class="text-gray-700">{{ $category->name }}</span></nav>
<h1 class="text-2xl font-extrabold">{{ $category->name }}</h1>
@if($category->description)<p class="text-sm text-gray-600 mt-2 max-w-2xl">{{ $category->description }}</p>@endif
@if($category->children->count())
<div class="flex flex-wrap gap-2 mt-3">
  @foreach($category->children as $child)
    <a href="/kategori/{{ $child->slug }}" class="text-xs px-3 py-1.5 border rounded-full bg-white hover:bg-gray-100">{{ $child->name }}</a>
  @endforeach
</div>
@endif
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mt-6">
  @forelse($posts as $post)
    @include('components.post-card', ['post' => $post])
  @empty
    <p class="text-sm text-gray-600">Belum ada artikel pada kategori ini.</p>
  @endforelse
</div>
@include('components.pagination', ['paginator' => $posts])
@endsection
