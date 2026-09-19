@props(['post'])
<article class="bg-white rounded-xl overflow-hidden border hover:shadow-md transition flex flex-col">
  @if(!empty($post->featured_image))
    <a href="/artikel/{{ $post->slug }}" tabindex="-1" aria-hidden="true">
      <img src="{{ str_starts_with($post->featured_image, 'http') ? $post->featured_image : asset('storage/' . ltrim($post->featured_image, '/')) }}" alt="{{ $post->title }}" loading="lazy" class="w-full h-44 object-cover">
    </a>
  @endif
  <div class="p-4 flex flex-col gap-2 flex-1">
    @if($post->category)
      <a href="/kategori/{{ $post->category->slug }}" class="text-xs font-semibold text-blue-700 uppercase tracking-wide">{{ $post->category->name }}</a>
    @endif
    <h3 class="font-bold leading-snug text-base">
      <a href="/artikel/{{ $post->slug }}" class="hover:text-blue-700">{{ $post->title }}</a>
    </h3>
    @if($post->excerpt)
      <p class="text-sm text-gray-600 line-clamp-2">{{ $post->excerpt }}</p>
    @endif
    <div class="mt-auto pt-2 flex items-center gap-2 text-xs text-gray-500">
      <span>{{ $post->author?->name ?? 'Redaksi' }}</span>
      <span aria-hidden="true">•</span>
      <time datetime="{{ optional($post->published_at)->toDateString() }}">{{ optional($post->published_at)->locale('id')->translatedFormat('d M Y') }}</time>
      @if($post->reading_time)<span aria-hidden="true">•</span><span>{{ $post->reading_time }} mnt</span>@endif
    </div>
  </div>
</article>
