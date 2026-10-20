@extends('layouts.app')

@section('title', $page->title . ' - ' . ($siteName ?? config('app.name')))
@section('meta_description', substr(strip_tags($page->content), 0, 160))

@section('content')
<nav aria-label="Breadcrumb" class="text-[13px] text-slate-500 mb-4"><a href="/" class="hover:text-red-700 font-semibold">Beranda</a> <span aria-hidden="true" class="text-slate-300">/</span> <span class="text-slate-700">{{ $page->title }}</span></nav>
<div class="grid lg:grid-cols-[1fr_300px] gap-6 items-start">
  <article class="bg-white border border-slate-200 rounded-3xl p-6 md:p-10 shadow-sm">
    <p class="text-[11px] font-extrabold tracking-[0.2em] uppercase text-red-700">Halaman Informasi</p>
    <h1 class="font-headline text-2xl md:text-4xl font-bold mt-2">{{ $page->title }}</h1>
    <div class="h-1 w-20 bg-red-600 mt-4" aria-hidden="true"></div>
    <div class="prose prose-slate max-w-none mt-6 prose-headings:font-headline">
      {!! $page->content !!}
    </div>
  </article>
  <aside class="space-y-4 lg:sticky lg:top-24">
    <div class="bg-slate-950 text-white rounded-3xl p-6 bg-dots">
      <p class="font-headline font-bold text-lg">Butuh bantuan?</p>
      <p class="text-sm text-slate-300 mt-1">Hubungi redaksi untuk koreksi, iklan, atau kerja sama.</p>
      <a href="/redaksi" class="block text-center mt-4 text-sm font-bold bg-red-600 hover:bg-red-500 rounded-xl py-3 transition">Hubungi Redaksi →</a>
    </div>
    <nav class="bg-white border border-slate-200 rounded-3xl p-5 text-sm font-semibold" aria-label="Halaman lain">
      <p class="font-extrabold mb-3">Halaman Lain</p>
      <ul class="space-y-2">
        <li><a class="hover:text-red-700" href="/tentang">→ Tentang Kami</a></li>
        <li><a class="hover:text-red-700" href="/redaksi">→ Redaksi</a></li>
        <li><a class="hover:text-red-700" href="/pedoman-media-siber">→ Pedoman Media Siber</a></li>
        <li><a class="hover:text-red-700" href="/kebijakan-privasi">→ Kebijakan Privasi</a></li>
      </ul>
    </nav>
  </aside>
</div>
@endsection
