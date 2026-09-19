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
</div>
</div>
<div class="space-y-4">
<div class="bg-white rounded shadow p-4 space-y-3 text-sm">
<div class="flex gap-2">
<button class="bg-blue-600 text-white px-4 py-2 rounded flex-1">💾 Simpan</button>
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
// SEO counter
const mt = document.getElementById('meta_title'), md = document.getElementById('meta_description');
const upd = () => { document.getElementById('cTitle').textContent = mt.value.length; document.getElementById('cDesc').textContent = md.value.length; };
mt.addEventListener('input', upd); md.addEventListener('input', upd); upd();
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
