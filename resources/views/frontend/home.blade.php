@extends('layouts.app')

@section('title', ($siteName ?? 'Portal Berita') . ' - Berita Terkini Hari Ini')
@section('meta_description', 'Berita terkini hari ini: headline, artikel terbaru, terpopuler, dan liputan per kategori dalam bahasa Indonesia.')

@section('content')
{{-- HERO HEADLINE --}}
<section aria-label="Headline">
  @if($headlines->count())
    <div class="grid gap-4 md:grid-cols-3">
      @php($first = $headlines->first())
      <article class="md:col-span-2 relative rounded-xl overflow-hidden border bg-white">
        @if($first->featured_image)
          <img src="{{ str_starts_with($first->featured_image,'http') ? $first->featured_image : asset('storage/'.ltrim($first->featured_image,'/')) }}" alt="{{ $first->title }}" class="w-full h-64 md:h-96 object-cover" fetchpriority="high">
        @endif
        <div class="p-5">
          <span class="text-xs font-bold text-white bg-red-600 px-2 py-1 rounded">HEADLINE</span>
          <h1 class="text-2xl md:text-3xl font-extrabold mt-2 leading-tight"><a href="/artikel/{{ $first->slug }}" class="hover:text-blue-700">{{ $first->title }}</a></h1>
          <p class="text-sm text-gray-600 mt-2">{{ $first->excerpt }}</p>
          <p class="text-xs text-gray-500 mt-2"><time datetime="{{ optional($first->published_at)->toDateString() }}">{{ optional($first->published_at)->locale('id')->translatedFormat('d F Y') }}</time> • {{ $first->author?->name ?? 'Redaksi' }}</p>
        </div>
      </article>
      <div class="grid gap-4">
        @foreach($headlines->skip(1) as $h)
          @include('components.post-card', ['post' => $h])
        @endforeach
        @foreach($featured->take(2) as $f)
          @include('components.post-card', ['post' => $f])
        @endforeach
      </div>
    </div>
  @endif
</section>

<div class="grid gap-8 lg:grid-cols-3 mt-10">
  {{-- TERBARU --}}
  <section class="lg:col-span-2" aria-label="Artikel terbaru">
    <h2 class="text-xl font-bold mb-4">Terbaru</h2>
    <div class="grid gap-4 sm:grid-cols-2">
      @forelse($latest as $post)
        @include('components.post-card', ['post' => $post])
      @empty
        <p class="text-sm text-gray-600">Belum ada artikel.</p>
      @endforelse
    </div>
    @include('components.pagination', ['paginator' => $latest])
  </section>

  {{-- POPULER SIDEBAR --}}
  <aside aria-label="Artikel populer" class="space-y-4">
    <div class="bg-white border rounded-xl p-5">
      <h2 class="font-bold mb-3">Terpopuler</h2>
      <ol class="space-y-3">
        @foreach($popular as $i => $p)
          <li class="flex gap-3">
            <span class="text-2xl font-extrabold text-gray-200">{{ $i+1 }}</span>
            <div>
              <a href="/artikel/{{ $p->slug }}" class="text-sm font-semibold hover:text-blue-700">{{ $p->title }}</a>
              <p class="text-xs text-gray-500">{{ number_format($p->views_count) }} dibaca</p>
            </div>
          </li>
        @endforeach
      </ol>
    </div>
    <div class="bg-white border rounded-xl p-5">
      <h2 class="font-bold mb-2">Newsletter</h2>
      @include('components.newsletter-form')
    </div>
  </aside>
</div>

{{-- PER KATEGORI --}}
@foreach($categories as $cat)
<section aria-label="Kategori {{ $cat->name }}" class="mt-10">
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-xl font-bold">{{ $cat->name }}</h2>
    <a href="/kategori/{{ $cat->slug }}" class="text-sm text-blue-700 hover:underline">Lihat semua →</a>
  </div>
  <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    @foreach($cat->latestPosts as $post)
      @include('components.post-card', ['post' => $post])
    @endforeach
  </div>
</section>
@endforeach
@endsection
