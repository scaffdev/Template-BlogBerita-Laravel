@extends('layouts.app')

@section('title', 'Daftar — ' . ($siteName ?? config('app.name')))
@section('meta_description', 'Buat akun pembaca gratis untuk berkomentar dan menyimpan artikel.')

@section('content')
<div class="max-w-md mx-auto">
  <div class="bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm">
    <p class="kicker text-red-700">Akun Pembaca</p>
    <h1 class="font-headline text-2xl md:text-3xl font-bold mt-1">Daftar gratis</h1>
    <p class="text-sm text-slate-500 mt-1">Simpan artikel, ikuti topik, dan ikut berdiskusi.</p>

    @if($errors->any())<div role="alert" class="mt-4 bg-red-50 border border-red-200 text-red-800 text-sm rounded-xl px-4 py-3">{{ $errors->first() }}</div>@endif

    <form action="/register" method="POST" class="mt-5 space-y-4">
      @csrf
      <div>
        <label for="name" class="text-sm font-semibold">Nama lengkap</label>
        <input id="name" name="name" value="{{ old('name') }}" required autocomplete="name" class="input mt-1">
        @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
      </div>
      <div>
        <label for="username" class="text-sm font-semibold">Username</label>
        <input id="username" name="username" value="{{ old('username') }}" required autocomplete="username" class="input mt-1" placeholder="huruf kecil, tanpa spasi">
        @error('username')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
      </div>
      <div>
        <label for="email" class="text-sm font-semibold">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="input mt-1">
        @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
      </div>
      <div>
        <label for="password" class="text-sm font-semibold">Kata sandi <span class="font-normal text-slate-500">(min. 8 karakter)</span></label>
        <input id="password" type="password" name="password" required autocomplete="new-password" class="input mt-1">
        @error('password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
      </div>
      <div>
        <label for="password_confirmation" class="text-sm font-semibold">Ulangi kata sandi</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="input mt-1">
      </div>
      <button type="submit" class="btn btn-primary w-full">Daftar</button>
    </form>
  </div>

  <p class="text-center text-sm text-slate-600 mt-4">Sudah punya akun? <a href="/login" class="font-bold text-red-700 hover:underline">Masuk</a></p>
</div>
@endsection
