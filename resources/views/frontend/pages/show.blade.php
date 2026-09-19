@extends('layouts.app')

@section('title', $page->title . ' - ' . ($siteName ?? config('app.name')))
@section('meta_description', substr(strip_tags($page->content), 0, 160))

@section('content')
<nav aria-label="Breadcrumb" class="text-sm text-gray-500 mb-3"><a href="/" class="hover:text-blue-700">Beranda</a> <span aria-hidden="true">›</span> <span class="text-gray-700">{{ $page->title }}</span></nav>
<article class="bg-white border rounded-xl p-6 md:p-8">
  <h1 class="text-2xl md:text-3xl font-extrabold">{{ $page->title }}</h1>
  <div class="prose max-w-[700px] mt-4">
    {!! $page->content !!}
  </div>
</article>
@endsection
