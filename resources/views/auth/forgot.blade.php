@extends('layouts.app')

@section('title', 'Lupa Kata Sandi — ' . ($siteName ?? config('app.name')))

@section('content')
<div class="max-w-md mx-auto">
  <div class="bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm">
    <p class="kicker text-red-700">Akun Pembaca</p>
    <h1 class="font-headline text-2xl font-bold mt-1">Lupa kata sandi</h1>
    <p class="text-sm text-slate-500 mt-1">Masukkan email Anda, kami kirim tautan untuk mengatur ulang kata sandi.</p>

    @if(session('success'))<div role="status" class="mt-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl px-4 py-3">{{ session('success') }}</div>@endif
    @if($errors->any())<div role="alert" class="mt-4 bg-red-50 border border-red-200 text-red-800 text-sm rounded-xl px-4 py-3">{{ $errors->first() }}</div>@endif

    <form action="/forgot-password" method="POST" class="mt-5 space-y-4">
      @csrf
      <div>
        <label for="email" class="text-sm font-semibold">Email</label>
        <input id="email" type="email" name="email" required autocomplete="email" class="input mt-1" placeholder="nama@email.com">
      </div>
      <button type="submit" class="btn btn-primary w-full">Kirim tautan reset</button>
    </form>
  </div>
  <p class="text-center text-sm text-slate-600 mt-4"><a href="/login" class="font-bold text-red-700 hover:underline">← Kembali ke halaman masuk</a></p>
</div>
@endsection
