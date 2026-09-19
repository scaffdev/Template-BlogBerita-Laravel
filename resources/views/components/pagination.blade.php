@props(['paginator'])
@if($paginator->hasPages())
<nav role="navigation" aria-label="Navigasi halaman" class="mt-8 flex items-center justify-center gap-2">
  @if($paginator->onFirstPage())
    <span class="px-4 py-2 text-sm border rounded-lg text-gray-400 bg-gray-50">← Sebelumnya</span>
  @else
    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-4 py-2 text-sm border rounded-lg bg-white hover:bg-gray-100 min-h-[44px] inline-flex items-center">← Sebelumnya</a>
  @endif
  <span class="text-sm text-gray-600 px-2">Hal. {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}</span>
  @if($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-4 py-2 text-sm border rounded-lg bg-white hover:bg-gray-100 min-h-[44px] inline-flex items-center">Berikutnya →</a>
  @else
    <span class="px-4 py-2 text-sm border rounded-lg text-gray-400 bg-gray-50">Berikutnya →</span>
  @endif
</nav>
@endif
