{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
@foreach($posts as $post)
<url>
<loc>{{ url('/artikel/' . $post->slug) }}</loc>
<news:news>
<news:publication>
<news:name>{{ $siteName ?? config('app.name') }}</news:name>
<news:language>id</news:language>
</news:publication>
<news:publication_date>{{ optional($post->published_at)->toAtomString() }}</news:publication_date>
<news:title><![CDATA[{{ $post->title }}]]></news:title>
</news:news>
</url>
@endforeach
</urlset>
