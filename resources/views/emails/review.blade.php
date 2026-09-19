<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>Artikel menunggu review</title></head>
<body style="font-family:sans-serif;max-width:600px;margin:auto;">
<h2>Artikel menunggu review</h2>
<p><strong>{{ $post->title }}</strong> oleh {{ $post->author->name ?? 'penulis' }}</p>
<p>{{ $post->excerpt }}</p>
<p><a href="{{ url('/admin/posts/'.$post->id.'/edit') }}">Buka di admin panel</a></p>
</body></html>
