<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Login Admin</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
<form method="POST" action="{{ route('admin.login.post') }}" class="bg-white rounded shadow p-6 w-full max-w-sm space-y-3 text-sm">
@csrf
<h1 class="text-xl font-bold">🔐 Login Admin</h1>
@if($errors->any())<div class="bg-red-100 text-red-700 p-2 rounded">{{ $errors->first() }}</div>@endif
<label class="block">Email<input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full border rounded px-3 py-2"></label>
<label class="block">Password<input type="password" name="password" required class="mt-1 w-full border rounded px-3 py-2"></label>
<label class="flex items-center gap-2"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
<button class="w-full bg-gray-900 text-white py-2 rounded">Masuk</button>
<a href="/" class="block text-center text-blue-600">← Kembali ke situs</a>
</form>
</body></html>
