@extends('layouts.admin')
@section('title', 'Newsletter')
@section('content')
<div class="flex justify-between items-center mb-4"><h1 class="text-xl font-bold">Subscriber ({{ $confirmed }}/{{ $total }} terkonfirmasi)</h1><a href="{{ route('admin.subscribers.broadcast') }}" class="bg-blue-600 text-white px-4 py-2 rounded">📧 Broadcast</a></div>
<div class="bg-white rounded shadow overflow-x-auto"><table class="w-full text-sm">
<thead><tr class="text-left text-gray-500 border-b"><th class="p-3">Email</th><th class="p-3">Nama</th><th class="p-3">Status</th><th class="p-3">Aksi</th></tr></thead>
<tbody>@foreach($subscribers as $s)<tr class="border-b"><td class="p-3">{{ $s->email }}</td><td class="p-3">{{ $s->name ?? '-' }}</td><td class="p-3">{{ $s->unsubscribed_at ? 'berhenti' : ($s->is_confirmed ? 'aktif' : 'belum konfirmasi') }}</td><td class="p-3"><form method="POST" action="{{ route('admin.subscribers.destroy', $s) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="text-red-600">Hapus</button></form></td></tr>@endforeach</tbody>
</table></div>
<div class="mt-4">{{ $subscribers->links() }}</div>
@endsection
