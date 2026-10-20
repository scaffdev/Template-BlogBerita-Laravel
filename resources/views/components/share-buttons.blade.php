@props(['post'])
@php($shareUrl = url('/artikel/' . $post->slug))
<div class="flex flex-wrap items-center gap-2">
  <span class="text-sm font-bold">Bagikan:</span>
  <button type="button" data-share-native data-url="{{ $shareUrl }}" data-title="{{ $post->title }}"
          class="h-10 px-4 rounded-[2px] bg-slate-900 text-white text-sm font-bold hover:bg-red-700 transition inline-flex items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.7 10.7l6.6-3.3m0 9.2l-6.6-3.3M6 14a2 2 0 100-4 2 2 0 000 4zm12 6a2 2 0 100-4 2 2 0 000 4zM18 8a2 2 0 100-4 2 2 0 000 4z"/></svg>
    Bagikan
  </button>
  <a target="_blank" rel="noopener noreferrer" href="https://wa.me/?text={{ urlencode($post->title . ' ' . $shareUrl) }}" class="w-10 h-10 rounded-[2px] bg-[#25D366] text-white grid place-items-center text-xs font-extrabold hover:scale-105 transition" aria-label="Bagikan ke WhatsApp" title="WhatsApp">WA</a>
  <a target="_blank" rel="noopener noreferrer" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" class="w-10 h-10 rounded-[2px] bg-[#1877F2] text-white grid place-items-center text-xs font-extrabold hover:scale-105 transition" aria-label="Bagikan ke Facebook" title="Facebook">f</a>
  <a target="_blank" rel="noopener noreferrer" href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode($shareUrl) }}" class="w-10 h-10 rounded-[2px] bg-slate-900 text-white grid place-items-center text-xs font-extrabold hover:scale-105 transition" aria-label="Bagikan ke X" title="X">𝕏</a>
  <a target="_blank" rel="noopener noreferrer" href="https://t.me/share/url?url={{ urlencode($shareUrl) }}&text={{ urlencode($post->title) }}" class="w-10 h-10 rounded-[2px] bg-[#229ED9] text-white grid place-items-center text-xs font-extrabold hover:scale-105 transition" aria-label="Bagikan ke Telegram" title="Telegram">TG</a>
  <button type="button" data-copy="{{ $shareUrl }}" data-copy-message="Tautan artikel disalin!"
          class="h-10 px-4 rounded-[2px] border border-slate-300 text-sm font-semibold hover:bg-slate-900 hover:text-white hover:border-slate-900 transition">Salin tautan</button>
</div>
