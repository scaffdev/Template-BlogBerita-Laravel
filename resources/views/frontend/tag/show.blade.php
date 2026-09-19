@extends('layouts.app')

@section('title', 'Topik #' . $tag->name . ' - ' . ($siteName ?? config('app.name')))
@section('meta_description', 'Artikel dengan tag ' . $tag->name)

@section('content')
<nav aria-label="Breadcrumb" class="text-sm text-gray-500 mb-3"><a href="/" class="hover:text-blue-700">Beranda</a> <span aria-hidden="true">›</span> <span class="text-gray-700">#{{ $tag->name }}</span></nav>
<h1 class="text-2xl font-extrabold">#{{ $tag->name }}</h1>
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mt-6">
  @forelse($posts as $post)
    @include('components.post-card', ['post' => $post])
  @empty
    <p class="text-sm text-gray-600">Belum ada artikel dengan tag ini.</p>
  @endforelse
</div>
@include('components.pagination', ['paginator' => $posts])
@endsection
