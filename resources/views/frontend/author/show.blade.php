@extends('layouts.app')

@section('title', $author->name . ' - Profil Penulis')
@section('meta_description', $author->bio ?: ('Artikel yang ditulis oleh ' . $author->name))

@section('content')
<div class="rounded-3xl overflow-hidden bg-slate-950 text-white bg-dots p-6 md:p-8 relative">
  <div class="absolute -left-10 -top-10 w-48 h-48 rounded-full bg-red-600/25 blur-3xl" aria-hidden="true"></div>
  <div class="relative flex flex-col sm:flex-row gap-5 items-start">
    @if($author->avatar)
      <img src="{{ str_starts_with($author->avatar,'http') ? $author->avatar : asset('storage/'.ltrim($author->avatar,'/')) }}" alt="Foto {{ $author->name }}" class="w-24 h-24 rounded-3xl object-cover ring-4 ring-white/15 shrink-0" loading="lazy">
    @else
      <div class="w-24 h-24 rounded-3xl bg-gradient-to-br from-red-600 to-rose-800 grid place-items-center text-3xl font-extrabold shrink-0" aria-hidden="true">{{ substr($author->name,0,1) }}</div>
    @endif
    <div class="min-w-0 flex-1">
      <p class="text-[11px] font-extrabold tracking-[0.2em] uppercase text-red-300">Profil Penulis</p>
      <h1 class="font-headline text-2xl md:text-3xl font-bold mt-1">{{ $author->name }}</h1>
      <p class="text-sm text-slate-400">{{ $author->roleLabel() }} • {{ number_format($posts->total() ?? 0) }} artikel terbit</p>
      @if($author->bio)<p class="text-sm text-slate-200 mt-2.5 leading-relaxed max-w-2xl">{{ $author->bio }}</p>@endif
      <div class="flex flex-wrap gap-2 mt-4">
        <button type="button" data-share-native data-url="{{ url()->current() }}" data-title="Profil {{ $author->name }}"
                class="text-xs font-bold px-4 py-2 rounded-[2px] bg-red-600 hover:bg-red-500 transition inline-flex items-center gap-1.5">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8.7 10.7l6.6-3.3m0 9.2l-6.6-3.3M6 14a2 2 0 100-4 2 2 0 000 4zm12 6a2 2 0 100-4 2 2 0 000 4zM18 8a2 2 0 100-4 2 2 0 000 4z"/></svg>
          Bagikan profil
        </button>
        <button type="button" data-copy="{{ url()->current() }}" data-copy-message="Tautan profil disalin!"
                class="text-xs font-bold px-4 py-2 rounded-[2px] bg-white/10 border border-white/15 hover:bg-white/20 transition">Salin tautan</button>
      </div>
    </div>
  </div>
</div>

<h2 class="font-headline text-2xl font-black mt-8 mb-4 border-b-[3px] border-double border-slate-900/50 pb-2">Artikel oleh {{ $author->name }}</h2>
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
  @forelse($posts as $post)
    @include('components.post-card', ['post' => $post])
  @empty
    <p class="col-span-full bg-white border rounded-3xl p-10 text-center text-sm text-slate-500">Penulis ini belum memiliki artikel terbit.</p>
  @endforelse
</div>
@include('components.pagination', ['paginator' => $posts])
@endsection
