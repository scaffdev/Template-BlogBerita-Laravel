@props(['title', 'link' => null, 'linkText' => 'Lihat semua', 'accent' => 'bg-red-600', 'subtitle' => null, 'number' => null])
<div class="mb-6">
  <div class="flex items-end justify-between gap-3 border-b-[3px] border-double border-slate-900/70 dark:border-white/40 pb-2">
    <div class="flex items-baseline gap-3 min-w-0">
      @if($number)
        <span class="font-headline text-2xl md:text-3xl font-black text-red-700 leading-none tabular-nums" aria-hidden="true">{{ str_pad($number, 2, '0', STR_PAD_LEFT) }}</span>
      @else
        <span class="{{ $accent }} w-2.5 h-2.5 shrink-0 mb-1" aria-hidden="true"></span>
      @endif
      <div class="min-w-0">
        <h2 class="font-headline text-2xl md:text-[28px] font-black tracking-tight truncate">{{ $title }}</h2>
        @if($subtitle)<p class="font-headline italic text-xs md:text-sm text-slate-500 mt-0.5 truncate">{{ $subtitle }}</p>@endif
      </div>
    </div>
    @if($link)
      <a href="{{ $link }}" class="shrink-0 section-flag text-slate-600 hover:text-red-700 border-b border-slate-400 hover:border-red-600 pb-0.5 transition">{{ $linkText }} →</a>
    @endif
  </div>
</div>
