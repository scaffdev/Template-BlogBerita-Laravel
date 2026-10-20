@extends('layouts.app')

@section('title', 'Artikel Disimpan - ' . ($siteName ?? config('app.name')))

@section('content')
<nav aria-label="Breadcrumb" class="text-xs text-slate-500 mb-3"><a href="/" class="hover:text-red-700 font-semibold">Beranda</a> <span aria-hidden="true">/</span> <span>Disimpan</span></nav>
<div class="bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm">
  <h1 class="font-headline text-2xl md:text-3xl font-bold">Artikel Disimpan</h1>
  <p class="text-sm text-slate-500 mt-1">Koleksi pribadi Anda — tersinkron di akun, tersedia juga riwayat baca lokal di perangkat ini.</p>
</div>
@if($posts->count())
  <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mt-5">
    @foreach($posts as $post)
      @include('components.post-card', ['post' => $post])
    @endforeach
  </div>
  @include('components.pagination', ['paginator' => $posts])
@else
  <div class="mt-5 bg-white border border-slate-200 rounded-3xl p-10 text-center">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 4a2 2 0 012-2h8a2 2 0 012 2v18l-6-4-6 4V4z"/></svg>
    <p class="font-headline font-bold text-xl mt-3">Belum ada simpanan.</p>
    <p class="text-sm text-slate-500 mt-1.5">Ketuk tombol <b>Simpan</b> pada artikel untuk membacanya nanti.</p>
    <a href="/artikel" class="btn btn-dark mt-4">Jelajahi artikel</a>
  </div>
@endif
@endsection
