@extends('layouts.app')

@section('title', $author->name . ' - Profil Penulis')
@section('meta_description', $author->bio ?: ('Artikel yang ditulis oleh ' . $author->name))

@section('content')
<div class="bg-white border rounded-xl p-6 flex gap-4 items-start">
  @if($author->avatar)
    <img src="{{ str_starts_with($author->avatar,'http') ? $author->avatar : asset('storage/'.ltrim($author->avatar,'/')) }}" alt="Foto {{ $author->name }}" class="w-20 h-20 rounded-full object-cover" loading="lazy">
  @else
    <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center text-xl font-bold text-blue-700" aria-hidden="true">{{ substr($author->name,0,1) }}</div>
  @endif
  <div>
    <h1 class="text-2xl font-extrabold">{{ $author->name }}</h1>
    <p class="text-sm text-gray-500">@{{ $author->username }}</p>
    @if($author->bio)<p class="text-sm text-gray-700 mt-2">{{ $author->bio }}</p>@endif
  </div>
</div>

<h2 class="text-xl font-bold mt-8 mb-4">Artikel oleh {{ $author->name }}</h2>
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
  @forelse($posts as $post)
    @include('components.post-card', ['post' => $post])
  @empty
    <p class="text-sm text-gray-600">Penulis ini belum memiliki artikel terbit.</p>
  @endforelse
</div>
@include('components.pagination', ['paginator' => $posts])
@endsection
