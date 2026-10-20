@extends('layouts.app')

@section('title', ($q !== '' ? 'Hasil pencarian "' . $q . '"' : 'Pencarian') . ' — ' . ($siteName ?? config('app.name')))

@section('content')
@php($hasFilter = ($penulis ?? '') !== '' || ($dari ?? '') !== '' || ($sampai ?? '') !== '' || $sort !== 'relevan')
<div class="rounded-3xl bg-white border border-slate-200 p-6 md:p-8 shadow-sm relative overflow-hidden">
  <div class="absolute inset-0 bg-grid-light opacity-60" aria-hidden="true"></div>
  <div class="relative">
    <nav aria-label="Breadcrumb" class="text-xs text-slate-500"><a href="/" class="hover:text-red-700 font-semibold">Beranda</a> <span aria-hidden="true">/</span> <span>Pencarian</span></nav>
    <h1 class="font-headline text-2xl md:text-3xl font-bold mt-2">{{ $q !== '' ? 'Hasil untuk “' . e($q) . '”' : 'Pencarian' }}</h1>
    <p class="text-sm text-slate-500 mt-1">Temukan artikel, topik, dan tokoh. Gunakan <kbd class="px-1.5 py-0.5 bg-slate-100 border border-slate-200 rounded text-[11px] font-mono">Ctrl</kbd> + <kbd class="px-1.5 py-0.5 bg-slate-100 border border-slate-200 rounded text-[11px] font-mono">K</kbd> dari halaman mana pun.</p>
  </div>

  <form method="GET" action="/cari" class="relative mt-5 space-y-3">
    <div class="flex flex-col sm:flex-row gap-2">
      <div class="relative flex-1">
        <label for="q" class="sr-only">Kata kunci</label>
        <input id="q" type="search" name="q" value="{{ $q }}" placeholder="Ketik kata kunci..." autocomplete="off"
               data-suggest-input aria-expanded="false" aria-autocomplete="list" aria-controls="q-box"
               class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-3 text-sm min-h-[44px] outline-none focus:border-red-400 focus:bg-white">
        <div class="search-suggest hidden" id="q-box" data-suggest-box="q" role="listbox" aria-label="Saran pencarian"></div>
      </div>
      <div class="sm:w-44">
        <label for="kategori" class="sr-only">Kanal</label>
        <select id="kategori" name="kategori" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-3 py-3 text-sm min-h-[44px] font-semibold">
          <option value="">Semua kanal</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->slug }}" @selected($kategori===$cat->slug)>{{ $cat->name }}</option>
          @endforeach
        </select>
      </div>
      <button class="btn btn-primary sm:w-auto">Cari</button>
    </div>

    <details class="bg-slate-50 border border-slate-200 rounded-2xl" @if($hasFilter) open @endif>
      <summary class="cursor-pointer select-none px-4 py-3 text-sm font-bold flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" d="M4 6h16M7 12h10M10 18h4"/></svg>
        Filter lanjutan
        @if($hasFilter)<span class="text-[10px] font-extrabold bg-red-600 text-white px-2 py-0.5 rounded-[2px]">Aktif</span>@endif
      </summary>
      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-2 px-4 pb-4">
        <div>
          <label for="sort" class="text-xs font-bold text-slate-600">Urutkan</label>
          <select id="sort" name="sort" class="mt-1 w-full border border-slate-200 bg-white rounded-xl px-3 py-2.5 text-sm min-h-[44px] font-semibold">
            <option value="relevan" @selected($sort==='relevan')>Paling relevan</option>
            <option value="terbaru" @selected($sort==='terbaru')>Terbaru</option>
            <option value="populer" @selected($sort==='populer')>Terpopuler</option>
          </select>
        </div>
        <div>
          <label for="penulis" class="text-xs font-bold text-slate-600">Penulis</label>
          <select id="penulis" name="penulis" class="mt-1 w-full border border-slate-200 bg-white rounded-xl px-3 py-2.5 text-sm min-h-[44px] font-semibold">
            <option value="">Semua penulis</option>
            @foreach(($authors ?? []) as $a)
              <option value="{{ $a->username }}" @selected(($penulis ?? '')===$a->username)>{{ $a->name }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label for="dari" class="text-xs font-bold text-slate-600">Dari tanggal</label>
          <input id="dari" type="date" name="dari" value="{{ $dari ?? '' }}" class="mt-1 w-full border border-slate-200 bg-white rounded-xl px-3 py-2.5 text-sm min-h-[44px]">
        </div>
        <div>
          <label for="sampai" class="text-xs font-bold text-slate-600">Sampai tanggal</label>
          <input id="sampai" type="date" name="sampai" value="{{ $sampai ?? '' }}" class="mt-1 w-full border border-slate-200 bg-white rounded-xl px-3 py-2.5 text-sm min-h-[44px]">
        </div>
      </div>
    </details>
  </form>
</div>

@if($q !== '' || $kategori !== '')
  @if($posts->total() > 0)
    <p class="mt-5 inline-flex items-center gap-2 text-sm font-bold bg-white border border-slate-200 text-slate-700 px-4 py-2 rounded-[2px] shadow-sm">
      <span class="w-2 h-2 rounded-[2px] bg-emerald-500" aria-hidden="true"></span>
      {{ number_format($posts->total()) }} artikel ditemukan
    </p>
    @php($firstRes = $posts->first())
    @if($firstRes && $posts->currentPage() === 1)
      <div class="mt-4">
        @include('components.post-card', ['post' => $firstRes, 'variant' => 'overlay'])
      </div>
    @endif
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mt-4">
      @foreach($posts->skip($posts->currentPage()===1 ? 1 : 0) as $post)
        @include('components.post-card', ['post' => $post])
      @endforeach
    </div>
    @include('components.pagination', ['paginator' => $posts])
  @else
    <div class="mt-5 bg-white border border-slate-200 rounded-3xl p-10 text-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M20 20l-3.5-3.5"/></svg>
      <p class="font-headline font-bold text-xl mt-3">Tidak ada hasil untuk “{{ $q }}”</p>
      <p class="text-sm text-slate-500 mt-1.5">Coba kata kunci lebih umum, periksa ejaan, atau jelajahi topik populer berikut:</p>
      <div class="inline-flex flex-wrap justify-center gap-2 mt-4">
        @foreach(($hotTags ?? collect())->take(6) as $t)
          <a href="/cari?q={{ urlencode($t->name) }}" class="text-xs font-bold px-3.5 py-2 rounded-[2px] bg-slate-100 hover:bg-slate-900 hover:text-white transition">{{ $t->name }}</a>
        @endforeach
      </div>
    </div>
    @if($suggestions->count())
      <h2 class="section-flag text-amber-600 border-b-[3px] border-double border-slate-900/50 pb-2 mt-8 mb-4">Mungkin Anda suka</h2>
      <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($suggestions as $post)
          @include('components.post-card', ['post' => $post])
        @endforeach
      </div>
    @endif
  @endif
@else
  @if($suggestions->count())
    <h2 class="section-flag text-red-700 border-b-[3px] border-double border-slate-900/50 pb-2 mt-8 mb-4">Sedang Populer</h2>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
      @foreach($suggestions as $post)
        @include('components.post-card', ['post' => $post])
      @endforeach
    </div>
  @endif
@endif
@endsection
