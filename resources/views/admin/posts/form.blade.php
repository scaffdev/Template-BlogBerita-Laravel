@extends('layouts.admin')
@section('title', ($post->exists ? 'Edit' : 'Tulis').' Artikel')
@section('content')
<form id="postForm" method="POST" action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}" enctype="multipart/form-data" class="grid lg:grid-cols-3 gap-4">
@csrf @if($post->exists) @method('PUT') @endif
<input type="hidden" name="post_id" id="post_id" value="{{ $post->id }}">
<div class="lg:col-span-2 space-y-4">
<div class="bg-white rounded shadow p-4 space-y-3">
<label class="block text-sm">Judul<input id="title" name="title" value="{{ old('title', $post->title) }}" required class="mt-1 w-full border rounded px-3 py-2"></label>
<label class="block text-sm">Slug<input id="slug" name="slug" value="{{ old('slug', $post->slug) }}" class="mt-1 w-full border rounded px-3 py-2 font-mono text-xs"></label>
<label class="block text-sm">Ringkasan (excerpt)<textarea name="excerpt" rows="2" maxlength="500" class="mt-1 w-full border rounded px-3 py-2">{{ old('excerpt', $post->excerpt) }}</textarea></label>
<label class="block text-sm">Poin “Yang perlu diketahui” <span class="text-gray-400">(maks 4 baris — satu poin per baris)</span><textarea name="summary_points" rows="3" maxlength="1000" class="mt-1 w-full border rounded px-3 py-2" placeholder="Fakta utama&#10;Konteks singkat&#10;Dampak bagi pembaca">{{ old('summary_points', is_array($post->summary_points) ? implode("\n", $post->summary_points) : '') }}</textarea></label>
<div>
<div class="flex flex-wrap gap-1 mb-2 text-xs" id="toolbar">
<button type="button" data-cmd="bold" class="border rounded px-2 py-1 font-bold">B</button>
<button type="button" data-cmd="italic" class="border rounded px-2 py-1 italic">I</button>
<button type="button" data-tag="h2" class="border rounded px-2 py-1">H2</button>
<button type="button" data-tag="h3" class="border rounded px-2 py-1">H3</button>
<button type="button" data-cmd="blockquote" class="border rounded px-2 py-1">❝ Quote</button>
<button type="button" data-cmd="insertUnorderedList" class="border rounded px-2 py-1">• List</button>
<button type="button" data-cmd="link" class="border rounded px-2 py-1">🔗 Link</button>
<button type="button" data-cmd="img" class="border rounded px-2 py-1">🖼️ Img</button>
<button type="button" data-cmd="youtube" class="border rounded px-2 py-1">▶ YouTube</button>
</div>
<div id="editor" contenteditable="true" class="w-full border rounded px-3 py-2 min-h-[320px] prose max-w-none focus:outline-none">{!! old('content', $post->content) !!}</div>
<textarea name="content" id="content" class="hidden">{{ old('content', $post->content) }}</textarea>
</div>
</div>
<div class="bg-white rounded shadow p-4 grid md:grid-cols-2 gap-3">
<label class="block text-sm">Meta title (<span id="cTitle">0</span>/70)<input name="meta_title" id="meta_title" maxlength="70" value="{{ old('meta_title', $post->meta_title) }}" class="mt-1 w-full border rounded px-3 py-2"></label>
<label class="block text-sm md:col-span-2">Meta description (<span id="cDesc">0</span>/160)<textarea name="meta_description" id="meta_description" maxlength="160" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('meta_description', $post->meta_description) }}</textarea></label>
<div class="md:col-span-2 rounded border p-3 text-xs" id="seoBox" aria-live="polite">
<p class="font-bold text-sm mb-1">📊 Skor SEO: <span id="seoScore">—</span></p>
<ul id="seoList" class="list-disc ml-5 space-y-0.5 text-gray-600"></ul>
</div>
</div>
<div class="bg-white rounded shadow p-4 grid md:grid-cols-2 gap-3 text-sm">
<h2 class="md:col-span-2 font-bold">📚 Seri, Kolaborasi & Monetisasi</h2>
<label class="block">Nama seri (opsional)<input name="series_name" value="{{ old('series_name', $post->series_name) }}" placeholder="cth: Pilpres 2029" class="mt-1 w-full border rounded px-3 py-2"></label>
<label class="block">Urutan dalam seri<input type="number" name="series_order" min="1" max="999" value="{{ old('series_order', $post->series_order) }}" class="mt-1 w-full border rounded px-3 py-2"></label>
<label class="block md:col-span-2">Co-author (nama, pisahkan koma, maks 3)<input name="co_authors" value="{{ old('co_authors', is_array($post->co_authors) ? implode(', ', $post->co_authors) : $post->co_authors) }}" placeholder="Nama kedua, Nama ketiga" class="mt-1 w-full border rounded px-3 py-2"></label>
<label class="flex items-center gap-2"><input type="checkbox" name="is_sponsored" value="1" @checked(old('is_sponsored',$post->is_sponsored))> Artikel bersponsor</label>
<label class="block">Label sponsor<input name="sponsored_label" value="{{ old('sponsored_label', $post->sponsored_label ?? 'Konten Bersponsor') }}" class="mt-1 w-full border rounded px-3 py-2"></label>
<label class="flex items-center gap-2 md:col-span-2"><input type="checkbox" name="is_liveblog" value="1" @checked(old('is_liveblog',$post->is_liveblog))> 🔴 Liveblog (liputan langsung berurutan)</label>
</div>
@if($post->exists && $post->is_liveblog)
<div class="bg-white rounded shadow p-4 text-sm">
<h2 class="font-bold mb-2">🔴 Kelola Liveblog ({{ $post->liveUpdates->count() }} update)</h2>
<form method="POST" action="{{ route('admin.posts.live.store', $post) }}" class="flex flex-col sm:flex-row gap-2">@csrf
<input name="content" required maxlength="2000" placeholder="Tulis update terbaru..." class="flex-1 border rounded px-3 py-2">
<label class="flex items-center gap-1 text-xs whitespace-nowrap"><input type="checkbox" name="is_key_moment" value="1"> Momen kunci</label>
<button class="bg-red-600 text-white px-4 py-2 rounded text-xs font-bold">+ Tambah</button>
</form>
<ol class="mt-3 space-y-2 max-h-64 overflow-auto">
@foreach($post->liveUpdates as $u)
<li class="flex gap-2 items-start border-t pt-2"><span class="flex-1">{{ $u->content }} <span class="text-gray-400 text-xs">({{ $u->created_at->format('d M H:i') }})</span>@if($u->is_key_moment) <b class="text-amber-600 text-xs">★ KUNCI</b>@endif</span>
<form method="POST" action="{{ route('admin.posts.live.destroy', $u) }}" onsubmit="return confirm('Hapus update ini?')">@csrf @method('DELETE')<button class="text-red-600 text-xs underline">Hapus</button></form></li>
@endforeach
</ol>
</div>
@endif
</div>
<div class="space-y-4">
<div class="bg-white rounded shadow p-4 space-y-3 text-sm">
<div class="flex gap-2">
<button class="bg-red-600 text-white px-4 py-2 rounded flex-1">💾 Simpan</button>
@if($post->exists)<a href="{{ route('admin.posts.preview', $post) }}" class="border px-4 py-2 rounded">👁️ Preview</a>@endif
</div>
<div id="autosaveInfo" class="text-xs text-gray-500">Autosave tiap 30 detik.</div>
<label class="block">Status<select name="status" class="mt-1 w-full border rounded px-3 py-2">@foreach(['draft'=>'Draft','pending'=>'Menunggu Review','published'=>'Terbit','archived'=>'Arsip'] as $k=>$v)<option value="{{ $k }}" @selected(old('status',$post->status)==$k)>{{ $v }}</option>@endforeach</select></label>
<label class="block">Jadwal terbit<input type="datetime-local" name="published_at" value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}" class="mt-1 w-full border rounded px-3 py-2"></label>
<label class="block">Kategori<select name="category_id" class="mt-1 w-full border rounded px-3 py-2"><option value="">—</option>@foreach($categories as $c)<option value="{{ $c->id }}" @selected(old('category_id',$post->category_id)==$c->id)>{{ $c->name }}</option>@endforeach</select></label>
<label class="block">Tag (pisahkan koma)<input name="tags" value="{{ old('tags', $post->relationLoaded('tags') ? $post->tags->pluck('name')->join(', ') : '') }}" placeholder="politik, ekonomi" class="mt-1 w-full border rounded px-3 py-2"></label>
<label class="flex items-center gap-2"><input type="checkbox" name="is_headline" value="1" @checked(old('is_headline',$post->is_headline))> Headline</label>
<label class="flex items-center gap-2"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured',$post->is_featured))> Pilihan Editor</label>
<label class="flex items-center gap-2"><input type="checkbox" name="allow_comments" value="1" @checked(old('allow_comments',$post->allow_comments ?? true))> Komentar aktif</label>
</div>
<div class="bg-white rounded shadow p-4 text-sm">
<label class="block">Gambar utama<input type="file" name="featured_image" id="fi" accept="image/*" class="mt-1 w-full"></label>
<label class="block mt-2">Kredit foto<input name="featured_image_credit" value="{{ old('featured_image_credit', $post->featured_image_credit) }}" class="mt-1 w-full border rounded px-3 py-2" placeholder="cth: ANTARA / Nama Fotografer"></label>
@if($post->featured_image)<img id="fiPrev" src="{{ str_starts_with($post->featured_image,'http') ? $post->featured_image : asset('storage/'.$post->featured_image) }}" class="mt-2 rounded max-h-40">@else<img id="fiPrev" class="mt-2 rounded max-h-40 hidden">@endif
</div>
</div>
</form>
@endsection
@push('scripts')
<script>
const titleEl = document.getElementById('title'), slugEl = document.getElementById('slug');
let slugTouched = !!slugEl.value;
slugEl.addEventListener('input', () => slugTouched = true);
titleEl.addEventListener('input', () => {
  if (!slugTouched) slugEl.value = titleEl.value.toLowerCase().replace(/[^a-z0-9]+/gi,'-').replace(/^-|-$/g,'').slice(0,120);
});
const editor = document.getElementById('editor'), hidden = document.getElementById('content');
document.getElementById('postForm').addEventListener('submit', () => hidden.value = editor.innerHTML);
document.getElementById('toolbar').addEventListener('click', e => {
  const b = e.target.closest('button'); if (!b) return;
  editor.focus();
  if (b.dataset.cmd === 'bold' || b.dataset.cmd === 'italic' || b.dataset.cmd === 'insertUnorderedList') {
    document.execCommand(b.dataset.cmd);
  } else if (b.dataset.tag) {
    document.execCommand('formatBlock', false, b.dataset.tag);
  } else if (b.dataset.cmd === 'blockquote') {
    document.execCommand('formatBlock', false, 'blockquote');
  } else if (b.dataset.cmd === 'link') {
    const u = prompt('URL tautan:'); if (u) document.execCommand('createLink', false, u);
  } else if (b.dataset.cmd === 'img') {
    const u = prompt('URL gambar:'); if (u) document.execCommand('insertImage', false, u);
  } else if (b.dataset.cmd === 'youtube') {
    const u = prompt('URL YouTube (embed/watch):'); if (u) {
      const id = (u.match(/(?:v=|youtu\.be\/|embed\/)([\w-]{6,})/)||[])[1] || '';
      if (id) document.execCommand('insertHTML', false, `<iframe width="560" height="315" src="https://www.youtube.com/embed/${id}" frameborder="0" allowfullscreen></iframe><p></p>`);
    }
  }
});
// SEO counter + skor SEO (SEO-10)
const mt = document.getElementById('meta_title'), md = document.getElementById('meta_description');
const upd = () => {
  document.getElementById('cTitle').textContent = mt.value.length;
  document.getElementById('cDesc').textContent = md.value.length;
  // Skor sederhana: judul 30-65, desc 70-155, excerpt ada, slug ada, konten > 300 kata, gambar utama
  const checks = [];
  const t = titleEl.value.trim();
  checks.push(['Judul 30–65 karakter', t.length >= 30 && t.length <= 65]);
  checks.push(['Meta title terisi (≤70)', mt.value.trim().length >= 10 && mt.value.length <= 70]);
  checks.push(['Meta description 70–155 karakter', md.value.trim().length >= 70 && md.value.length <= 160]);
  checks.push(['Slug terisi & deskriptif', slugEl.value.trim().length >= 10]);
  checks.push(['Konten cukup panjang (>300 kata)', editor.innerText.trim().split(/\s+/).filter(Boolean).length > 300]);
  const score = Math.round(checks.filter((c) => c[1]).length / checks.length * 100);
  const el = document.getElementById('seoScore');
  if (el) {
    el.textContent = score + '/100 ' + (score >= 80 ? '✅ Bagus' : score >= 50 ? '⚠️ Cukup' : '❌ Kurang');
    el.className = score >= 80 ? 'text-green-700 font-bold' : score >= 50 ? 'text-amber-600 font-bold' : 'text-red-600 font-bold';
    document.getElementById('seoList').innerHTML = checks.map((c) => `<li>${c[1] ? '✅' : '❌'} ${c[0]}</li>`).join('');
  }
};
titleEl.addEventListener('input', upd);
mt.addEventListener('input', upd); md.addEventListener('input', upd);
editor.addEventListener('input', upd); upd();
// Featured image preview
document.getElementById('fi').addEventListener('change', e => {
  const f = e.target.files[0]; if (!f) return;
  const img = document.getElementById('fiPrev');
  img.src = URL.createObjectURL(f); img.classList.remove('hidden');
});
// Autosave tiap 30 detik
setInterval(async () => {
  hidden.value = editor.innerHTML;
  const fd = new FormData();
  fd.append('id', document.getElementById('post_id').value || '');
  fd.append('title', titleEl.value); fd.append('content', editor.innerHTML);
  try {
    const r = await fetch("{{ route('admin.posts.autosave') }}", { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }, body: fd });
    const j = await r.json();
    if (j.id) { document.getElementById('post_id').value = j.id; document.getElementById('autosaveInfo').textContent = 'Autosave tersimpan ' + (j.saved_at || ''); }
  } catch(e) {}
}, 30000);
</script>
@endpush
