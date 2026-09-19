@extends('layouts.admin')
@section('title', ($user->exists ? 'Edit' : 'Tambah').' Pengguna')
@section('content')
<form method="POST" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}" class="bg-white rounded shadow p-4 max-w-xl space-y-3 text-sm">
@csrf @if($user->exists) @method('PUT') @endif
<label class="block">Nama<input name="name" value="{{ old('name', $user->name) }}" required class="mt-1 w-full border rounded px-3 py-2"></label>
<label class="block">Username<input name="username" value="{{ old('username', $user->username) }}" required class="mt-1 w-full border rounded px-3 py-2"></label>
<label class="block">Email<input type="email" name="email" value="{{ old('email', $user->email) }}" required class="mt-1 w-full border rounded px-3 py-2"></label>
<label class="block">Password {{ $user->exists ? '(kosongkan bila tidak diubah)' : '' }}<input type="password" name="password" class="mt-1 w-full border rounded px-3 py-2"><input type="password" name="password_confirmation" placeholder="Konfirmasi" class="mt-1 w-full border rounded px-3 py-2"></label>
<label class="block">Role<select name="role" class="mt-1 w-full border rounded px-3 py-2">@foreach(['reader','author','editor','admin'] as $r)<option @selected(old('role',$user->role)==$r) value="{{ $r }}">{{ ucfirst($r) }}</option>@endforeach</select></label>
<label class="block">Bio<textarea name="bio" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('bio', $user->bio) }}</textarea></label>
<button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
</form>
@endsection
