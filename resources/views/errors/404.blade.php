@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan (404) — ' . ($siteName ?? config('app.name')))
@section('robots', 'noindex')

@section('content')
<div class="max-w-2xl mx-auto text-center py-10">
  <p class="font-headline text-7xl md:text-9xl font-bold text-slate-200 leading-none" aria-hidden="true">404</p>
  <h1 class="font-headline text-2xl md:text-3xl font-bold mt-4">Halaman tidak ditemukan</h1>
  <p class="text-sm text-slate-600 mt-2 max-w-md mx-auto">Tautan mungkin sudah dipindah atau dihapus. Periksa kembali alamatnya, atau mulai dari beranda.</p>
  <div class="flex flex-wrap justify-center gap-2 mt-5">
    <a href="/" class="btn btn-primary">Kembali ke beranda</a>
    <a href="/artikel" class="btn btn-outline">Lihat semua artikel</a>
  </div>
</div>

@if(!empty($popular ?? null) && $popular->count())
  <h2 class="section-flag text-red-700 border-b-[3px] border-double border-slate-900/50 pb-2 mt-8 mb-4">Sedang banyak dibaca</h2>
  <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    @foreach($popular as $post)
      @include('components.post-card', ['post' => $post])
    @endforeach
  </div>
@endif
@endsection
