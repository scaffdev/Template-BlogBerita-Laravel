@props(['paginator'])
@if($paginator->hasPages())
<nav role="navigation" aria-label="Navigasi halaman" class="mt-8 flex items-center justify-center gap-2">
  @if($paginator->onFirstPage())
    <span class="px-4 py-2 text-sm border border-slate-200 rounded-xl text-slate-400 bg-slate-50" aria-disabled="true">← Sebelumnya</span>
  @else
    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-4 py-2 text-sm border border-slate-200 rounded-xl bg-white hover:border-red-300 hover:text-red-700 min-h-[44px] inline-flex items-center transition">← Sebelumnya</a>
  @endif
  <span class="text-sm text-slate-600 px-2 font-semibold" aria-current="page">Hal. {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}</span>
  @if($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-4 py-2 text-sm border border-slate-200 rounded-xl bg-white hover:border-red-300 hover:text-red-700 min-h-[44px] inline-flex items-center transition">Berikutnya →</a>
  @else
    <span class="px-4 py-2 text-sm border border-slate-200 rounded-xl text-slate-400 bg-slate-50" aria-disabled="true">Berikutnya →</span>
  @endif
</nav>
@endif
