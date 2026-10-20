@extends('layouts.admin')
@section('title', 'Slot Iklan')
@section('content')
<h1 class="text-xl font-bold mb-4">Slot Iklan (AdSense / HTML)</h1>
<form method="POST" action="{{ route('admin.ads.update') }}" class="space-y-4 max-w-4xl">
@csrf
@foreach(['ad_header' => 'Header', 'ad_sidebar' => 'Sidebar', 'ad_inarticle' => 'Tengah Artikel', 'ad_footer' => 'Footer'] as $slot => $label)
<div class="bg-white rounded shadow p-4 text-sm">
<div class="flex justify-between items-center mb-2"><h2 class="font-bold">{{ $label }} <span class="font-mono text-xs text-gray-500">{{ $slot }}</span></h2>
<label class="flex items-center gap-2 text-xs"><input type="checkbox" name="{{ $slot }}_enabled" value="1" @checked(($ads[$slot.'_enabled'] ?? '0')==='1')> Aktif</label></div>
<textarea name="{{ $slot }}" rows="4" class="w-full border rounded px-3 py-2 font-mono text-xs" placeholder="Paste kode AdSense / HTML banner di sini…">{{ $ads[$slot] ?? '' }}</textarea>
</div>
@endforeach
<button class="bg-red-600 text-white px-4 py-2 rounded text-sm">Simpan Iklan</button>
</form>
@endsection
