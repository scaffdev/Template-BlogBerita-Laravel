<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Admin') — {{ config('app.name') }}</title>
<meta name="robots" content="noindex, nofollow">
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 text-slate-900">
<div class="flex min-h-screen">
<aside class="w-60 bg-slate-950 text-slate-300 flex-shrink-0 hidden md:flex md:flex-col">
<a href="{{ route('admin.dashboard') }}" class="px-5 py-4 border-b border-white/10 flex items-center gap-2.5">
<span class="w-8 h-8 rounded-lg bg-gradient-to-br from-red-600 to-rose-800 text-white grid place-items-center font-extrabold text-sm" aria-hidden="true">{{ mb_substr(config('app.name'), 0, 1) }}</span>
<span class="font-extrabold text-white">{{ config('app.name') }}</span>
</a>
<nav class="flex-1 overflow-y-auto py-3 text-sm space-y-0.5 px-2" aria-label="Menu admin">
@php
$adminMenu = [
    ['admin.dashboard', 'Dashboard'],
    ['admin.posts.index', 'Artikel'],
    ['admin.categories.index', 'Kategori'],
    ['admin.tags.index', 'Tag'],
    ['admin.media.index', 'Media'],
    ['admin.comments.index', 'Komentar'],
    ['admin.users.index', 'Pengguna'],
    ['admin.pages.index', 'Halaman'],
    ['admin.subscribers.index', 'Newsletter'],
    ['admin.ads.index', 'Iklan'],
    ['admin.analytics.index', 'Statistik'],
    ['admin.settings.edit', 'Pengaturan'],
    ['admin.activity.index', 'Log Aktivitas'],
    ['admin.redirects.index', 'Redirect'],
];
@endphp
@foreach($adminMenu as [$routeName, $label])
  @php($active = request()->routeIs($routeName) || request()->routeIs(str_replace('.index', '.*', $routeName)))
  <a href="{{ route($routeName) }}" @class(['block px-3 py-2 rounded-lg transition', 'bg-red-600 text-white font-bold' => $active, 'hover:bg-white/10' => ! $active])>
    {{ $label }}
    @if($routeName === 'admin.comments.index' && ($pendingComments ?? 0) > 0)
      <span class="ml-1 bg-amber-400 text-slate-900 text-xs font-extrabold px-2 py-0.5 rounded-full">{{ $pendingComments }}</span>
    @endif
  </a>
@endforeach
</nav>
<div class="px-4 py-3 border-t border-white/10 text-xs">
<a href="/" target="_blank" rel="noopener" class="font-bold text-white hover:text-red-300">Lihat situs →</a>
</div>
</aside>
<div class="flex-1 flex flex-col min-w-0">
<header class="bg-white shadow-sm px-4 py-3 flex items-center justify-between gap-3 sticky top-0 z-40">
<div class="font-semibold truncate">@yield('title', 'Dashboard')</div>
<div class="flex items-center gap-3 text-sm shrink-0">
<a href="/" target="_blank" rel="noopener" class="text-red-700 font-bold hover:underline hidden sm:inline">Lihat situs</a>
<span class="text-slate-500 hidden lg:inline">{{ auth()->user()->name ?? '' }} · {{ auth()->user()->roleLabel() }}</span>
<form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-dark !min-h-[38px] !py-1.5 !px-4">Keluar</button></form>
</div>
</header>

<nav class="md:hidden overflow-x-auto bg-slate-950 text-slate-300 text-sm flex gap-1 px-2 py-2 no-scrollbar" aria-label="Menu admin (mobile)">
@foreach($adminMenu as [$routeName, $label])
  <a href="{{ route($routeName) }}" @class(['px-3 py-1.5 rounded-full whitespace-nowrap', 'bg-red-600 text-white font-bold' => request()->routeIs($routeName) || request()->routeIs(str_replace('.index', '.*', $routeName)), 'hover:bg-white/10' => ! (request()->routeIs($routeName) || request()->routeIs(str_replace('.index', '.*', $routeName)))])>{{ $label }}</a>
@endforeach
</nav>

<main class="p-4 md:p-6 flex-1">
@if(session('success'))<div role="status" data-auto-dismiss class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>@endif
@if(session('error'))<div role="alert" class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm">{{ session('error') }}</div>@endif
@if($errors->any())<div role="alert" class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm"><ul class="list-disc ml-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
@yield('content')
</main>
</div>
</div>
@stack('scripts')
</body>
</html>
