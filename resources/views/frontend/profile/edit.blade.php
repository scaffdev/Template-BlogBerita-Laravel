@extends('layouts.app')

@section('title', 'Profil Saya - ' . ($siteName ?? config('app.name')))

@section('content')
<div class="max-w-2xl mx-auto bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm">
  <h1 class="font-headline text-2xl font-bold">Profil Saya</h1>
  <p class="text-sm text-slate-500 mt-1">Nama, bio, foto, dan tautan media sosial tampil di halaman penulis.</p>
  @if(session('success'))<div role="status" class="mt-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl px-4 py-3">{{ session('success') }}</div>@endif
  <form method="POST" action="/profil" enctype="multipart/form-data" class="mt-5 space-y-3 text-sm">
    @csrf @method('PUT')
    <div class="flex items-center gap-4">
      <img src="{{ $user->avatarUrl() }}" alt="Avatar" class="w-16 h-16 rounded-2xl object-cover bg-slate-100">
      <label class="block flex-1">Foto profil<input type="file" name="avatar" accept="image/*" class="mt-1 w-full text-sm"></label>
    </div>
    @error('avatar')<p class="text-xs font-bold text-red-600">{{ $message }}</p>@enderror
    <label class="block font-semibold">Nama<input name="name" value="{{ old('name', $user->name) }}" required class="input mt-1"></label>
    <label class="block font-semibold">Username<input name="username" value="{{ old('username', $user->username) }}" required class="input mt-1 font-mono"></label>
    <label class="block font-semibold">Bio singkat<textarea name="bio" rows="3" maxlength="500" class="input mt-1">{{ old('bio', $user->bio) }}</textarea></label>
    <div class="grid sm:grid-cols-2 gap-3">
      <label class="block font-semibold">Twitter/X<input name="twitter" value="{{ old('twitter', $user->social_links['twitter'] ?? '') }}" placeholder="https://x.com/..." class="input mt-1"></label>
      <label class="block font-semibold">Instagram<input name="instagram" value="{{ old('instagram', $user->social_links['instagram'] ?? '') }}" placeholder="https://instagram.com/..." class="input mt-1"></label>
      <label class="block font-semibold">Facebook<input name="facebook" value="{{ old('facebook', $user->social_links['facebook'] ?? '') }}" placeholder="https://facebook.com/..." class="input mt-1"></label>
      <label class="block font-semibold">YouTube<input name="youtube" value="{{ old('youtube', $user->social_links['youtube'] ?? '') }}" placeholder="https://youtube.com/..." class="input mt-1"></label>
    </div>
    <button class="btn btn-dark">Simpan profil</button>
  </form>
</div>
@endsection
