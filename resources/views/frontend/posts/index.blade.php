@extends('layouts.app')

@section('title', 'Terbaru — ' . ($siteName ?? config('app.name')))
@section('meta_description', 'Jelajahi semua artikel terbaru dari seluruh kanal, filter berdasarkan kanal atau kata kunci.')

@section('content')
<div class="rounded-3xl overflow-hidden bg-slate-950 text-white bg-dots p-6 md:p-8 relative">
  <div class="absolute -right-12 -bottom-16 w-64 h-64 rounded-full bg-red-600/25 blur-3xl" aria-hidden="true"></div>
  <nav aria-label="Breadcrumb" class="text-xs text-slate-400 relative"><a href="/" class="hover:text-white font-semibold">Beranda</a> <span aria-hidden="true">/</span> <span class="text-slate-200">Terbaru</span></nav>
  <h1 class="font-headline text-2xl md:text-4xl font-bold mt-2 relative">Terbaru</h1>
  <p class="text-sm text-slate-300 mt-1.5 relative">{{ number_format($posts->total() ?? 0) }} artikel dari seluruh kanal, diperbarui sepanjang hari.</p>
</div>

<form method="GET" action="/artikel" class="mt-5 flex flex-col sm:flex-row gap-2 bg-white border border-slate-200 rounded-2xl p-3 shadow-sm">
  <label for="q" class="sr-only">Kata kunci</label>
  <div class="flex items-center flex-1 bg-slate-100 rounded-xl px-3.5">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M20 20l-3.5-3.5"/></svg>
    <input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="Cari judul, topik, tokoh..." class="w-full bg-transparent py-3 text-sm outline-none placeholder:text-slate-400 min-h-[44px]">
  </div>
  <label for="kategori" class="sr-only">Kanal</label>
  <select id="kategori" name="kategori" class="border border-slate-200 bg-slate-50 rounded-xl px-3.5 py-3 text-sm min-h-[44px] font-semibold">
    <option value="">Semua kanal</option>
    @foreach($categories as $cat)
      <option value="{{ $cat->slug }}" @selected(request('kategori')===$cat->slug)>{{ $cat->name }}</option>
    @endforeach
  </select>
  <button class="btn btn-primary">Terapkan</button>
</form>

@if($posts->count() && !request('q') && !request('kategori') && (request()->integer('page', 1) < 2))
  @php($first = $posts->first())
  <div class="mt-5">
    @include('components.post-card', ['post' => $first, 'variant' => 'overlay', 'eager' => true])
  </div>
@endif

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mt-5">
  @forelse($posts as $post)
    @include('components.post-card', ['post' => $post])
  @empty
    <div class="col-span-full bg-white border border-slate-200 rounded-3xl p-10 text-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M20 20l-3.5-3.5"/></svg>
      <p class="font-bold mt-3">Tidak ada artikel ditemukan.</p>
      <p class="text-sm text-slate-500 mt-1">Coba kata kunci atau kanal lain, atau <a href="/artikel" class="text-red-700 font-bold hover:underline">lihat semua artikel</a>.</p>
    </div>
  @endforelse
</div>
@include('components.pagination', ['paginator' => $posts])
@endsection
