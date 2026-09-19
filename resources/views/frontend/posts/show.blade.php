@extends('layouts.app')

@section('title', $post->meta_title ?: $post->title)
@section('meta_description', $post->meta_description ?: ($post->excerpt ?: substr(strip_tags($post->content), 0, 160)))
@section('canonical', url('/artikel/' . $post->slug))
@section('og_type', 'article')
@section('og_title', $post->title)
@section('og_description', $post->meta_description ?: ($post->excerpt ?? ''))
@section('og_image', $post->featured_image ? (str_starts_with($post->featured_image,'http') ? $post->featured_image : asset('storage/' . ltrim($post->featured_image,'/'))) : asset('images/og-default.jpg'))

@push('jsonld')
<script type="application/ld+json">{!! json_encode($jsonLd ?? [], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
@if(!empty($breadcrumbJsonLd))
<script type="application/ld+json">{!! json_encode($breadcrumbJsonLd, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
@endif
@endpush

@section('content')
{{-- Progress bar baca --}}
<div x-data="{w:0}" x-init="window.addEventListener('scroll',()=>{const h=document.documentElement;w=(h.scrollTop/(h.scrollHeight-h.clientHeight))*100})" class="fixed top-0 left-0 h-1 bg-blue-700 z-50" :style="`width:${w}%`" aria-hidden="true"></div>

<nav aria-label="Breadcrumb" class="text-sm text-gray-500 mb-3">
  <ol class="flex flex-wrap gap-1">
    <li><a href="/" class="hover:text-blue-700">Beranda</a> <span aria-hidden="true">›</span></li>
    @if($post->category)<li><a href="/kategori/{{ $post->category->slug }}" class="hover:text-blue-700">{{ $post->category->name }}</a> <span aria-hidden="true">›</span></li>@endif
    <li aria-current="page" class="text-gray-700 line-clamp-1">{{ $post->title }}</li>
  </ol>
</nav>

<article class="bg-white border rounded-xl p-5 md:p-8">
  @if($post->category)
    <a href="/kategori/{{ $post->category->slug }}" class="inline-block text-xs font-bold text-white bg-blue-700 px-3 py-1 rounded-full">{{ $post->category->name }}</a>
  @endif
  <h1 class="text-2xl md:text-4xl font-extrabold leading-tight mt-3">{{ $post->title }}</h1>

  <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-gray-500 mt-3">
    <a href="/penulis/{{ $post->author?->username ?? '' }}" class="font-medium text-gray-700 hover:text-blue-700">{{ $post->author?->name ?? 'Redaksi' }}</a>
    <span aria-hidden="true">•</span>
    <span>Terbit: <time datetime="{{ optional($post->published_at)->toIso8601String() }}">{{ optional($post->published_at)->locale('id')->translatedFormat('d F Y, H:i') }}</time></span>
    @if($post->updated_at && $post->published_at && $post->updated_at->gt($post->published_at))
      <span aria-hidden="true">•</span>
      <span>Update: <time datetime="{{ $post->updated_at->toIso8601String() }}">{{ $post->updated_at->locale('id')->translatedFormat('d F Y') }}</time></span>
    @endif
    @if($post->reading_time)<span aria-hidden="true">•</span><span>{{ $post->reading_time }} menit baca</span>@endif
    <span aria-hidden="true">•</span><span>{{ number_format($post->views_count) }} dibaca</span>
  </div>

  @if($post->featured_image)
    <figure class="mt-5">
      <img src="{{ str_starts_with($post->featured_image,'http') ? $post->featured_image : asset('storage/' . ltrim($post->featured_image,'/')) }}" alt="{{ $post->title }}" class="w-full rounded-xl object-cover" loading="lazy">
    </figure>
  @endif

  {{-- TOC otomatis dari h2/h3 --}}
  <div x-data="{toc:[]}" x-init="toc=[...document.querySelectorAll('#isi-artikel h2, #isi-artikel h3')].map((el,i)=>{el.id=el.id||('bagian-'+(i+1));return{id:el.id,text:el.innerText,level:el.tagName}})" class="mt-6">
    <template x-if="toc.length">
      <nav aria-label="Daftar isi" class="border rounded-xl p-4 bg-gray-50 text-sm">
        <p class="font-bold mb-2">Daftar Isi</p>
        <ul class="space-y-1">
          <template x-for="item in toc" :key="item.id">
            <li :class="item.level==='H3' ? 'ml-4' : ''"><a :href="'#'+item.id" class="hover:text-blue-700" x-text="item.text"></a></li>
          </template>
        </ul>
      </nav>
    </template>
  </div>

  <div id="isi-artikel" class="prose max-w-[700px] mx-auto mt-6 prose-headings:scroll-mt-24">
    {!! $post->content !!}
  </div>

  @if($post->tags->count())
    <div class="mt-6 flex flex-wrap gap-2" aria-label="Tag">
      @foreach($post->tags as $tag)
        <a href="/tag/{{ $tag->slug }}" class="text-xs px-3 py-1.5 border rounded-full hover:bg-gray-100">#{{ $tag->name }}</a>
      @endforeach
    </div>
  @endif

  <div class="mt-6 pt-4 border-t">
    @include('components.share-buttons', ['post' => $post])
  </div>

  {{-- Author box --}}
  @if($post->author)
  <div class="mt-6 flex gap-4 bg-gray-50 border rounded-xl p-4">
    @if($post->author->avatar)
      <img src="{{ str_starts_with($post->author->avatar,'http') ? $post->author->avatar : asset('storage/'.ltrim($post->author->avatar,'/')) }}" alt="Foto {{ $post->author->name }}" class="w-14 h-14 rounded-full object-cover" loading="lazy">
    @endif
    <div>
      <p class="font-bold"><a href="/penulis/{{ $post->author->username }}" class="hover:text-blue-700">{{ $post->author->name }}</a></p>
      @if($post->author->bio)<p class="text-sm text-gray-600 mt-1">{{ $post->author->bio }}</p>@endif
    </div>
  </div>
  @endif
</article>

{{-- TERKAIT --}}
@if(!empty($related) && $related->count())
<section aria-label="Artikel terkait" class="mt-8">
  <h2 class="text-xl font-bold mb-4">Artikel Terkait</h2>
  <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    @foreach($related as $rel)
      @include('components.post-card', ['post' => $rel])
    @endforeach
  </div>
</section>
@endif

{{-- KOMENTAR --}}
<section aria-label="Komentar" class="mt-8 bg-white border rounded-xl p-5">
  <h2 class="text-xl font-bold">Komentar ({{ $comments->count() }})</h2>
  <ol class="mt-4 space-y-4">
    @forelse($comments as $c)
      <li class="border-b pb-4 last:border-0">
        <p class="text-sm font-semibold">{{ $c->author_name ?? $c->user?->name ?? 'Anonim' }} <span class="font-normal text-gray-500 text-xs">• {{ $c->created_at->locale('id')->diffForHumans() }}</span></p>
        <p class="text-sm mt-1">{{ $c->content }}</p>
        @if($c->replies?->count())
          <ol class="ml-5 mt-3 space-y-3 border-l pl-4">
            @foreach($c->replies as $r)
              <li><p class="text-sm font-semibold">{{ $r->author_name ?? $r->user?->name ?? 'Anonim' }}</p><p class="text-sm mt-1">{{ $r->content }}</p></li>
            @endforeach
          </ol>
        @endif
        <form action="/komentar/{{ $c->id }}/laporkan" method="POST" class="mt-2">@csrf<button class="text-xs text-gray-500 hover:text-red-600 underline">Laporkan</button></form>
      </li>
    @empty
      <li class="text-sm text-gray-600">Belum ada komentar. Jadilah yang pertama!</li>
    @endforelse
  </ol>

  @if($post->allow_comments)
  <form action="/artikel/{{ $post->slug }}/komentar" method="POST" class="mt-6 space-y-3">
    @csrf
    <h3 class="font-bold">Tulis Komentar</h3>
    @error('content')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
    @guest
      <div class="grid sm:grid-cols-2 gap-3">
        <div><label for="author_name" class="text-sm font-medium">Nama</label><input id="author_name" name="author_name" value="{{ old('author_name') }}" required class="mt-1 w-full border rounded-lg px-3 py-2 text-sm min-h-[44px]"></div>
        <div><label for="author_email" class="text-sm font-medium">Email</label><input id="author_email" type="email" name="author_email" value="{{ old('author_email') }}" required class="mt-1 w-full border rounded-lg px-3 py-2 text-sm min-h-[44px]"></div>
      </div>
    @endguest
    <div><label for="content" class="text-sm font-medium">Komentar</label><textarea id="content" name="content" rows="4" required class="mt-1 w-full border rounded-lg px-3 py-2 text-sm">{{ old('content') }}</textarea></div>
    {{-- Honeypot: harus kosong --}}
    <div class="hidden" aria-hidden="true"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
    <button class="px-5 py-2.5 bg-blue-700 text-white text-sm rounded-lg min-h-[44px]">Kirim Komentar</button>
    <p class="text-xs text-gray-500">Komentar dimoderasi sebelum tampil.</p>
  </form>
  @else
    <p class="text-sm text-gray-600 mt-4">Komentar untuk artikel ini ditutup.</p>
  @endif
</section>
@endsection
