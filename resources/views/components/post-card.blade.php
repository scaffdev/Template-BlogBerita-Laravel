@props(['post', 'variant' => 'card', 'number' => null, 'eager' => false])

@if($variant === 'overlay')
<article class="img-zoom group relative rounded-2xl overflow-hidden shadow-card bg-slate-900 min-h-[320px] flex">
  <x-post-image :post="$post" :eager="$eager" sizes="(min-width: 1024px) 800px, 100vw" class="absolute inset-0 w-full h-full object-cover" />
  @if(empty($post->featured_image))
    <div class="absolute inset-0 bg-gradient-to-br from-slate-800 via-slate-900 to-red-950"></div>
  @endif
  <div class="absolute inset-0 headline-overlay"></div>
  <div class="relative mt-auto p-5 w-full">
    <div class="flex items-center gap-2 mb-2.5">
      @if($post->category)
        <a href="/kategori/{{ $post->category->slug }}" class="kicker bg-red-600 text-white px-2.5 py-1 rounded-[2px]">{{ $post->category->name }}</a>
      @endif
      @if(!empty($post->is_headline))
        <span class="kicker bg-amber-400 text-slate-900 px-2.5 py-1 rounded-[2px]">Headline</span>
      @elseif(!empty($post->is_featured))
        <span class="kicker bg-amber-400 text-slate-900 px-2.5 py-1 rounded-[2px]">Pilihan Editor</span>
      @endif
    </div>
    <h3 class="font-headline font-black text-white text-2xl md:text-[32px] leading-[1.1]">
      <a href="/artikel/{{ $post->slug }}" class="group-hover:underline decoration-red-500 decoration-2 underline-offset-4">{{ $post->title }}</a>
    </h3>
    @if($post->excerpt)
      <p class="text-sm text-slate-200/90 mt-2 line-clamp-2 hidden sm:block">{{ $post->excerpt }}</p>
    @endif
    <div class="mt-3 flex flex-wrap items-center gap-2.5 text-xs text-slate-300">
      <span class="font-semibold text-white">{{ $post->author?->name ?? 'Redaksi' }}</span>
      <span aria-hidden="true">•</span>
      <time datetime="{{ optional($post->published_at)->toDateString() }}">{{ optional($post->published_at)->locale('id')->translatedFormat('d M Y, H:i') }}</time>
      @if($post->reading_time)<span aria-hidden="true">•</span><span>{{ $post->reading_time }} menit baca</span>@endif
    </div>
  </div>
  <a href="/artikel/{{ $post->slug }}" class="absolute inset-0" aria-label="{{ $post->title }}"><span class="sr-only">{{ $post->title }}</span></a>
</article>

@elseif($variant === 'horizontal')
<article class="group bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-card transition overflow-hidden flex gap-3 p-3">
  @if(!empty($post->featured_image))
    <a href="/artikel/{{ $post->slug }}" tabindex="-1" aria-hidden="true" class="img-zoom shrink-0 w-28 h-24 sm:w-40 sm:h-28 rounded-xl overflow-hidden bg-slate-100 block">
      <x-post-image :post="$post" alt="" sizes="160px" class="w-full h-full object-cover" />
    </a>
  @endif
  <div class="min-w-0 flex flex-col py-0.5">
    @if($post->category)
      <a href="/kategori/{{ $post->category->slug }}" class="kicker text-red-700">{{ $post->category->name }}</a>
    @endif
    <h3 class="font-bold leading-snug text-[15px] mt-0.5 line-clamp-2">
      <a href="/artikel/{{ $post->slug }}" class="group-hover:text-red-700">{{ $post->title }}</a>
    </h3>
    <div class="mt-auto pt-1.5 flex flex-wrap items-center gap-x-2 gap-y-0.5 text-xs text-slate-500">
      <span class="font-medium text-slate-600">{{ $post->author?->name ?? 'Redaksi' }}</span>
      <span aria-hidden="true">•</span>
      <time datetime="{{ optional($post->published_at)->toDateString() }}">{{ optional($post->published_at)->locale('id')->diffForHumans() }}</time>
    </div>
  </div>
</article>

@elseif($variant === 'rank')
<article class="group flex gap-3.5 items-start">
  <span class="font-headline text-4xl font-bold leading-none tabular-nums {{ $number <= 3 ? 'text-red-600' : 'text-slate-300' }}" aria-hidden="true">{{ str_pad($number, 2, '0', STR_PAD_LEFT) }}</span>
  <div class="min-w-0 border-b border-slate-100 pb-3.5 flex-1">
    @if($post->category)
      <a href="/kategori/{{ $post->category->slug }}" class="kicker text-red-700">{{ $post->category->name }}</a>
    @endif
    <h3 class="font-bold text-[15px] leading-snug mt-0.5 line-clamp-2"><a href="/artikel/{{ $post->slug }}" class="group-hover:text-red-700">{{ $post->title }}</a></h3>
    <p class="text-xs text-slate-500 mt-1">
      <span class="sr-only">Nomor {{ $number }}. </span>{{ number_format($post->views_count ?? 0) }} dibaca
      <span aria-hidden="true">•</span> {{ optional($post->published_at)->locale('id')->diffForHumans() }}
    </p>
  </div>
</article>

@else
<article class="group bg-white rounded-2xl overflow-hidden border border-slate-200/80 shadow-sm hover:shadow-card hover:-translate-y-1 transition flex flex-col">
  @if(!empty($post->featured_image))
    <a href="/artikel/{{ $post->slug }}" tabindex="-1" aria-hidden="true" class="img-zoom relative block overflow-hidden bg-slate-100">
      <x-post-image :post="$post" alt="" class="w-full h-48 object-cover" />
      @if($post->category)
        <span class="absolute left-3 top-3 kicker bg-slate-950/85 backdrop-blur text-white px-2.5 py-1 rounded-[2px]">{{ $post->category->name }}</span>
      @endif
      @if(!empty($post->is_featured))
        <span class="absolute right-3 top-3 kicker bg-amber-400 text-slate-900 px-2 py-1 rounded-[2px]">Pilihan</span>
      @endif
    </a>
  @else
    <a href="/artikel/{{ $post->slug }}" tabindex="-1" aria-hidden="true" class="relative block h-24 bg-slate-900 bg-dots">
      <span class="absolute left-3 bottom-2 kicker text-white/80">{{ $post->category?->name ?? 'Berita' }}</span>
    </a>
  @endif
  <div class="p-4 flex flex-col gap-2 flex-1">
    <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest">
      @if(empty($post->featured_image) && $post->category)
        <a href="/kategori/{{ $post->category->slug }}" class="text-red-700">{{ $post->category->name }}</a>
      @endif
      <span class="text-slate-400 font-semibold normal-case tracking-normal">{{ optional($post->published_at)->locale('id')->diffForHumans() }}</span>
    </div>
    <h3 class="font-bold leading-snug text-[16px]">
      <a href="/artikel/{{ $post->slug }}" class="group-hover:text-red-700 line-clamp-2">{{ $post->title }}</a>
    </h3>
    @if($post->excerpt)
      <p class="text-sm text-slate-600 line-clamp-2">{{ $post->excerpt }}</p>
    @endif
    <div class="mt-auto pt-3 border-t border-slate-100 flex items-center gap-2 text-xs text-slate-500">
      <span class="w-6 h-6 rounded-[3px] bg-slate-900 text-white grid place-items-center font-headline font-black text-[10px]" aria-hidden="true">{{ mb_substr($post->author?->name ?? 'R', 0, 1) }}</span>
      <span class="font-semibold text-slate-700 truncate">{{ $post->author?->name ?? 'Redaksi' }}</span>
      <span class="ml-auto shrink-0">{{ $post->reading_time ?? 3 }} mnt</span>
    </div>
  </div>
</article>
@endif
