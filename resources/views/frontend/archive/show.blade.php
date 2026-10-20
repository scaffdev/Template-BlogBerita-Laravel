@extends('layouts.app')

@section('title', 'Arsip ' . $label . ' - ' . ($siteName ?? config('app.name')))

@section('content')
<div class="rounded-3xl bg-white border border-slate-200 p-6 md:p-8 shadow-sm flex flex-wrap items-center gap-4">
  <span class="w-14 h-14 rounded-2xl bg-amber-100 border border-amber-200 grid place-items-center shrink-0" aria-hidden="true">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v1a2 2 0 01-2 2M5 8v11a2 2 0 002 2h10a2 2 0 002-2V8m-9 5h4"/></svg>
  </span>
  <div>
    <nav aria-label="Breadcrumb" class="text-xs text-slate-500"><a href="/" class="hover:text-red-700 font-semibold">Beranda</a> <span aria-hidden="true">/</span> <span>Arsip</span></nav>
    <h1 class="font-headline text-2xl md:text-3xl font-bold">Arsip: {{ $label }}</h1>
    <p class="text-sm text-slate-500">{{ number_format($posts->total() ?? 0) }} artikel pada periode ini.</p>
  </div>
</div>
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mt-5">
  @forelse($posts as $post)
    @include('components.post-card', ['post' => $post])
  @empty
    <p class="col-span-full bg-white border rounded-3xl p-10 text-center text-sm text-slate-500">Tidak ada artikel pada periode ini.</p>
  @endforelse
</div>
@include('components.pagination', ['paginator' => $posts])
@endsection
