@extends('layouts.app')

@section('title', 'Masuk — ' . ($siteName ?? config('app.name')))
@section('meta_description', 'Masuk ke akun pembaca untuk berkomentar, menyimpan artikel, dan mengikuti topik favorit.')

@section('content')
<div class="max-w-md mx-auto">
  <div class="bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm">
    <p class="kicker text-red-700">Akun Pembaca</p>
    <h1 class="font-headline text-2xl md:text-3xl font-bold mt-1">Masuk</h1>
    <p class="text-sm text-slate-500 mt-1">Berkomentar, menyimpan artikel, dan mengikuti topik favorit Anda.</p>

    @if(session('status'))<div role="status" class="mt-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl px-4 py-3">{{ session('status') }}</div>@endif
    @if($errors->any())<div role="alert" class="mt-4 bg-red-50 border border-red-200 text-red-800 text-sm rounded-xl px-4 py-3">{{ $errors->first() }}</div>@endif

    <form method="POST" action="/login" class="mt-5 space-y-4">
      @csrf
      <input type="hidden" name="redirect" value="{{ old('redirect', request('redirect')) }}">
      <div>
        <label for="email" class="text-sm font-semibold">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="input mt-1" placeholder="nama@email.com">
      </div>
      <div>
        <div class="flex items-center justify-between">
          <label for="password" class="text-sm font-semibold">Kata sandi</label>
          <a href="/forgot-password" class="text-xs font-bold text-red-700 hover:underline">Lupa kata sandi?</a>
        </div>
        <input id="password" type="password" name="password" required autocomplete="current-password" class="input mt-1" placeholder="••••••••">
      </div>
      <label class="flex items-center gap-2 text-sm">
        <input type="checkbox" name="remember" value="1" class="accent-red-600"> Ingat saya di perangkat ini
      </label>
      <button type="submit" class="btn btn-primary w-full">Masuk</button>
    </form>
  </div>

  <p class="text-center text-sm text-slate-600 mt-4">Belum punya akun? <a href="/register" class="font-bold text-red-700 hover:underline">Daftar gratis</a></p>
</div>
@endsection
