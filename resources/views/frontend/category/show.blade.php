@extends('layouts.app')

@section('title', $category->name . ' - ' . ($siteName ?? config('app.name')))
@section('meta_description', $category->description ?: ('Kumpulan artikel kategori ' . $category->name))

@section('content')
<div class="rounded-3xl overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-red-950 text-white bg-dots p-6 md:p-8 relative">
  <nav aria-label="Breadcrumb" class="text-xs text-slate-400"><a href="/" class="hover:text-white font-semibold">Beranda</a> <span aria-hidden="true">/</span> <span class="text-slate-200">Kanal</span></nav>
  <div class="flex flex-wrap items-center gap-3 mt-2">
    <span class="text-[11px] font-extrabold uppercase tracking-widest bg-red-600 px-3 py-1.5 rounded-[2px]">Kanal</span>
    <h1 class="font-headline text-2xl md:text-4xl font-bold">{{ $category->name }}</h1>
    <span class="text-xs font-bold bg-white/10 border border-white/15 px-2.5 py-1.5 rounded-[2px]">{{ $posts->total() ?? 0 }} artikel</span>
  </div>
  @if($category->description)<p class="text-sm text-slate-300 mt-2 max-w-2xl leading-relaxed">{{ $category->description }}</p>@endif
  @if($category->children->count())
  <div class="flex flex-wrap gap-2 mt-4">
    <span class="text-xs font-bold text-slate-300 self-center">Sub-kanal:</span>
    @foreach($category->children as $child)
      <a href="/kategori/{{ $child->slug }}" class="text-xs font-bold px-3.5 py-1.5 rounded-[2px] bg-white/10 border border-white/15 hover:bg-red-600 transition">{{ $child->name }}</a>
    @endforeach
  </div>
  @endif
</div>

@if($posts->count())
  @php($hl = $posts->first())
  <div class="grid md:grid-cols-2 gap-4 mt-5">
    @include('components.post-card', ['post' => $hl, 'variant' => 'overlay'])
    <div class="grid gap-3 content-start">
      @foreach($posts->skip(1)->take(3) as $post)
        @include('components.post-card', ['post' => $post, 'variant' => 'horizontal'])
      @endforeach
    </div>
  </div>
  <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mt-4">
    @foreach($posts->skip(4) as $post)
      @include('components.post-card', ['post' => $post])
    @endforeach
  </div>
@else
  <div class="bg-white border border-slate-200 rounded-3xl p-10 text-center mt-5">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/></svg>
    <p class="font-bold mt-3">Belum ada artikel pada kanal ini.</p>
    <a href="/artikel" class="inline-block mt-3 text-sm font-bold text-red-700 hover:underline">Jelajahi kanal lain →</a>
  </div>
@endif
@include('components.pagination', ['paginator' => $posts])
@endsection
