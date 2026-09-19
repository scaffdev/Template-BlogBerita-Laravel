@extends('layouts.admin')
@section('title', 'Broadcast Newsletter')
@section('content')
<h1 class="text-xl font-bold mb-4">Broadcast Newsletter</h1>
<form method="POST" action="{{ route('admin.subscribers.send') }}" class="bg-white rounded shadow p-4 max-w-3xl space-y-3 text-sm">
@csrf
<label class="block">Subjek<input name="subject" required class="mt-1 w-full border rounded px-3 py-2"></label>
<label class="block">Pengantar tambahan (opsional)<textarea name="body" rows="3" class="mt-1 w-full border rounded px-3 py-2"></textarea></label>
<div><div class="font-medium mb-1">Pilih artikel (kosongkan = 5 terbaru otomatis):</div>
<div class="space-y-1 max-h-64 overflow-y-auto border rounded p-2">
@foreach($posts as $p)<label class="flex gap-2 items-start text-xs"><input type="checkbox" name="post_ids[]" value="{{ $p->id }}" class="mt-1"><span>{{ $p->title }} <span class="text-gray-500">({{ $p->published_at?->format('d M Y') }})</span></span></label>@endforeach
</div></div>
<button class="bg-blue-600 text-white px-4 py-2 rounded">Kirim ke antrean</button>
</form>
@endsection
