<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Admin') — {{ config('app.name') }}</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">
<div class="flex min-h-screen">
<aside class="w-60 bg-gray-900 text-gray-200 flex-shrink-0 hidden md:flex md:flex-col">
<div class="px-5 py-4 text-xl font-bold text-white border-b border-gray-800">📰 Admin Panel</div>
<nav class="flex-1 overflow-y-auto py-3 text-sm space-y-0.5 px-2">
<a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded hover:bg-gray-800">📊 Dashboard</a>
<a href="{{ route('admin.posts.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">📝 Artikel</a>
<a href="{{ route('admin.categories.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">📁 Kategori</a>
<a href="{{ route('admin.tags.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">🏷️ Tag</a>
<a href="{{ route('admin.media.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">🖼️ Media</a>
<a href="{{ route('admin.comments.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">💬 Komentar @if(($pendingComments ?? 0) > 0)<span class="ml-1 bg-red-600 text-white text-xs px-2 py-0.5 rounded-full">{{ $pendingComments }}</span>@endif</a>
<a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">👥 Pengguna</a>
<a href="{{ route('admin.pages.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">📄 Halaman</a>
<a href="{{ route('admin.subscribers.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">✉️ Newsletter</a>
<a href="{{ route('admin.ads.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">📢 Iklan</a>
<a href="{{ route('admin.analytics.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">📈 Statistik</a>
<a href="{{ route('admin.settings.edit') }}" class="block px-3 py-2 rounded hover:bg-gray-800">⚙️ Pengaturan</a>
<a href="{{ route('admin.activity.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">🧾 Log</a>
<a href="{{ route('admin.redirects.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">🔀 Redirect</a>
</nav>
</aside>
<div class="flex-1 flex flex-col min-w-0">
<header class="bg-white shadow px-4 py-3 flex items-center justify-between">
<div class="font-semibold">@yield('title', 'Dashboard')</div>
<div class="flex items-center gap-3 text-sm">
<a href="/" target="_blank" class="text-blue-600 hover:underline">Lihat Situs</a>
<span class="text-gray-600">{{ auth()->user()->name ?? '' }} ({{ auth()->user()->role ?? '' }})</span>
<form method="POST" action="{{ route('logout') }}">@csrf<button class="bg-gray-900 text-white px-3 py-1.5 rounded">Logout</button></form>
</div>
</header>
<main class="p-4 md:p-6">
@if(session('success'))<div class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded">{{ session('success') }}</div>@endif
@if($errors->any())<div class="mb-4 bg-red-100 border border-red-300 text-red-800 px-4 py-2 rounded"><ul class="list-disc ml-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
@yield('content')
</main>
</div>
</div>
@stack('scripts')
</body>
</html>
