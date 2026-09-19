@extends('layouts.admin')
@section('title', 'Moderasi Komentar')
@section('content')
<h1 class="text-xl font-bold mb-4">Komentar @if($pendingCount)<span class="text-sm bg-amber-200 px-2 py-1 rounded">{{ $pendingCount }} pending</span>@endif</h1>
<form method="GET" class="mb-3 text-sm"><select name="status" onchange="this.form.submit()" class="border rounded px-3 py-2"><option value="">Semua</option>@foreach(['pending','approved','rejected','spam'] as $s)<option @selected(request('status')==$s) value="{{ $s }}">{{ ucfirst($s) }}</option>@endforeach</select></form>
<div class="bg-white rounded shadow divide-y text-sm">
@foreach($comments as $c)
<div class="p-3 flex justify-between gap-3">
<div><div class="font-medium">{{ $c->author_name ?? $c->user->name ?? 'Anonim' }} <span class="text-gray-500 font-normal">di “{{ $c->post->title ?? '-' }}”</span></div>
<p class="text-gray-700 mt-1">{{ $c->content }}</p>
<div class="text-xs text-gray-500 mt-1">{{ $c->created_at->diffForHumans() }} · {{ $c->status }} · {{ $c->ip_address }}</div></div>
<div class="flex flex-col gap-1 shrink-0">
<form method="POST" action="{{ route('admin.comments.approve', $c) }}">@csrf<button class="text-green-600 border rounded px-2 py-1">Setujui</button></form>
<form method="POST" action="{{ route('admin.comments.reject', $c) }}">@csrf<button class="text-amber-600 border rounded px-2 py-1">Tolak</button></form>
<form method="POST" action="{{ route('admin.comments.spam', $c) }}">@csrf<button class="text-orange-600 border rounded px-2 py-1">Spam</button></form>
<form method="POST" action="{{ route('admin.comments.destroy', $c) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="text-red-600 border rounded px-2 py-1">Hapus</button></form>
</div>
</div>
@endforeach
</div>
<div class="mt-4">{{ $comments->links() }}</div>
@endsection
