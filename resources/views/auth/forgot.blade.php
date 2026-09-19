@extends('layouts.app')

@section('title', 'Lupa Kata Sandi - ' . ($siteName ?? config('app.name')))

@section('content')
<div class="max-w-md mx-auto bg-white border rounded-xl p-6">
  <h1 class="text-2xl font-extrabold">Lupa Kata Sandi</h1>
  <p class="text-sm text-gray-600 mt-1">Masukkan email Anda, kami kirim tautan reset.</p>
  <form action="/forgot-password" method="POST" class="mt-4 space-y-3">
    @csrf
    <div><label for="email" class="text-sm font-medium">Email</label><input id="email" type="email" name="email" required class="mt-1 w-full border rounded-lg px-3 py-2 text-sm min-h-[44px]"></div>
    <button class="w-full px-5 py-2.5 bg-blue-700 text-white text-sm rounded-lg min-h-[44px]">Kirim Tautan Reset</button>
  </form>
</div>
@endsection
