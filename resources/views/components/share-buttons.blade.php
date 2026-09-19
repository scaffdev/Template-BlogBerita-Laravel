@props(['post'])
@php($shareUrl = url('/artikel/' . $post->slug))
<div class="flex flex-wrap items-center gap-2" x-data="{copied:false}">
  <span class="text-sm text-gray-600">Bagikan:</span>
  <a target="_blank" rel="noopener" href="https://wa.me/?text={{ urlencode($post->title . ' ' . $shareUrl) }}" class="text-sm px-3 py-2 border rounded-lg min-h-[44px] inline-flex items-center hover:bg-gray-100" aria-label="Bagikan ke WhatsApp">WA</a>
  <a target="_blank" rel="noopener" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" class="text-sm px-3 py-2 border rounded-lg min-h-[44px] inline-flex items-center hover:bg-gray-100" aria-label="Bagikan ke Facebook">FB</a>
  <a target="_blank" rel="noopener" href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode($shareUrl) }}" class="text-sm px-3 py-2 border rounded-lg min-h-[44px] inline-flex items-center hover:bg-gray-100" aria-label="Bagikan ke X">X</a>
  <button type="button" @click="navigator.clipboard.writeText('{{ $shareUrl }}');copied=true;setTimeout(()=>copied=false,2000)" class="text-sm px-3 py-2 border rounded-lg min-h-[44px] hover:bg-gray-100">Salin tautan</button>
  <span x-show="copied" x-cloak class="text-xs text-green-700">Tautan disalin!</span>
</div>
