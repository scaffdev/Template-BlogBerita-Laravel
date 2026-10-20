<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="theme-color" content="#be123c">
<meta name="csrf-token" content="{{ csrf_token() }}">
@php
    $siteName = $siteName ?? config('app.name');
    $tagline = $siteSettings['site_tagline'] ?? 'Cepat, akurat, terpercaya';
    $logoSetting = $siteSettings['logo'] ?? null;
    $logoUrl = $logoSetting ? (str_starts_with($logoSetting, 'http') ? $logoSetting : asset('storage/' . ltrim($logoSetting, '/'))) : null;
    $ogImage = $siteSettings['og_image'] ?? asset('images/og-default.jpg');
    $socials = collect([
        'Facebook' => $siteSettings['facebook'] ?? null,
        'X' => $siteSettings['twitter'] ?? null,
        'Instagram' => $siteSettings['instagram'] ?? null,
        'YouTube' => $siteSettings['youtube'] ?? null,
    ])->filter();
    $contactEmail = $siteSettings['site_email'] ?? null;
    $contactPhone = $siteSettings['site_phone'] ?? null;
    $contactAddress = $siteSettings['site_address'] ?? null;
    $hasContact = $contactEmail || $contactPhone || $contactAddress;
@endphp
<title>@yield('title', $siteName . ' — Berita Terkini Bahasa Indonesia')</title>
<meta name="description" content="@yield('meta_description', $siteSettings['seo_default_description'] ?? 'Berita terkini, analisis, dan liputan terpercaya dalam bahasa Indonesia.')">
<link rel="canonical" href="@yield('canonical', url()->current())">
<meta property="og:type" content="@yield('og_type', 'website')">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="id_ID">
<meta property="og:title" content="@yield('og_title', trim($__env->yieldContent('title', $siteName)))">
<meta property="og:description" content="@yield('og_description', trim($__env->yieldContent('meta_description', $siteSettings['seo_default_description'] ?? '')))">
<meta property="og:url" content="@yield('canonical', url()->current())">
<meta property="og:image" content="@yield('og_image', $ogImage)">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('og_title', trim($__env->yieldContent('title', $siteName)))">
<meta name="twitter:description" content="@yield('og_description', trim($__env->yieldContent('meta_description', '')))">
<meta name="twitter:image" content="@yield('og_image', $ogImage)">
<meta name="robots" content="max-image-preview:large">
<link rel="alternate" type="application/rss+xml" title="{{ $siteName }} — RSS" href="{{ url('/feed') }}">
<link rel="manifest" href="/manifest.webmanifest">
<link rel="icon" href="{{ !empty($siteSettings['favicon']) ? asset('storage/' . ltrim($siteSettings['favicon'], '/')) : '/favicon.ico' }}">
<link rel="icon" type="image/png" sizes="192x192" href="/images/icon-192.png">
<link rel="icon" type="image/png" sizes="512x512" href="/images/icon-512.png">
<link rel="apple-touch-icon" href="/images/apple-touch-icon.png">

{{-- Set tema SEBELUM render agar tidak berkedip --}}
<script>
(function () {
  try {
    var pref = localStorage.getItem('bk-dark');
    var dark = pref === '1' || (pref === null && window.matchMedia('(prefers-color-scheme: dark)').matches);
    if (dark) document.documentElement.classList.add('dark');
  } catch (e) {}
})();
</script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500..900;1,9..144,500..900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Source+Serif+4:ital,opsz,wght@0,8..60,400..700;1,8..60,400..700&display=swap" rel="stylesheet">
@vite(['resources/css/app.css'])
@yield('meta')
@stack('head')
@stack('jsonld')
<script type="application/ld+json">{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => $siteName,
    'url' => url('/'),
    'logo' => $logoUrl ?: $ogImage,
    'sameAs' => $socials->values()->all(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
</head>
<body class="bg-slate-50 text-slate-900 antialiased pb-[72px] lg:pb-0">
<a href="#konten" class="skip-link">Lewati ke konten</a>

{{-- ═══════ TOPBAR ═══════ --}}
<div class="bg-slate-950 text-slate-400 text-xs">
  <div class="max-w-7xl mx-auto px-4 h-9 flex items-center justify-between gap-3">
    <p class="truncate">
      <time datetime="{{ now()->toDateString() }}">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</time>
      <span class="text-slate-600 mx-1.5" aria-hidden="true">•</span>
      <span class="text-slate-300 font-headline italic">{{ $tagline }}</span>
    </p>
    <div class="flex items-center gap-3 shrink-0">
      <a href="/feed" class="hover:text-white" title="RSS">RSS</a>
      @auth
        <a href="/disimpan" class="hover:text-white">Disimpan</a>
        <a href="/profil" class="hover:text-white hidden sm:inline">Profil</a>
        @if(auth()->user()->isAuthor())
          <a href="/admin" class="hover:text-white hidden sm:inline">Redaksi</a>
        @endif
        <form action="/logout" method="POST" class="inline">@csrf<button type="submit" class="hover:text-white">Keluar</button></form>
      @else
        <a href="/login" class="text-white font-semibold hover:underline">Masuk</a>
        <span class="text-slate-600" aria-hidden="true">|</span>
        <a href="/register" class="hover:text-white">Daftar</a>
      @endauth
    </div>
  </div>
</div>

{{-- ═══════ MASTHEAD ═══════ --}}
<div class="bg-white rule-double-b">
  <div class="max-w-7xl mx-auto px-4 py-4 flex items-center gap-3">
    <button data-menu-btn type="button" class="lg:hidden icon-btn shrink-0" aria-label="Buka menu" aria-expanded="false" aria-controls="mobileMenu">
      <svg data-menu-icon="open" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
      <svg data-menu-icon="close" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
    </button>

    <a href="/" class="flex items-center gap-3 shrink-0" aria-label="{{ $siteName }} — Beranda">
      @if($logoUrl)
        <img src="{{ $logoUrl }}" alt="" class="w-11 h-11 rounded-[3px] object-contain">
      @else
        <span class="w-11 h-11 rounded-[3px] bg-gradient-to-br from-red-700 to-red-950 text-white grid place-items-center font-headline font-black text-2xl" aria-hidden="true">{{ mb_substr($siteName, 0, 1) }}</span>
      @endif
      <span class="leading-none">
        <span class="block font-headline text-[26px] sm:text-[32px] font-black tracking-tight">{{ $siteName }}</span>
        <span class="hidden sm:block font-headline italic text-[12px] text-slate-500 mt-1">{{ $tagline }}</span>
      </span>
    </a>

    <form action="/cari" method="GET" role="search" class="hidden md:block relative ml-auto w-full max-w-sm">
      <div class="flex items-center bg-slate-50 border border-slate-300 rounded-[2px] pl-4 pr-1 py-1 focus-within:bg-white focus-within:border-red-600 transition">
        <label for="q-desktop" class="sr-only">Cari artikel</label>
        <input id="q-desktop" data-search-input data-suggest-input type="search" name="q" value="{{ request()->routeIs('search') ? request('q') : '' }}" placeholder="Cari topik, tokoh, peristiwa…" autocomplete="off"
               role="combobox" aria-expanded="false" aria-controls="q-desktop-box" class="bg-transparent w-full text-sm outline-none placeholder:text-slate-400">
        <button type="submit" class="w-9 h-9 rounded-[2px] bg-slate-900 text-white grid place-items-center hover:bg-red-700 shrink-0 transition" aria-label="Cari">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M21 21l-4.35-4.35M10 18a8 8 0 110-16 8 8 0 010 16z"/></svg>
        </button>
      </div>
      <div class="search-suggest hidden" id="q-desktop-box" data-suggest-box="q-desktop" role="listbox" aria-label="Saran pencarian"></div>
      <span class="sr-only">Tips: tekan Ctrl+K untuk membuka pencarian</span>
    </form>

    <div class="hidden md:flex items-center gap-2 ml-auto md:ml-0">
      <button data-dark-toggle type="button" class="icon-btn" aria-pressed="false" aria-label="Aktifkan mode gelap" title="Mode gelap (D)">
        <svg data-icon-moon xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
        <svg data-icon-sun xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 3v2m0 14v2M5.6 5.6l1.4 1.4m10 10l1.4 1.4M3 12h2m14 0h2M5.6 18.4l1.4-1.4m10-10l1.4-1.4"/><circle cx="12" cy="12" r="4"/></svg>
      </button>
      <a href="#newsletter" class="btn btn-primary !min-h-[40px] !py-2 !px-4">Berlangganan</a>
    </div>

    <button data-search-overlay-open type="button" class="md:hidden icon-btn ml-auto shrink-0" aria-label="Buka pencarian">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M21 21l-4.35-4.35M10 18a8 8 0 110-16 8 8 0 010 16z"/></svg>
    </button>
  </div>
</div>

{{-- ═══════ NAVBAR ═══════ --}}
<header class="sticky top-0 z-40 bg-slate-950/95 backdrop-blur border-b border-white/10 shadow-lg">
  <div class="max-w-7xl mx-auto px-4">
    <nav class="hidden lg:flex items-center gap-0.5" aria-label="Navigasi utama">
      <a href="/" class="px-4 py-4 text-[11px] font-extrabold tracking-[0.16em] uppercase border-b-[3px] transition {{ request()->is('/') ? 'text-white border-red-500' : 'text-slate-300 border-transparent hover:text-white hover:border-red-500/60' }}">Beranda</a>
      <a href="/artikel" class="px-4 py-4 text-[11px] font-extrabold tracking-[0.16em] uppercase border-b-[3px] transition {{ request()->is('artikel*') ? 'text-white border-red-500' : 'text-slate-300 border-transparent hover:text-white hover:border-red-500/60' }}">Terbaru</a>
      @foreach(($menuCategories ?? collect())->take(5) as $cat)
        <a href="/kategori/{{ $cat->slug }}" class="px-4 py-4 text-[11px] font-extrabold tracking-[0.16em] uppercase border-b-[3px] whitespace-nowrap transition {{ request()->is('kategori/' . $cat->slug) ? 'text-white border-red-500' : 'text-slate-300 border-transparent hover:text-white hover:border-red-500/60' }}">{{ $cat->name }}</a>
      @endforeach
      <a href="/cari?sort=populer" class="ml-auto px-4 py-4 text-[11px] font-extrabold tracking-[0.16em] uppercase border-b-[3px] whitespace-nowrap text-amber-300 border-transparent hover:text-amber-200 hover:border-amber-400/70 transition">Terpopuler</a>
    </nav>
  </div>

  {{-- Menu mobile --}}
  <div id="mobileMenu" data-mobile-menu class="hidden lg:hidden border-t border-white/10 bg-slate-950 px-4 py-4">
    <nav class="grid gap-1 text-sm font-semibold text-slate-200" aria-label="Navigasi kanal">
      <a href="/" class="px-3 py-2.5 rounded-lg hover:bg-white/10">Beranda</a>
      <a href="/artikel" class="px-3 py-2.5 rounded-lg hover:bg-white/10">Terbaru</a>
      @foreach(($menuCategories ?? collect()) as $cat)
        <a href="/kategori/{{ $cat->slug }}" class="px-3 py-2.5 rounded-lg hover:bg-white/10">{{ $cat->name }}</a>
      @endforeach
      <a href="/cari?sort=populer" class="px-3 py-2.5 rounded-lg hover:bg-white/10 text-amber-300">Terpopuler</a>
    </nav>
    <div class="mt-3 pt-3 border-t border-white/10 flex items-center justify-between gap-2">
      <button data-dark-toggle type="button" class="btn btn-outline !bg-white/5 !text-slate-200 !border-white/15 !min-h-[40px] !py-2">
        <svg data-icon-moon xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
        <svg data-icon-sun xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 3v2m0 14v2M5.6 5.6l1.4 1.4m10 10l1.4 1.4M3 12h2m14 0h2M5.6 18.4l1.4-1.4m10-10l1.4-1.4"/><circle cx="12" cy="12" r="4"/></svg>
        <span>Mode gelap</span>
      </button>
      @auth
        <a href="/disimpan" class="btn btn-outline !bg-white/5 !text-slate-200 !border-white/15 !min-h-[40px] !py-2">Disimpan</a>
      @else
        <a href="/login" class="btn btn-outline !bg-white/5 !text-slate-200 !border-white/15 !min-h-[40px] !py-2">Masuk</a>
        <a href="/register" class="btn btn-primary !min-h-[40px] !py-2">Daftar</a>
      @endauth
    </div>
  </div>

  {{-- Ticker berita --}}
  @if(($tickerPosts ?? collect())->isNotEmpty())
  <div class="border-t border-white/10 bg-slate-900">
    <div class="max-w-7xl mx-auto px-4 py-2 flex items-center gap-3">
      <span class="shrink-0 inline-flex items-center gap-1.5 bg-red-600 text-white section-flag px-2.5 py-1.5 rounded-[2px]">
        <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping" aria-hidden="true"></span> Terkini
      </span>
      <div class="ticker-wrap relative flex-1 overflow-hidden text-sm text-slate-200" data-ticker aria-label="Berita terkini">
        <div class="ticker-track flex gap-10 whitespace-nowrap w-max">
          @foreach($tickerPosts->concat($tickerPosts) as $t)
            <a href="/artikel/{{ $t->slug }}" class="font-headline italic hover:text-white">• {{ $t->title }}</a>
          @endforeach
        </div>
      </div>
      <button type="button" data-ticker-toggle class="shrink-0 text-[11px] font-bold text-slate-300 hover:text-white border border-white/20 rounded-[2px] px-3 py-1" aria-pressed="false">Jeda</button>
    </div>
  </div>
  @endif
</header>

{{-- Iklan header (hanya jika diaktifkan di admin) --}}
<div class="hidden lg:block max-w-7xl mx-auto px-4 pt-4">
  <x-ad-slot name="ad_header" class="mx-auto max-w-[970px]" min-height="120px" />
</div>

@if(session('success'))
  <div class="max-w-7xl mx-auto px-4 mt-4"><div role="status" data-auto-dismiss class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl px-4 py-3 font-medium">✓ {{ session('success') }}</div></div>
@endif
@if(session('error'))
  <div class="max-w-7xl mx-auto px-4 mt-4"><div role="alert" data-auto-dismiss class="bg-red-50 border border-red-200 text-red-800 text-sm rounded-xl px-4 py-3 font-medium">{{ session('error') }}</div></div>
@endif

<main id="konten" class="max-w-7xl mx-auto px-4 py-6 page-in">
  @yield('content')
</main>

{{-- Iklan footer --}}
<div class="max-w-7xl mx-auto px-4">
  <x-ad-slot name="ad_footer" class="mb-10" min-height="90px" />
</div>

{{-- ═══════ FOOTER ═══════ --}}
<footer class="mt-6 bg-slate-950 text-slate-300">
  <div class="bg-red-700 bg-dots">
    <div class="max-w-7xl mx-auto px-4 py-8 flex flex-col md:flex-row md:items-center gap-5">
      <div class="flex-1">
        <p class="text-white font-headline font-black text-2xl">Jangan lewatkan berita penting.</p>
        <p class="text-red-100 text-sm mt-1">Ringkasan headline dikirim tiap pagi pukul 06.00 WIB. Gratis, tanpa spam.</p>
      </div>
      <div id="newsletter" class="w-full md:max-w-md rounded-[2px] bg-white p-3 shadow-pop scroll-mt-28">
        @include('components.newsletter-form')
      </div>
    </div>
  </div>

  <div class="max-w-7xl mx-auto px-4 py-12 grid gap-10 md:grid-cols-2 lg:grid-cols-[1.3fr_.8fr_.8fr_1.1fr]">
    <div>
      <div class="flex items-center gap-2.5">
        <span class="w-10 h-10 rounded-[3px] bg-gradient-to-br from-red-700 to-red-950 text-white grid place-items-center font-headline font-black text-xl" aria-hidden="true">{{ mb_substr($siteName, 0, 1) }}</span>
        <p class="font-headline font-black text-xl text-white">{{ $siteName }}</p>
      </div>
      <p class="text-sm text-slate-400 mt-3 leading-relaxed">{{ $siteSettings['seo_default_description'] ?? 'Media berita berbahasa Indonesia: headline, analisis, dan liputan mendalam dengan standar jurnalistik.' }}</p>
      @if($socials->isNotEmpty())
        <div class="flex flex-wrap gap-2 mt-4">
          @foreach($socials as $name => $url)
            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="h-9 px-3 rounded-[2px] bg-white/10 hover:bg-red-600 hover:text-white inline-flex items-center text-xs font-bold transition">{{ $name }}</a>
          @endforeach
          <a href="/feed" class="h-9 px-3 rounded-[2px] bg-white/10 hover:bg-red-600 hover:text-white inline-flex items-center gap-1.5 text-xs font-bold transition">RSS</a>
        </div>
      @endif
    </div>

    <nav aria-label="Kanal">
      <p class="section-flag text-red-400 border-b-2 border-white/15 pb-2 mb-4">Kanal</p>
      <ul class="space-y-2 text-sm">
        @forelse(($menuCategories ?? collect()) as $cat)
          <li><a class="hover:text-white hover:translate-x-1 inline-block transition" href="/kategori/{{ $cat->slug }}">→ {{ $cat->name }}</a></li>
        @empty
          <li><a class="hover:text-white" href="/artikel">→ Semua Berita</a></li>
        @endforelse
        <li><a class="hover:text-white" href="/artikel">→ Semua Berita</a></li>
      </ul>
    </nav>

    <nav aria-label="Perusahaan">
      <p class="section-flag text-red-400 border-b-2 border-white/15 pb-2 mb-4">Perusahaan</p>
      <ul class="space-y-2 text-sm">
        <li><a class="hover:text-white" href="/tentang">Tentang Kami</a></li>
        <li><a class="hover:text-white" href="/redaksi">Redaksi</a></li>
        <li><a class="hover:text-white" href="/pedoman-media-siber">Pedoman Media Siber</a></li>
        <li><a class="hover:text-white" href="/kebijakan-privasi">Kebijakan Privasi</a></li>
        <li><a class="hover:text-white" href="/kontak">Kontak</a></li>
      </ul>
    </nav>

    <div>
      <p class="section-flag text-red-400 border-b-2 border-white/15 pb-2 mb-4">Topik Hangat</p>
      <div class="flex flex-wrap gap-2">
        @forelse(($hotTags ?? collect())->take(8) as $tag)
          <a href="/tag/{{ $tag->slug }}" class="text-xs font-semibold px-3 py-1.5 rounded-[2px] bg-white/10 hover:bg-red-600 hover:text-white border border-white/10 transition">#{{ $tag->name }}</a>
        @empty
          <a href="/artikel" class="text-xs font-semibold px-3 py-1.5 rounded-[2px] bg-white/10 hover:bg-red-600 hover:text-white border border-white/10 transition">Semua topik</a>
        @endforelse
      </div>
      @if($hasContact)
      <div class="mt-5 rounded-[2px] bg-white/5 border border-white/10 p-4 text-xs leading-relaxed text-slate-400">
        <p class="font-bold text-white text-sm">Kantor Redaksi</p>
        @if($contactAddress)<p class="mt-1">{{ $contactAddress }}</p>@endif
        @if($contactEmail || $contactPhone)
          <p class="mt-1">
            @if($contactEmail)<a href="mailto:{{ $contactEmail }}" class="hover:text-white">{{ $contactEmail }}</a>@endif
            @if($contactEmail && $contactPhone) &bull; @endif
            @if($contactPhone)<span>{{ $contactPhone }}</span>@endif
          </p>
        @endif
      </div>
      @endif
    </div>
  </div>

  <div class="rule-double-t">
    <div class="max-w-7xl mx-auto px-4 py-8 text-center">
      <p class="font-headline font-black text-2xl text-white">{{ $siteName }}</p>
      <p class="font-headline italic text-xs text-slate-500 mt-1.5">Jurnalisme yang tenang, data yang akurat, konteks yang utuh.</p>
    </div>
    <div class="border-t border-white/10">
      <div class="max-w-7xl mx-auto px-4 py-4 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500">
        <p>© {{ date('Y') }} {{ $siteName }}. Hak cipta dilindungi.</p>
        <p class="flex gap-4">
          <a href="/pedoman-media-siber" class="hover:text-slate-300">Pedoman Media Siber</a>
          <a href="/kebijakan-privasi" class="hover:text-slate-300">Privasi</a>
        </p>
      </div>
    </div>
  </div>
</footer>

{{-- ═══════ BOTTOM NAV (mobile) ═══════ --}}
<nav class="bottom-nav lg:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur border-t border-slate-200" aria-label="Navigasi cepat">
  <div class="grid grid-cols-5">
    @php($bottomItems = [
      ['href' => '/', 'label' => 'Beranda', 'active' => request()->is('/'), 'icon' => 'home'],
      ['href' => '/artikel', 'label' => 'Terbaru', 'active' => request()->is('artikel*'), 'icon' => 'news'],
      ['href' => '#', 'label' => 'Cari', 'active' => false, 'icon' => 'search'],
      ['href' => '/disimpan', 'label' => 'Disimpan', 'active' => request()->is('disimpan'), 'icon' => 'bookmark'],
      ['href' => auth()->check() ? '/profil' : '/login', 'label' => auth()->check() ? 'Profil' : 'Masuk', 'active' => request()->is('profil') || request()->is('login'), 'icon' => 'user'],
    ])
    @foreach($bottomItems as $item)
      @if($item['icon'] === 'search')
        <button type="button" data-search-overlay-open class="flex flex-col items-center justify-center gap-0.5 py-2.5 text-[10px] font-bold text-slate-500">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M21 21l-4.35-4.35M10 18a8 8 0 110-16 8 8 0 010 16z"/></svg>
          {{ $item['label'] }}
        </button>
      @else
        <a href="{{ $item['href'] }}" class="flex flex-col items-center justify-center gap-0.5 py-2.5 text-[10px] font-bold {{ $item['active'] ? 'text-red-700' : 'text-slate-500' }}">
          @if($item['icon'] === 'home')
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5L12 3l9 7.5V21H3z"/></svg>
          @elseif($item['icon'] === 'news')
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5h13a2 2 0 012 2v12H6a2 2 0 01-2-2z M8 9h8M8 13h8M8 17h5"/></svg>
          @elseif($item['icon'] === 'bookmark')
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 4h12v17l-6-4-6 4z"/></svg>
          @else
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path stroke-linecap="round" d="M4 21a8 8 0 0116 0"/></svg>
          @endif
          {{ $item['label'] }}
        </a>
      @endif
    @endforeach
  </div>
</nav>

{{-- ═══════ OVERLAY PENCARIAN (mobile) ═══════ --}}
<div data-search-overlay class="hidden fixed inset-0 z-[80] bg-slate-950/70 backdrop-blur-sm px-4 pt-20">
  <div class="max-w-xl mx-auto bg-white rounded-[2px] p-4 shadow-2xl">
    <div class="flex items-center justify-between mb-3">
      <p class="font-headline font-black text-lg">Cari berita</p>
      <button type="button" data-search-overlay-close class="icon-btn" aria-label="Tutup pencarian">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
      </button>
    </div>
    <form action="/cari" method="GET" role="search" class="relative">
      <label for="q-overlay" class="sr-only">Kata kunci</label>
      <input id="q-overlay" data-search-input data-suggest-input type="search" name="q" placeholder="Cari topik, tokoh, peristiwa…" autocomplete="off"
             role="combobox" aria-expanded="false" aria-controls="q-overlay-box" class="input">
      <div class="search-suggest hidden" id="q-overlay-box" data-suggest-box="q-overlay" role="listbox" aria-label="Saran pencarian"></div>
      <button type="submit" class="btn btn-primary w-full mt-3">Cari</button>
    </form>
    <p class="text-xs text-slate-500 mt-3">Contoh: ekonomi hijau, timnas, pemilu, AI.</p>
  </div>
</div>

<button data-to-top type="button" class="hidden fixed bottom-24 lg:bottom-6 right-4 lg:right-6 z-40 icon-btn shadow-xl" aria-label="Kembali ke atas">
  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19V5M5 12l7-7 7 7"/></svg>
</button>

@if(!empty($siteSettings['ga4_id']))
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $siteSettings['ga4_id'] }}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '{{ $siteSettings['ga4_id'] }}');
</script>
@endif

@vite(['resources/js/app.js'])
@stack('scripts')
</body>
</html>
