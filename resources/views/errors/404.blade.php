@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan (404)')

@section('content')
<div class="max-w-xl mx-auto text-center py-12">
  <p class="text-6xl font-extrabold text-gray-200">404</p>
  <h1 class="text-2xl font-extrabold mt-2">Halaman tidak ditemukan</h1>
  <p class="text-sm text-gray-600 mt-2">Tautan mungkin sudah dipindah atau dihapus. Coba artikel populer berikut:</p>
  <div class="flex justify-center gap-2 mt-4">
    <a href="/" class="px-5 py-2.5 bg-blue-700 text-white text-sm rounded-lg min-h-[44px] inline-flex items-center">Kembali ke Beranda</a>
    <a href="/artikel" class="px-5 py-2.5 border text-sm rounded-lg min-h-[44px] inline-flex items-center bg-white">Semua Artikel</a>
  </div>
  @if(!empty($popular ?? null) && $popular->count())
    <div class="text-left mt-8 grid gap-4 sm:grid-cols-2">
      @foreach($popular as $post)
        @include('components.post-card', ['post' => $post])
      @endforeach
    </div>
  @endif
</div>
@endsection
