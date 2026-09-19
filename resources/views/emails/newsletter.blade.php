<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>{{ $subjectLine }}</title></head>
<body style="font-family:sans-serif;max-width:600px;margin:auto;background:#f3f4f6;padding:20px;">
<div style="background:#111827;color:#fff;padding:16px;border-radius:8px 8px 0 0;"><h2 style="margin:0;">{{ \App\Models\Setting::get('site_name', config('app.name')) }}</h2><p style="margin:4px 0 0;color:#9ca3af;">{{ \App\Models\Setting::get('site_tagline', '') }}</p></div>
<div style="background:#fff;padding:20px;border-radius:0 0 8px 8px;">
<h3>{{ $subjectLine }}</h3>
@if($body)<p>{!! nl2br(e($body)) !!}</p>@endif
@foreach($posts as $p)
<div style="border-top:1px solid #eee;padding:12px 0;">
<div style="font-weight:bold;"><a href="{{ url('/artikel/'.$p->slug) }}">{{ $p->title }}</a></div>
<div style="color:#666;font-size:13px;">{{ $p->excerpt }}</div>
</div>
@endforeach
<p style="font-size:12px;color:#888;">Tidak ingin menerima email lagi? <a href="{{ url('/newsletter/unsubscribe/'.$unsubToken) }}">Berhenti berlangganan</a></p>
</div>
</body></html>
