<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Masuk Redaksi — {{ config('app.name') }}</title>
<meta name="robots" content="noindex, nofollow">
@vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 grid place-items-center p-4 bg-dots">
  <main class="w-full max-w-sm">
    <div class="bg-white text-slate-900 rounded-3xl p-6 md:p-8 shadow-pop">
      <div class="flex items-center gap-3">
        <span class="w-11 h-11 rounded-xl bg-gradient-to-br from-red-600 to-rose-800 text-white grid place-items-center font-extrabold text-xl" aria-hidden="true">{{ mb_substr(config('app.name'), 0, 1) }}</span>
        <div>
          <p class="font-extrabold text-lg leading-tight">{{ config('app.name') }}</p>
          <p class="kicker text-red-700">Panel Redaksi</p>
        </div>
      </div>

      <h1 class="font-headline text-2xl font-bold mt-6">Masuk pengelola</h1>
      <p class="text-sm text-slate-500 mt-1">Untuk penulis, editor, dan admin.</p>

      @if($errors->any())
        <div role="alert" class="mt-4 bg-red-50 border border-red-200 text-red-800 text-sm rounded-xl px-4 py-3">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('admin.login.post') }}" class="mt-5 space-y-4">
        @csrf
        <div>
          <label for="email" class="text-sm font-semibold">Email</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="input mt-1" placeholder="nama@email.com">
        </div>
        <div>
          <label for="password" class="text-sm font-semibold">Kata sandi</label>
          <input id="password" type="password" name="password" required autocomplete="current-password" class="input mt-1" placeholder="••••••••">
        </div>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remember" value="1" class="accent-red-600"> Ingat saya</label>
        <button type="submit" class="btn btn-primary w-full">Masuk</button>
      </form>
    </div>
    <p class="text-center text-xs text-slate-400 mt-4"><a href="/" class="hover:text-white">← Kembali ke situs</a></p>
  </main>
</body>
</html>
