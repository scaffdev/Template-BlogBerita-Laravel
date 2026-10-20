@extends('layouts.app')

@section('title', 'Atur Ulang Kata Sandi — ' . ($siteName ?? config('app.name')))

@section('content')
<div class="max-w-md mx-auto">
  <div class="bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm">
    <p class="kicker text-red-700">Akun Pembaca</p>
    <h1 class="font-headline text-2xl font-bold mt-1">Atur ulang kata sandi</h1>

    @if($errors->any())<div role="alert" class="mt-4 bg-red-50 border border-red-200 text-red-800 text-sm rounded-xl px-4 py-3">{{ $errors->first() }}</div>@endif

    <form action="/reset-password" method="POST" class="mt-5 space-y-4">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">
      <div>
        <label for="email" class="text-sm font-semibold">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required autocomplete="email" class="input mt-1">
      </div>
      <div>
        <label for="password" class="text-sm font-semibold">Kata sandi baru</label>
        <input id="password" type="password" name="password" required autocomplete="new-password" class="input mt-1">
      </div>
      <div>
        <label for="password_confirmation" class="text-sm font-semibold">Ulangi kata sandi baru</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="input mt-1">
      </div>
      <button type="submit" class="btn btn-primary w-full">Simpan kata sandi</button>
    </form>
  </div>
</div>
@endsection
