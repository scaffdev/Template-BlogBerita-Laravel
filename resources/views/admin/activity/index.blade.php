@extends('layouts.admin')
@section('title', 'Log Aktivitas')
@section('content')
<h1 class="text-xl font-bold mb-4">Log Aktivitas</h1>
<div class="bg-white rounded shadow overflow-x-auto"><table class="w-full text-sm">
<thead><tr class="text-left text-gray-500 border-b"><th class="p-3">Waktu</th><th class="p-3">Pelaku</th><th class="p-3">Aktivitas</th><th class="p-3">Subjek</th></tr></thead>
<tbody>@foreach($logs as $l)<tr class="border-b"><td class="p-3 whitespace-nowrap">{{ $l->created_at }}</td><td class="p-3">{{ $l->causer->name ?? '-' }}</td><td class="p-3">{{ $l->description }}</td><td class="p-3 font-mono text-xs">{{ $l->subject_type }} #{{ $l->subject_id }}</td></tr>@endforeach</tbody>
</table></div>
<div class="mt-4">{{ $logs->links() }}</div>
@endsection
