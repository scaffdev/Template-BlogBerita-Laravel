@extends('layouts.app')

@section('title', ($q !== '' ? 'Hasil pencarian "' . $q . '"' : 'Pencarian') . ' - ' . ($siteName ?? config('app.name')))

@section('content')
<h1 class="text-2xl font-extrabold">{{ $q !== '' ? 'Hasil untuk "' . e($q) . '"' : 'Pencarian Artikel' }}</h1>

<form method="GET" action="/cari" class="mt-4 grid sm:grid-cols-4 gap-2 bg-white border rounded-xl p-3">
  <div class="sm:col-span-2"><label for="q" class="sr-only">Kata kunci</label><input id="q" type="search" name="q" value="{{ $q }}" placeholder="Ketik kata kunci..." class="w-full border rounded-lg px-3 py-2 text-sm min-h-[44px]"></div>
  <div><label for="kategori" class="sr-only">Kategori</label><select id="kategori" name="kategori" class="w-full border rounded-lg px-3 py-2 text-sm min-h-[44px]"><option value="">Semua kategori</option>@foreach($categories as $cat)<option value="{{ $cat->slug }}" @selected($kategori===$cat->slug)>{{ $cat->name }}</option>@endforeach</select></div>
  <div><label for="sort" class="sr-only">Urutkan</label><select id="sort" name="sort" class="w-full border rounded-lg px-3 py-2 text-sm min-h-[44px]"><option value="relevan" @selected($sort==='relevan')>Paling relevan</option><option value="terbaru" @selected($sort==='terbaru')>Terbaru</option><option value="populer" @selected($sort==='populer')>Terpopuler</option></select></div>
  <div class="sm:col-span-4"><button class="w-full sm:w-auto px-5 py-2 bg-blue-700 text-white text-sm rounded-lg min-h-[44px]">Cari</button></div>
</form>

@if($q !== '' || $kategori !== '')
  @if($posts->total() > 0)
    <p class="text-sm text-gray-600 mt-4">Ditemukan {{ $posts->total() }} artikel.</p>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mt-4">
      @foreach($posts as $post)
        @include('components.post-card', ['post' => $post])
      @endforeach
    </div>
    @include('components.pagination', ['paginator' => $posts])
  @else
    <div class="mt-6 bg-white border rounded-xl p-6 text-center">
      <p class="font-bold">Tidak ada hasil untuk "{{ $q }}"</p>
      <p class="text-sm text-gray-600 mt-1">Coba kata kunci lain, atau lihat rekomendasi populer berikut:</p>
    </div>
    <h2 class="text-lg font-bold mt-6 mb-3">Mungkin Anda suka</h2>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
      @foreach($suggestions as $post)
        @include('components.post-card', ['post' => $post])
      @endforeach
    </div>
  @endif
@else
  <h2 class="text-lg font-bold mt-6 mb-3">Artikel Populer</h2>
  <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    @foreach($suggestions as $post)
      @include('components.post-card', ['post' => $post])
    @endforeach
  </div>
@endif
@endsection
