@extends('layouts.app')

@section('title', ($siteName ?? 'Portal Berita') . ' — Berita Terkini Hari Ini')
@section('meta_description', 'Berita terkini hari ini: headline, artikel terbaru, terpopuler, dan liputan per kanal dalam bahasa Indonesia.')

@section('content')
{{-- ══════ HEADLINE ══════ --}}
@if($headlines->count())
<section aria-label="Headline" class="bg-white border border-slate-200/80 rounded-3xl p-4 md:p-5 shadow-sm">
  @php($first = $headlines->first())
  @php($rest = $headlines->skip(1)->take(2))
  <div class="flex items-end justify-between gap-3 mb-4 border-b-2 border-slate-900/60 dark:border-white/30 pb-2">
    <p class="section-flag text-red-700">Headline Hari Ini</p>
    <a href="/artikel" class="section-flag text-slate-500 hover:text-red-700 transition">Semua berita →</a>
  </div>
  <div class="grid gap-4 lg:grid-cols-3">
    <div class="lg:col-span-2">
      @include('components.post-card', ['post' => $first, 'variant' => 'overlay', 'eager' => true])
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-1 gap-4">
      @foreach($rest as $h)
        <article class="img-zoom group relative rounded-2xl overflow-hidden bg-slate-900 min-h-[180px] lg:min-h-0 lg:h-full flex">
          <x-post-image :post="$h" sizes="(min-width: 1024px) 400px, 50vw" class="absolute inset-0 w-full h-full object-cover" />
          @if(empty($h->featured_image))<span class="absolute inset-0 bg-gradient-to-br from-slate-800 via-slate-900 to-red-950"></span>@endif
          <div class="absolute inset-0 headline-overlay"></div>
          <div class="relative mt-auto p-4 w-full">
            @if($h->category)<span class="kicker bg-white/15 backdrop-blur text-white border border-white/20 px-2 py-0.5 rounded">{{ $h->category->name }}</span>@endif
            <h2 class="font-bold text-white leading-snug mt-1.5 line-clamp-3"><a href="/artikel/{{ $h->slug }}" class="group-hover:underline decoration-red-400 underline-offset-4">{{ $h->title }}</a></h2>
            <p class="text-[11px] text-slate-300 mt-1">{{ optional($h->published_at)->locale('id')->diffForHumans() }} • {{ $h->author?->name ?? 'Redaksi' }}</p>
          </div>
          <a href="/artikel/{{ $h->slug }}" class="absolute inset-0" aria-label="{{ $h->title }}"><span class="sr-only">{{ $h->title }}</span></a>
        </article>
      @endforeach
      <div class="sm:col-span-2 lg:col-span-1 rounded-[2px] bg-slate-950 text-white p-4 bg-dots flex items-center gap-3">
        <span class="w-11 h-11 border border-amber-400/70 text-amber-300 grid place-items-center font-headline text-3xl leading-none shrink-0" aria-hidden="true">“</span>
        <div class="min-w-0">
          <p class="kicker text-amber-300">Sorotan Redaksi</p>
          @if($featured->first())
            <a href="/artikel/{{ $featured->first()->slug }}" class="text-sm font-bold leading-snug hover:underline line-clamp-2">{{ $featured->first()->title }}</a>
          @else
            <p class="text-sm font-bold">Analisis mendalam pilihan editor hari ini.</p>
          @endif
        </div>
      </div>
    </div>
  </div>

  @if($featured->count())
    <div class="mt-4 pt-4 border-t border-slate-100 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
      @foreach($featured->take(4) as $f)
        <a href="/artikel/{{ $f->slug }}" class="group flex gap-3 items-center bg-slate-50 hover:bg-red-50 border border-slate-100 hover:border-red-200 rounded-xl p-2.5 transition">
          @if($f->featured_image)
            <x-post-image :post="$f" alt="" sizes="64px" class="w-16 h-16 rounded-lg object-cover shrink-0" />
          @else
            <span class="w-16 h-16 rounded-lg bg-slate-900 text-white grid place-items-center font-extrabold shrink-0" aria-hidden="true">{{ mb_substr($f->title, 0, 1) }}</span>
          @endif
          <span class="min-w-0">
            <span class="block kicker text-red-700">{{ $f->category?->name ?? 'Pilihan' }}</span>
            <span class="block text-[13px] font-bold leading-snug line-clamp-2 group-hover:text-red-800">{{ $f->title }}</span>
          </span>
        </a>
      @endforeach
    </div>
  @endif
</section>
@endif

{{-- Lanjutkan membaca (diisi JS dari riwayat lokal) --}}
<section data-continue-reading-wrap class="hidden mt-5 bg-white border border-slate-200/80 rounded-2xl p-4 shadow-sm" aria-label="Lanjutkan membaca">
  <p class="font-headline font-black text-xl mb-3">Lanjutkan membaca</p>
  <div data-continue-reading class="grid gap-2 sm:grid-cols-2 lg:grid-cols-4"></div>
</section>

<div class="grid gap-8 lg:grid-cols-[1fr_340px] mt-8 items-start">
  {{-- ══════ KOLOM UTAMA ══════ --}}
  <div class="min-w-0 space-y-10">
    {{-- TERBARU --}}
    <section aria-label="Artikel terbaru" class="bg-white border border-slate-200/80 rounded-3xl p-4 md:p-6 shadow-sm">
      @include('components.section-title', ['title' => 'Terbaru', 'subtitle' => 'Berita terbaru dari tim redaksi', 'link' => '/artikel', 'linkText' => 'Semua berita'])
      @if($latest->count())
        @php($topLatest = $latest->first())
        <a href="/artikel/{{ $topLatest->slug }}" class="group grid md:grid-cols-2 gap-4 rounded-2xl overflow-hidden border border-slate-200 hover:border-red-300 hover:shadow-card transition mb-4">
          <span class="img-zoom block overflow-hidden bg-slate-100 min-h-[220px]">
            @if($topLatest->featured_image)
              <x-post-image :post="$topLatest" sizes="(min-width: 768px) 400px, 100vw" class="w-full h-full min-h-[220px] object-cover" />
            @else
              <span class="w-full h-full min-h-[220px] block bg-slate-900 bg-dots"></span>
            @endif
          </span>
          <span class="p-5 flex flex-col">
            <span class="inline-flex items-center gap-2">
              <span class="kicker bg-red-600 text-white px-2.5 py-1 rounded-md">{{ $topLatest->category?->name ?? 'Terbaru' }}</span>
              <span class="text-xs text-slate-500">{{ optional($topLatest->published_at)->locale('id')->diffForHumans() }}</span>
            </span>
            <span class="font-headline text-xl md:text-2xl font-bold leading-tight mt-2.5 group-hover:text-red-800 line-clamp-3">{{ $topLatest->title }}</span>
            <span class="text-sm text-slate-600 mt-2 line-clamp-2">{{ $topLatest->excerpt }}</span>
            <span class="mt-auto pt-3 text-xs font-semibold text-slate-500">Oleh {{ $topLatest->author?->name ?? 'Redaksi' }} • {{ $topLatest->reading_time ?? 3 }} menit baca</span>
          </span>
        </a>
      @endif
      <div class="grid gap-4 sm:grid-cols-2">
        @forelse($latest->skip(1) as $post)
          @include('components.post-card', ['post' => $post])
        @empty
          <p class="text-sm text-slate-600">Belum ada artikel.</p>
        @endforelse
      </div>
      <div class="mt-5">@include('components.pagination', ['paginator' => $latest])</div>
    </section>

    {{-- PER KANAL — layout bergantian --}}
    @foreach($categories as $idx => $cat)
    <section aria-label="Kanal {{ $cat->name }}" class="bg-white border border-slate-200/80 rounded-3xl p-4 md:p-6 shadow-sm overflow-hidden">
      @include('components.section-title', ['title' => $cat->name, 'subtitle' => $cat->description ?? 'Liputan pilihan kanal ' . $cat->name, 'link' => '/kategori/' . $cat->slug, 'linkText' => 'Semua', 'accent' => $idx % 2 ? 'bg-amber-400' : 'bg-red-600'])
      @if($cat->latestPosts->count())
        @if($idx % 2 === 0)
          <div class="grid md:grid-cols-2 gap-4">
            @include('components.post-card', ['post' => $cat->latestPosts->first(), 'variant' => 'overlay'])
            <div class="grid gap-3 content-start">
              @foreach($cat->latestPosts->skip(1)->take(3) as $post)
                @include('components.post-card', ['post' => $post, 'variant' => 'horizontal'])
              @endforeach
            </div>
          </div>
        @else
          <div class="grid gap-4 sm:grid-cols-2">
            @foreach($cat->latestPosts->take(4) as $post)
              @include('components.post-card', ['post' => $post])
            @endforeach
          </div>
        @endif
      @else
        <p class="text-sm text-slate-500">Belum ada artikel pada kanal ini.</p>
      @endif
    </section>
    @endforeach

    {{-- OPINI / ANALISIS --}}
    <section aria-label="Analisis dan opini" class="rounded-3xl overflow-hidden bg-slate-950 text-white bg-dots p-6 md:p-8 relative">
      <div class="absolute -right-10 -top-10 w-56 h-56 rounded-full bg-red-600/30 blur-3xl" aria-hidden="true"></div>
      <div class="flex flex-wrap items-center gap-3 relative">
        <span class="section-flag bg-amber-400 text-slate-900 px-3 py-1.5 rounded-[2px]">Opini &amp; Analisis</span>
        <h2 class="font-headline text-xl md:text-2xl font-black">Sudut pandang yang memperdalam peristiwa.</h2>
        <a href="/artikel" class="ml-auto btn btn-outline !min-h-[40px] !py-2">Baca semua →</a>
      </div>
      <div class="grid sm:grid-cols-3 gap-4 mt-5 relative">
        @foreach($popular->take(3) as $p)
          <a href="/artikel/{{ $p->slug }}" class="group rounded-[2px] bg-white/5 border border-white/10 hover:border-amber-300/50 hover:bg-white/10 p-4 transition">
            <p class="section-flag text-amber-300">{{ $p->category?->name ?? 'Analisis' }}</p>
            <p class="font-headline text-lg font-bold leading-snug mt-1.5 line-clamp-3 group-hover:underline underline-offset-4">“{{ $p->title }}”</p>
            <p class="font-headline italic text-xs text-slate-400 mt-2">— {{ $p->author?->name ?? 'Redaksi' }}</p>
          </a>
        @endforeach
      </div>
    </section>
  </div>

  {{-- ══════ SIDEBAR ══════ --}}
  <aside class="space-y-5 lg:sticky lg:top-24" aria-label="Sidebar">
    <div class="bg-white border border-slate-200/80 rounded-3xl p-5 shadow-sm" data-tabs>
      <div class="flex items-center justify-between mb-3">
        <h2 class="font-headline font-black text-xl">Jangan Lewatkan</h2>
      </div>
      <div role="tablist" aria-label="Pilih daftar artikel" class="grid grid-cols-2 gap-1 bg-slate-100 rounded-xl p-1 text-sm font-bold mb-4">
        <button data-tab="populer" role="tab" aria-selected="true" class="flex-1 py-2 rounded-lg bg-slate-900 text-white shadow transition">Terpopuler</button>
        <button data-tab="terbaru" role="tab" aria-selected="false" tabindex="-1" class="flex-1 py-2 rounded-lg text-slate-500 hover:text-slate-900 transition">Terbaru</button>
      </div>
      <div data-panel="populer" class="space-y-4">
        @forelse($popular as $i => $p)
          @include('components.post-card', ['post' => $p, 'variant' => 'rank', 'number' => $i + 1])
        @empty
          <p class="text-sm text-slate-500">Belum ada data.</p>
        @endforelse
      </div>
      <div data-panel="terbaru" class="space-y-3 hidden">
        @foreach($latest->take(5) as $post)
          @include('components.post-card', ['post' => $post, 'variant' => 'horizontal'])
        @endforeach
      </div>
    </div>

    <x-ad-slot name="ad_sidebar" min-height="250px" />

    <div class="rounded-[2px] border border-slate-300 overflow-hidden bg-slate-950 text-white p-6">
      <p class="section-flag text-red-400">Newsletter Pagi</p>
      <p class="font-headline font-black text-2xl mt-3 leading-tight">5 berita penting, 3 menit baca.</p>
      <p class="font-headline italic text-sm text-slate-400 mt-2">Dikirim setiap pagi, gratis.</p>
      <div class="mt-5 bg-white rounded-[2px] p-3">@include('components.newsletter-form')</div>
    </div>

    <div class="bg-white border border-slate-200/80 rounded-3xl p-5 shadow-sm">
      <h2 class="font-headline font-black text-xl">Topik Hangat</h2>
      <div class="flex flex-wrap gap-2 mt-3">
        @forelse(($hotTags ?? collect())->take(10) as $tag)
          <a href="/tag/{{ $tag->slug }}" class="chip">#{{ $tag->name }}</a>
        @empty
          <a href="/artikel" class="chip">Semua berita</a>
        @endforelse
      </div>
    </div>
  </aside>
</div>
@endsection
