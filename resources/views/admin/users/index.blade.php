@extends('layouts.admin')
@section('title', 'Pengguna')
@section('content')
<div class="flex justify-between items-center mb-4"><h1 class="text-xl font-bold">Pengguna</h1><a href="{{ route('admin.users.create') }}" class="bg-red-600 text-white px-4 py-2 rounded">+ Pengguna</a></div>
<div class="bg-white rounded shadow overflow-x-auto"><table class="w-full text-sm">
<thead><tr class="text-left text-gray-500 border-b"><th class="p-3">Nama</th><th class="p-3">Username</th><th class="p-3">Email</th><th class="p-3">Role</th><th class="p-3 text-right">Artikel</th><th class="p-3">Aksi</th></tr></thead>
<tbody>@foreach($users as $u)<tr class="border-b"><td class="p-3 font-medium">{{ $u->name }}</td><td class="p-3 font-mono text-xs">{{ $u->username }}</td><td class="p-3">{{ $u->email }}</td><td class="p-3"><span class="px-2 py-1 bg-gray-200 rounded text-xs">{{ $u->role }}</span></td><td class="p-3 text-right">{{ $u->posts_count }}</td><td class="p-3"><a href="{{ route('admin.users.edit', $u) }}" class="text-red-700 mr-2">Edit</a>@if($u->id !== auth()->id())<form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="text-red-600">Hapus</button></form>@endif</td></tr>@endforeach</tbody>
</table></div>
<div class="mt-4">{{ $users->links() }}</div>
@endsection
