@extends('layouts.app')

@section('title', 'Topik #' . $tag->name . ' - ' . ($siteName ?? config('app.name')))
@section('meta_description', 'Artikel dengan tag ' . $tag->name)

@section('content')
<div class="rounded-3xl bg-white border border-slate-200 p-6 md:p-8 shadow-sm flex flex-wrap items-center gap-4">
  <span class="w-14 h-14 rounded-2xl bg-slate-900 text-amber-300 grid place-items-center text-2xl font-extrabold shrink-0">#</span>
  <div class="min-w-0">
    <nav aria-label="Breadcrumb" class="text-xs text-slate-500"><a href="/" class="hover:text-red-700 font-semibold">Beranda</a> <span aria-hidden="true">/</span> <span>Topik</span></nav>
    <h1 class="font-headline text-2xl md:text-3xl font-bold">#{{ $tag->name }}</h1>
    <p class="text-sm text-slate-500">{{ $posts->total() ?? 0 }} artikel bertopik ini • <a href="/artikel" class="text-red-700 font-bold hover:underline">Jelajahi semua →</a></p>
  </div>
  <div class="ml-auto flex gap-2">
    <button type="button" data-share-native data-url="{{ url()->current() }}" data-title="Topik #{{ $tag->name }}"
            class="text-xs font-bold px-4 py-2.5 rounded-[2px] bg-slate-900 text-white hover:bg-red-700 transition inline-flex items-center gap-1.5">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8.7 10.7l6.6-3.3m0 9.2l-6.6-3.3M6 14a2 2 0 100-4 2 2 0 000 4zm12 6a2 2 0 100-4 2 2 0 000 4zM18 8a2 2 0 100-4 2 2 0 000 4z"/></svg>
      Bagikan topik
    </button>
  </div>
</div>
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mt-5">
  @forelse($posts as $post)
    @include('components.post-card', ['post' => $post])
  @empty
    <p class="col-span-full bg-white border rounded-3xl p-10 text-center text-sm text-slate-500">Belum ada artikel dengan tag ini.</p>
  @endforelse
</div>
@include('components.pagination', ['paginator' => $posts])
@endsection
