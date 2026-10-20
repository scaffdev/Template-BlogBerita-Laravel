@php($newsletterId = 'newsletter-email-' . \Illuminate\Support\Str::random(6))
<form action="/newsletter/subscribe" method="POST" class="flex flex-col sm:flex-row gap-2 w-full">
  @csrf
  <div class="hidden" aria-hidden="true"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
  <label for="{{ $newsletterId }}" class="sr-only">Alamat email</label>
  <div class="flex items-center flex-1 bg-slate-100 rounded-xl px-3.5 border border-transparent focus-within:bg-white focus-within:border-red-300 transition">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.9 5.3a2 2 0 002.2 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
    <input id="{{ $newsletterId }}" type="email" name="email" required placeholder="nama@email.com" class="w-full bg-transparent py-3 text-sm outline-none placeholder:text-slate-400 min-h-[44px]">
  </div>
  <button type="submit" class="btn btn-dark shrink-0">Langganan</button>
</form>
<p class="text-[11px] text-slate-500 mt-2">Dengan berlangganan, Anda menyetujui <a href="/kebijakan-privasi" class="underline hover:text-red-700">kebijakan privasi</a> kami. Bisa berhenti kapan saja.</p>
