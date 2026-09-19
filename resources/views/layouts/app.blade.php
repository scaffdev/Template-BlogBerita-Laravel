<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', ($siteName ?? config('app.name')) . ' - Berita Terkini Bahasa Indonesia')</title>
<meta name="description" content="@yield('meta_description', 'Baca berita terkini, artikel terbaru, dan liputan terpercaya dalam bahasa Indonesia.')">
<link rel="canonical" href="@yield('canonical', url()->current())">
<meta property="og:type" content="@yield('og_type', 'website')">
<meta property="og:site_name" content="{{ $siteName ?? config('app.name') }}">
<meta property="og:title" content="@yield('og_title', trim($__env->yieldContent('title', ($siteName ?? config('app.name')))))">
<meta property="og:description" content="@yield('og_description', trim($__env->yieldContent('meta_description', '')))">
<meta property="og:url" content="@yield('canonical', url()->current())">
<meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('og_title', trim($__env->yieldContent('title', '')))">
<meta name="twitter:description" content="@yield('og_description', trim($__env->yieldContent('meta_description', '')))">
<meta name="twitter:image" content="@yield('og_image', asset('images/og-default.jpg'))">
@yield('meta')
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>html{scroll-behavior:smooth}body{font-family:ui-sans-serif,system-ui,-apple-system,'Segoe UI',Roboto,Arial,sans-serif}</style>
@stack('head')
@stack('jsonld')
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
<a href="#konten" class="sr-only focus:not-sr-only focus:absolute focus:top-0 focus:left-0 focus:bg-white focus:p-2">Lewati ke konten</a>

<header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b" x-data="{open:false}">
  <div class="max-w-6xl mx-auto px-4">
    <div class="flex items-center justify-between h-16 gap-3">
      <a href="/" class="text-xl font-extrabold tracking-tight" aria-label="Beranda">{{ $siteName ?? config('app.name') }}</a>
      <form action="/cari" method="GET" role="search" class="hidden md:flex items-center gap-2 flex-1 max-w-md">
        <label for="q-desktop" class="sr-only">Cari artikel</label>
        <input id="q-desktop" type="search" name="q" value="{{ request('q') }}" placeholder="Cari berita..." class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600">
        <button type="submit" class="px-4 py-2 text-sm bg-blue-700 text-white rounded-lg min-h-[44px]">Cari</button>
      </form>
      <div class="flex items-center gap-2">
        @auth
          <span class="hidden sm:inline text-sm text-gray-600">{{ auth()->user()->name }}</span>
          <form action="/logout" method="POST">@csrf<button class="text-sm px-3 py-2 border rounded-lg min-h-[44px]">Keluar</button></form>
        @else
          <a href="/login" class="text-sm px-4 py-2 bg-blue-700 text-white rounded-lg min-h-[44px] inline-flex items-center">Masuk</a>
        @endauth
        <button @click="open=!open" class="md:hidden px-3 py-2 border rounded-lg min-h-[44px]" aria-label="Buka menu" :aria-expanded="open">☰</button>
      </div>
    </div>
  </div>
  <nav class="border-t bg-white" aria-label="Navigasi utama">
    <div class="max-w-6xl mx-auto px-4 hidden md:flex items-center gap-4 overflow-x-auto py-2 text-sm">
      <a href="/" class="hover:text-blue-700 font-medium">Beranda</a>
      <a href="/artikel" class="hover:text-blue-700 font-medium">Artikel</a>
      @foreach(($menuCategories ?? []) as $cat)
        <a href="/kategori/{{ $cat->slug }}" class="hover:text-blue-700 whitespace-nowrap">{{ $cat->name }}</a>
      @endforeach
    </div>
    <div x-show="open" x-cloak class="md:hidden px-4 py-3 space-y-1 text-sm border-t">
      <form action="/cari" method="GET" class="flex gap-2 mb-2" role="search">
        <label for="q-mobile" class="sr-only">Cari artikel</label>
        <input id="q-mobile" type="search" name="q" value="{{ request('q') }}" placeholder="Cari berita..." class="w-full border rounded-lg px-3 py-2">
        <button class="px-4 py-2 bg-blue-700 text-white rounded-lg">Cari</button>
      </form>
      <a href="/" class="block py-2">Beranda</a>
      <a href="/artikel" class="block py-2">Artikel</a>
      @foreach(($menuCategories ?? []) as $cat)
        <a href="/kategori/{{ $cat->slug }}" class="block py-2">{{ $cat->name }}</a>
      @endforeach
    </div>
  </nav>
</header>

@if(session('success'))
  <div class="max-w-6xl mx-auto px-4 mt-4"><div role="alert" class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg px-4 py-3">{{ session('success') }}</div></div>
@endif
@if(session('error'))
  <div class="max-w-6xl mx-auto px-4 mt-4"><div role="alert" class="bg-red-50 border border-red-200 text-red-800 text-sm rounded-lg px-4 py-3">{{ session('error') }}</div></div>
@endif

<main id="konten" class="max-w-6xl mx-auto px-4 py-6">
  @yield('content')
</main>

<footer class="mt-10 border-t bg-white">
  <div class="max-w-6xl mx-auto px-4 py-10 grid gap-8 md:grid-cols-4">
    <div>
      <p class="font-bold text-lg">{{ $siteName ?? config('app.name') }}</p>
      <p class="text-sm text-gray-600 mt-2">Media berita berbahasa Indonesia: cepat, akurat, dan terpercaya.</p>
      <div class="flex gap-3 mt-3 text-sm">
        <a href="#" aria-label="Facebook" class="underline">FB</a>
        <a href="#" aria-label="X" class="underline">X</a>
        <a href="#" aria-label="Instagram" class="underline">IG</a>
        <a href="/feed" aria-label="RSS" class="underline">RSS</a>
      </div>
    </div>
    <nav aria-label="Kategori footer">
      <p class="font-semibold mb-2">Kategori</p>
      <ul class="space-y-1 text-sm">
        @forelse(($menuCategories ?? []) as $cat)
          <li><a class="hover:text-blue-700" href="/kategori/{{ $cat->slug }}">{{ $cat->name }}</a></li>
        @empty
          <li><a class="hover:text-blue-700" href="/artikel">Semua Artikel</a></li>
        @endforelse
      </ul>
    </nav>
    <nav aria-label="Tautan legal">
      <p class="font-semibold mb-2">Informasi</p>
      <ul class="space-y-1 text-sm">
        <li><a class="hover:text-blue-700" href="/redaksi">Redaksi</a></li>
        <li><a class="hover:text-blue-700" href="/pedoman-media-siber">Pedoman Media Siber</a></li>
        <li><a class="hover:text-blue-700" href="/kebijakan-privasi">Kebijakan Privasi</a></li>
        <li><a class="hover:text-blue-700" href="/tentang">Tentang Kami</a></li>
      </ul>
    </nav>
    <div>
      <p class="font-semibold mb-2">Newsletter</p>
      <p class="text-sm text-gray-600 mb-2">Dapatkan artikel terbaru di email Anda.</p>
      @include('components.newsletter-form')
    </div>
  </div>
  <div class="border-t py-4 text-center text-xs text-gray-500">© {{ date('Y') }} {{ $siteName ?? config('app.name') }}. Hak cipta dilindungi.</div>
</footer>

@stack('scripts')
</body>
</html>
