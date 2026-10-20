@extends('layouts.app')

@section('title', $post->meta_title ?: $post->title)
@section('meta_description', $post->meta_description ?: ($post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 160)))
@section('canonical', url('/artikel/' . $post->slug))
@section('og_type', 'article')
@section('og_title', $post->title)
@section('og_description', $post->meta_description ?: ($post->excerpt ?? ''))
@section('og_image', $post->featured_image ? (str_starts_with($post->featured_image, 'http') ? $post->featured_image : asset('storage/' . ltrim($post->featured_image, '/'))) : asset('images/og-default.jpg'))

@push('jsonld')
<script type="application/ld+json">{!! json_encode($jsonLd ?? [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@if(!empty($breadcrumbJsonLd))
<script type="application/ld+json">{!! json_encode($breadcrumbJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endif
@endpush

@section('content')
{{-- Progress baca --}}
<div data-progress-bar class="fixed top-0 left-0 h-1 bg-gradient-to-r from-red-600 to-amber-400 z-50 w-0" aria-hidden="true"></div>

@if($post->status !== 'published')
  <div class="mb-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-sm font-semibold px-4 py-3">
    Pratinjau — artikel ini berstatus “{{ $post->status }}” dan belum terbit untuk publik.
  </div>
@endif

<nav aria-label="Breadcrumb" class="text-[11px] font-bold tracking-[0.14em] uppercase text-slate-500 mb-4 flex flex-wrap items-center gap-1.5">
  <a href="/" class="hover:text-red-700">Beranda</a> <span aria-hidden="true" class="text-slate-300">/</span>
  @if($post->category)
    <a href="/kategori/{{ $post->category->slug }}" class="hover:text-red-700">{{ $post->category->name }}</a> <span aria-hidden="true" class="text-slate-300">/</span>
  @endif
  <span aria-current="page" class="text-slate-700 line-clamp-1 font-semibold normal-case tracking-normal">{{ $post->title }}</span>
</nav>

<div class="grid gap-8 lg:grid-cols-[1fr_340px] items-start article-grid">
  {{-- ══════ ARTIKEL ══════ --}}
  <article class="bg-white border border-slate-200/80 rounded-3xl shadow-sm overflow-hidden"
           data-post-slug="{{ $post->slug }}" data-post-title="{{ $post->title }}" data-post-category="{{ $post->category?->name }}">
    <div class="p-5 md:p-9">
      @if(!empty($post->is_sponsored))
        <p class="mb-3 inline-flex items-center gap-2 kicker bg-violet-100 text-violet-800 border border-violet-200 px-3 py-1.5 rounded-[2px]">Konten Bersponsor</p>
      @endif
      @if(!empty($post->is_liveblog))
        <p class="mb-3 ml-2 inline-flex items-center gap-2 kicker bg-red-600 text-white px-3 py-1.5 rounded-[2px]"><span class="w-2 h-2 rounded-full bg-white animate-ping" aria-hidden="true"></span>Liputan Langsung</p>
      @endif

      <div class="flex flex-wrap items-center gap-2">
        @if($post->category)
          <a href="/kategori/{{ $post->category->slug }}" class="kicker text-white bg-red-600 px-3 py-1.5 rounded-[2px]">{{ $post->category->name }}</a>
        @endif
        @if(!empty($post->is_headline))<span class="kicker bg-amber-100 text-amber-800 border border-amber-200 px-3 py-1.5 rounded-[2px]">Headline</span>@endif
        <span class="text-xs text-slate-500 ml-auto">{{ optional($post->published_at)->locale('id')->translatedFormat('l, d F Y • H:i') }} WIB</span>
      </div>

      <h1 class="font-headline text-[28px] md:text-[2.9rem] md:leading-[1.08] font-black leading-[1.12] mt-4">{{ $post->title }}</h1>
      @if($post->excerpt)<p class="font-headline italic text-slate-600 text-lg md:text-xl leading-relaxed mt-4 border-t-[3px] border-double border-slate-900/40 pt-4">{{ $post->excerpt }}</p>@endif

      {{-- Ringkasan 3 poin (signature) --}}
      @php($points = is_array($post->summary_points) ? array_filter($post->summary_points) : [])
      @if(count($points))
        <div class="summary-box mt-6 p-5">
          <p class="kicker text-red-700 mb-2">Yang perlu diketahui</p>
          <ul class="list-disc ml-5 space-y-1.5 text-sm md:text-[15px] leading-relaxed">
            @foreach(array_slice($points, 0, 4) as $point)
              <li>{{ $point }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- Meta penulis --}}
      <div class="flex flex-wrap items-center gap-3 mt-6 pb-5 border-b border-slate-100">
        <a href="/penulis/{{ $post->author?->username ?? '' }}" class="flex items-center gap-2.5 group">
          @if($post->author?->avatar)
            <img src="{{ str_starts_with($post->author->avatar, 'http') ? $post->author->avatar : asset('storage/' . ltrim($post->author->avatar, '/')) }}" alt="Foto {{ $post->author->name }}" class="w-11 h-11 rounded-[3px] object-cover border border-slate-300" loading="lazy">
          @else
            <span class="w-11 h-11 rounded-[3px] bg-slate-900 text-white grid place-items-center font-headline font-black" aria-hidden="true">{{ mb_substr($post->author?->name ?? 'R', 0, 1) }}</span>
          @endif
          <span>
            <span class="block text-sm font-bold group-hover:text-red-700">{{ $post->author?->name ?? 'Redaksi' }}</span>
            <span class="block font-headline italic text-xs text-slate-500">{{ $post->author?->roleLabel() ?? 'Redaksi' }}</span>
          </span>
        </a>
        <span class="ml-auto flex flex-wrap items-center gap-2 text-xs font-semibold text-slate-500">
          @if($post->reading_time)<span class="px-2.5 py-1.5 bg-slate-100 rounded-[2px]">{{ $post->reading_time }} menit baca</span>@endif
          <span class="px-2.5 py-1.5 bg-slate-100 rounded-[2px]">{{ number_format($post->views_count) }} dibaca</span>
        </span>
      </div>
      @if($post->coAuthorNames())
        <p class="text-xs text-slate-500 mt-2">Ditulis bersama: <span class="font-bold text-slate-700">{{ $post->coAuthorNames() }}</span></p>
      @endif

      {{-- Satu baris aksi baca: ukuran teks, dengarkan, mode fokus, cetak, simpan, suka --}}
      <div class="no-print flex items-center gap-2 mt-4 text-xs font-bold overflow-x-auto no-scrollbar py-1" role="group" aria-label="Alat baca">
        <span class="text-slate-500 font-semibold shrink-0">Ukuran teks</span>
        <button type="button" data-font="down" class="icon-btn shrink-0" aria-label="Perkecil ukuran teks">A−</button>
        <button type="button" data-read-size-value class="text-slate-500 shrink-0 w-10 text-center" aria-live="polite">100%</button>
        <button type="button" data-font="up" class="icon-btn shrink-0" aria-label="Perbesar ukuran teks">A+</button>
        <button type="button" data-font-reset class="text-slate-500 hover:text-red-700 shrink-0 px-1">Reset</button>
        <span class="w-px h-6 bg-slate-200 shrink-0" aria-hidden="true"></span>
        <button type="button" data-tts class="btn btn-outline !min-h-[40px] !py-1.5 !px-3.5 shrink-0" aria-pressed="false">
          <svg data-tts-play xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5l-6 4H3v6h2l6 4z"/><path stroke-linecap="round" d="M15.5 8.5a5 5 0 010 7M18 6a8 8 0 010 12"/></svg>
          <svg data-tts-pause xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M10 9v6M14 9v6"/><circle cx="12" cy="12" r="9"/></svg>
          <span data-tts-label>Dengarkan</span>
        </button>
        <button type="button" data-focus-toggle class="btn btn-outline !min-h-[40px] !py-1.5 !px-3.5 shrink-0" aria-pressed="false"><span data-focus-label>Mode fokus</span></button>
        <button type="button" onclick="window.print()" class="btn btn-outline !min-h-[40px] !py-1.5 !px-3.5 shrink-0">Cetak</button>
        @auth
          <form action="/artikel/{{ $post->slug }}/simpan" method="POST" class="inline shrink-0" data-ajax-toggle data-toggle-key="saved">@csrf
            <button class="btn {{ ($isBookmarked ?? false) ? 'btn-primary' : 'btn-outline' }} !min-h-[40px] !py-1.5 !px-3.5" data-state-on="Tersimpan" data-state-off="Simpan" aria-pressed="{{ ($isBookmarked ?? false) ? 'true' : 'false' }}">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 4a2 2 0 012-2h8a2 2 0 012 2v18l-6-4-6 4V4z"/></svg>
              <span data-state-label>{{ ($isBookmarked ?? false) ? 'Tersimpan' : 'Simpan' }}</span>
            </button>
          </form>
          <form action="/artikel/{{ $post->slug }}/suka" method="POST" class="inline shrink-0" data-ajax-toggle data-toggle-key="liked">@csrf
            <button class="btn {{ ($isLiked ?? false) ? 'btn-primary' : 'btn-outline' }} !min-h-[40px] !py-1.5 !px-3.5" data-state-on="Disukai" data-state-off="Suka" aria-pressed="{{ ($isLiked ?? false) ? 'true' : 'false' }}">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-7.5-4.6-9.5-9.3C1 8.4 3 5 6.3 5c2 0 3.4 1.1 4.2 2.4l1.5 2.3 1.5-2.3C14.3 6.1 15.7 5 17.7 5 21 5 23 8.4 21.5 11.7 19.5 16.4 12 21 12 21z"/></svg>
              <span data-state-label>{{ ($isLiked ?? false) ? 'Disukai' : 'Suka' }}</span> <span data-count>({{ number_format($post->likes_count ?? 0) }})</span>
            </button>
          </form>
        @else
          <a href="/login?redirect={{ urlencode(request()->fullUrl()) }}" class="btn btn-outline !min-h-[40px] !py-1.5 !px-3.5 shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 4a2 2 0 012-2h8a2 2 0 012 2v18l-6-4-6 4V4z"/></svg>
            Simpan
          </a>
          <a href="/login?redirect={{ urlencode(request()->fullUrl()) }}" class="btn btn-outline !min-h-[40px] !py-1.5 !px-3.5 shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-7.5-4.6-9.5-9.3C1 8.4 3 5 6.3 5c2 0 3.4 1.1 4.2 2.4l1.5 2.3 1.5-2.3C14.3 6.1 15.7 5 17.7 5 21 5 23 8.4 21.5 11.7 19.5 16.4 12 21 12 21z"/></svg>
            Suka
          </a>
        @endauth
      </div>

      @if($post->featured_image)
        <figure class="mt-6">
          <span class="img-zoom block rounded-2xl overflow-hidden bg-slate-100">
            <x-post-image :post="$post" :eager="true" sizes="(min-width: 1024px) 900px, 100vw" class="w-full object-cover max-h-[520px]" />
          </span>
          <figcaption class="text-xs text-slate-500 mt-2">
            @if($post->featured_image_credit)
              Foto: {{ $post->featured_image_credit }}
            @else
              {{ $post->title }} — {{ $siteName ?? 'Redaksi' }}
            @endif
          </figcaption>
        </figure>
      @endif

      {{-- Daftar isi (diisi JS, hanya untuk artikel panjang) --}}
      <nav data-toc class="hidden mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-5 text-sm scroll-mt-28" aria-label="Daftar isi">
        <p class="font-extrabold mb-2 flex items-center gap-2">Daftar isi <span data-toc-count class="text-[11px] font-bold text-amber-700 bg-amber-100 px-2 py-0.5 rounded-[2px]"></span></p>
        <div data-toc-list></div>
      </nav>

      <div id="isi-artikel" class="prose prose-slate max-w-none mt-6 prose-headings:font-headline prose-headings:scroll-mt-28 prose-a:text-red-700 prose-img:rounded-2xl prose-lg">
        {!! $post->content !!}
      </div>

      <div class="mt-8">
        <x-ad-slot name="ad_inarticle" min-height="120px" />
      </div>

      {{-- Liveblog --}}
      @if(!empty($post->is_liveblog))
      <section aria-label="Liputan langsung" class="mt-8 rounded-2xl border border-red-200 bg-red-50/60 p-5">
        <h2 class="font-extrabold font-headline text-lg flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-red-600 animate-pulse" aria-hidden="true"></span>Liputan Langsung</h2>
        <ol class="mt-4 space-y-4">
          @forelse($post->liveUpdates as $u)
            <li class="flex gap-3">
              <span class="flex flex-col items-center"><span class="w-3 h-3 rounded-full {{ $u->is_key_moment ? 'bg-amber-400' : 'bg-red-600' }} mt-1.5" aria-hidden="true"></span><span class="w-px flex-1 bg-red-200" aria-hidden="true"></span></span>
              <div class="min-w-0 pb-1">
                <p class="text-[11px] font-bold text-red-700">{{ $u->created_at->locale('id')->translatedFormat('d M Y, H:i') }} WIB @if($u->is_key_moment)<span class="ml-1 bg-amber-300 text-slate-900 px-1.5 py-0.5 rounded text-[10px]">MOMEN KUNCI</span>@endif</p>
                <p class="text-sm mt-1 leading-relaxed">{{ $u->content }}</p>
              </div>
            </li>
          @empty
            <li class="text-sm text-slate-600">Liputan langsung akan diperbarui di sini mengikuti perkembangan peristiwa.</li>
          @endforelse
        </ol>
      </section>
      @endif

      {{-- Artikel berseri --}}
      @if(!empty($post->series_name) && !empty($series) && $series->count())
      <section aria-label="Artikel berseri" class="mt-8 rounded-2xl border border-indigo-200 bg-indigo-50/60 p-5">
        <h2 class="font-extrabold font-headline text-lg">Seri: {{ $post->series_name }}</h2>
        <ol class="mt-3 space-y-2 list-decimal ml-5 text-sm font-semibold">
          @foreach($series as $s)
            <li><a href="/artikel/{{ $s->slug }}" class="hover:text-red-700">{{ $s->title }}</a> <span class="text-xs font-medium text-slate-500">— {{ optional($s->published_at)->locale('id')->translatedFormat('d M Y') }}</span></li>
          @endforeach
        </ol>
      </section>
      @endif

      @if($post->tags->count())
        <div class="mt-8 flex flex-wrap gap-2 items-center" aria-label="Topik">
          <span class="text-sm font-bold mr-1">Topik:</span>
          @foreach($post->tags as $tag)
            <a href="/tag/{{ $tag->slug }}" class="chip">#{{ $tag->name }}</a>
          @endforeach
        </div>
      @endif

      <div class="mt-6 pt-5 border-t border-slate-100">
        @include('components.share-buttons', ['post' => $post])
      </div>

      {{-- Author box --}}
      @if($post->author)
      <div class="mt-6 rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 to-red-50/50 p-5 flex gap-4">
        @if($post->author->avatar)
          <img src="{{ str_starts_with($post->author->avatar, 'http') ? $post->author->avatar : asset('storage/' . ltrim($post->author->avatar, '/')) }}" alt="Foto {{ $post->author->name }}" class="w-16 h-16 rounded-2xl object-cover shrink-0" loading="lazy">
        @else
          <span class="w-16 h-16 rounded-2xl bg-slate-900 text-white grid place-items-center text-2xl font-extrabold shrink-0" aria-hidden="true">{{ mb_substr($post->author->name, 0, 1) }}</span>
        @endif
        <div class="min-w-0">
          <p class="kicker text-slate-500">{{ $post->author->roleLabel() }}</p>
          <p class="font-extrabold text-lg"><a href="/penulis/{{ $post->author->username }}" class="hover:text-red-700">{{ $post->author->name }}</a></p>
          @if($post->author->bio)<p class="text-sm text-slate-600 mt-1 leading-relaxed">{{ $post->author->bio }}</p>@endif
          <a href="/penulis/{{ $post->author->username }}" class="inline-flex items-center gap-1 text-sm font-bold text-red-700 hover:underline mt-2">Lihat semua artikel →</a>
        </div>
      </div>
      @endif
    </div>

    {{-- Baca juga --}}
    @if(!empty($related) && $related->count())
    <div class="border-t border-slate-100 bg-slate-50/70 px-5 md:px-9 py-6">
      <h2 class="section-flag text-red-700 border-b-[3px] border-double border-slate-900/50 pb-2">Baca Juga</h2>
      <div class="grid sm:grid-cols-2 gap-3 mt-4">
        @foreach($related->take(4) as $rel)
          <a href="/artikel/{{ $rel->slug }}" class="group flex gap-3 bg-white border border-slate-200 rounded-xl p-2.5 hover:border-red-300 hover:shadow-sm transition">
            @if($rel->featured_image)<x-post-image :post="$rel" alt="" sizes="80px" class="w-20 h-20 rounded-lg object-cover shrink-0" />@endif
            <span class="min-w-0 self-center">
              <span class="block kicker text-red-700">{{ $rel->category?->name }}</span>
              <span class="block text-sm font-bold leading-snug line-clamp-2 group-hover:text-red-800">{{ $rel->title }}</span>
            </span>
          </a>
        @endforeach
      </div>
    </div>
    @endif
  </article>

  {{-- ══════ SIDEBAR ══════ --}}
  <aside class="space-y-5 lg:sticky lg:top-24" data-sidebar>
    <div id="riwayat-baca" class="hidden bg-white border border-slate-200/80 rounded-3xl p-5 shadow-sm"></div>

    <div class="bg-white border border-slate-200/80 rounded-3xl p-5 shadow-sm">
      <h2 class="section-flag text-amber-600 border-b-[3px] border-double border-slate-900/50 pb-2 mb-4">Terpopuler</h2>
      <div class="space-y-4">
        @forelse(collect($sidebarPopular ?? $related ?? [])->take(5) as $i => $p)
          @include('components.post-card', ['post' => $p, 'variant' => 'rank', 'number' => $i + 1])
        @empty
          <p class="text-sm text-slate-500">Belum ada data.</p>
        @endforelse
      </div>
    </div>

    <x-ad-slot name="ad_sidebar" min-height="250px" />

    <div class="rounded-3xl bg-slate-950 text-white p-6 bg-dots">
      <p class="font-headline font-bold text-lg leading-snug">Dapatkan ringkasan pagi.</p>
      <p class="text-sm text-slate-300 mt-1">Gratis tiap hari pukul 06.00 WIB.</p>
      <div class="mt-3 bg-white rounded-2xl p-3">@include('components.newsletter-form')</div>
    </div>
  </aside>
</div>

{{-- ARTIKEL TERKAIT --}}
@if(!empty($related) && $related->count())
<section aria-label="Artikel terkait" class="mt-10 bg-white border border-slate-200/80 rounded-3xl p-4 md:p-6 shadow-sm">
  @include('components.section-title', ['title' => 'Artikel Terkait', 'link' => $post->category ? '/kategori/' . $post->category->slug : '/artikel', 'linkText' => 'Lainnya'])
  <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    @foreach($related as $rel)
      @include('components.post-card', ['post' => $rel])
    @endforeach
  </div>
</section>
@endif

{{-- ══════ KOMENTAR ══════ --}}
<section aria-label="Komentar" class="mt-8 bg-white border border-slate-200/80 rounded-3xl p-5 md:p-7 shadow-sm">
  <div class="flex flex-wrap items-center justify-between gap-2">
    <h2 class="font-headline font-black text-xl">Diskusi <span class="text-sm font-bold bg-slate-100 px-2.5 py-1 rounded-[2px]">{{ $comments->count() }}</span></h2>
    <p class="text-xs text-slate-500">Komentar tampil setelah dimoderasi.</p>
  </div>

  <ol class="mt-5 space-y-4">
    @forelse($comments as $c)
      <li class="flex gap-3 border-b border-slate-100 pb-4 last:border-0">
        <span class="w-10 h-10 rounded-[3px] bg-gradient-to-br from-slate-700 to-slate-900 text-white grid place-items-center font-headline font-black shrink-0" aria-hidden="true">{{ mb_substr($c->author_name ?? $c->user?->name ?? 'A', 0, 1) }}</span>
        <div class="min-w-0 flex-1">
          <p class="text-sm font-bold">{{ $c->author_name ?? $c->user?->name ?? 'Anonim' }} <span class="font-medium text-slate-400 text-xs">• {{ $c->created_at->locale('id')->diffForHumans() }}</span></p>
          <p class="text-sm mt-1 leading-relaxed text-slate-700">{{ $c->content }}</p>

          @if($c->replies?->count())
            <ol class="mt-3 space-y-3 border-l-2 border-red-100 pl-4">
              @foreach($c->replies as $r)
                <li>
                  <p class="text-sm font-bold">{{ $r->author_name ?? $r->user?->name ?? 'Anonim' }} @if($r->user?->isAuthor())<span class="text-xs font-semibold text-red-700 bg-red-50 px-1.5 py-0.5 rounded">Redaksi</span>@endif</p>
                  <p class="text-sm mt-0.5 text-slate-700">{{ $r->content }}</p>
                </li>
              @endforeach
            </ol>
          @endif

          <div class="mt-2 flex items-center gap-4">
            <details class="text-sm">
              <summary class="cursor-pointer text-xs font-bold text-slate-600 hover:text-red-700 w-fit">Balas</summary>
              <form action="/artikel/{{ $post->slug }}/komentar" method="POST" class="mt-2 rounded-xl bg-slate-50 border border-slate-200 p-3 space-y-2">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $c->id }}">
                @guest
                  <div class="grid sm:grid-cols-2 gap-2">
                    <label class="sr-only" for="reply-name-{{ $c->id }}">Nama Anda</label>
                    <input id="reply-name-{{ $c->id }}" name="author_name" required placeholder="Nama Anda" class="input !bg-white">
                    <label class="sr-only" for="reply-email-{{ $c->id }}">Email</label>
                    <input id="reply-email-{{ $c->id }}" type="email" name="author_email" required placeholder="nama@email.com" class="input !bg-white">
                  </div>
                @endguest
                <label class="sr-only" for="reply-content-{{ $c->id }}">Balasan</label>
                <textarea id="reply-content-{{ $c->id }}" name="content" rows="2" required placeholder="Tulis balasan santun..." class="input !bg-white"></textarea>
                <div class="hidden" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>
                <button class="btn btn-dark !min-h-[40px] !py-2">Kirim Balasan</button>
              </form>
            </details>
            <button type="button" data-report-open="/komentar/{{ $c->id }}/laporkan" class="text-xs text-slate-400 hover:text-red-600 underline">Laporkan</button>
          </div>
        </div>
      </li>
    @empty
      <li class="text-center py-8">
        <p class="font-bold mt-2">Belum ada komentar.</p>
        <p class="text-sm text-slate-500">Jadilah yang pertama berpendapat dengan bijak.</p>
      </li>
    @endforelse
  </ol>

  @if($post->allow_comments)
  <form action="/artikel/{{ $post->slug }}/komentar" method="POST" class="mt-6 rounded-2xl bg-slate-50 border border-slate-200 p-5 space-y-3">
    @csrf
    <h3 class="font-extrabold">Tulis Komentar</h3>
    @error('content')<p class="text-xs font-bold text-red-600">{{ $message }}</p>@enderror
    @guest
      <div class="grid sm:grid-cols-2 gap-3">
        <div><label for="author_name" class="text-sm font-semibold">Nama</label><input id="author_name" name="author_name" value="{{ old('author_name') }}" required class="input mt-1 !bg-white" placeholder="Nama Anda"></div>
        <div><label for="author_email" class="text-sm font-semibold">Email</label><input id="author_email" type="email" name="author_email" value="{{ old('author_email') }}" required class="input mt-1 !bg-white" placeholder="nama@email.com"><p class="text-[11px] text-slate-500 mt-1">Email tidak dipublikasikan.</p></div>
      </div>
    @endguest
    <div><label for="content" class="text-sm font-semibold">Komentar</label><textarea id="content" name="content" rows="4" required placeholder="Tulis pendapat Anda secara santun..." class="input mt-1 !bg-white">{{ old('content') }}</textarea></div>
    <div class="hidden" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>
    <button class="btn btn-dark">Kirim Komentar</button>
    <p class="text-xs text-slate-500">Komentar dimoderasi sebelum tampil. Dilarang SARA, spam, dan ujaran kebencian.</p>
  </form>
  @else
    <p class="text-sm text-slate-600 mt-4 bg-slate-50 border border-slate-200 rounded-xl p-4">Komentar untuk artikel ini ditutup.</p>
  @endif
</section>

{{-- Dialog lapor komentar --}}
<dialog data-report-dialog class="rounded-2xl p-0 backdrop:bg-slate-950/60 max-w-md w-[92vw]">
  <form method="POST" action="" data-report-action class="p-6 space-y-3">
    @csrf
    <h3 class="font-headline font-bold text-lg">Laporkan komentar</h3>
    <p class="text-sm text-slate-600">Bantu kami menjaga diskusi tetap sehat. Pilih alasan yang paling sesuai.</p>
    <div class="space-y-2 text-sm">
      @foreach(['Spam atau promosi', 'Ujaran kebencian / SARA', 'Kata kasar / pelecehan', 'Informasi menyesatkan', 'Lainnya'] as $reason)
        <label class="flex items-center gap-2.5 border border-slate-200 rounded-xl px-3.5 py-2.5 hover:border-red-300 cursor-pointer">
          <input type="radio" name="reason" value="{{ $reason }}" required class="accent-red-600">
          <span class="font-semibold">{{ $reason }}</span>
        </label>
      @endforeach
    </div>
    <div class="flex gap-2 pt-1">
      <button type="submit" class="btn btn-primary flex-1">Kirim laporan</button>
      <button type="button" data-report-close class="btn btn-outline">Batal</button>
    </div>
  </form>
</dialog>
@endsection
