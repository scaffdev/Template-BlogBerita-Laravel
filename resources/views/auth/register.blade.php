@extends('layouts.app')

@section('title', 'Daftar - ' . ($siteName ?? config('app.name')))

@section('content')
<div class="max-w-md mx-auto bg-white border rounded-xl p-6">
  <h1 class="text-2xl font-extrabold">Daftar Akun</h1>
  <form action="/register" method="POST" class="mt-4 space-y-3">
    @csrf
    <div><label for="name" class="text-sm font-medium">Nama Lengkap</label><input id="name" name="name" value="{{ old('name') }}" required class="mt-1 w-full border rounded-lg px-3 py-2 text-sm min-h-[44px]">@error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror</div>
    <div><label for="username" class="text-sm font-medium">Username</label><input id="username" name="username" value="{{ old('username') }}" required class="mt-1 w-full border rounded-lg px-3 py-2 text-sm min-h-[44px]">@error('username')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror</div>
    <div><label for="email" class="text-sm font-medium">Email</label><input id="email" type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full border rounded-lg px-3 py-2 text-sm min-h-[44px]">@error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror</div>
    <div><label for="password" class="text-sm font-medium">Kata Sandi (min. 8 karakter)</label><input id="password" type="password" name="password" required class="mt-1 w-full border rounded-lg px-3 py-2 text-sm min-h-[44px]">@error('password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror</div>
    <div><label for="password_confirmation" class="text-sm font-medium">Konfirmasi Kata Sandi</label><input id="password_confirmation" type="password" name="password_confirmation" required class="mt-1 w-full border rounded-lg px-3 py-2 text-sm min-h-[44px]"></div>
    <button class="w-full px-5 py-2.5 bg-blue-700 text-white text-sm rounded-lg min-h-[44px]">Daftar</button>
  </form>
  <p class="mt-4 text-sm"><a href="/login" class="text-blue-700 hover:underline">Sudah punya akun? Masuk</a></p>
</div>
@endsection
