{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<url><loc>{{ url('/') }}</loc><changefreq>daily</changefreq><priority>1.0</priority></url>
<url><loc>{{ url('/artikel') }}</loc><changefreq>daily</changefreq><priority>0.9</priority></url>
@foreach($posts as $post)
<url><loc>{{ url('/artikel/' . $post->slug) }}</loc><lastmod>{{ optional($post->updated_at)->toDateString() }}</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>
@endforeach
@foreach($categories as $cat)
<url><loc>{{ url('/kategori/' . $cat->slug) }}</loc><changefreq>weekly</changefreq><priority>0.6</priority></url>
@endforeach
@foreach($tags as $tag)
<url><loc>{{ url('/tag/' . $tag->slug) }}</loc><changefreq>weekly</changefreq><priority>0.5</priority></url>
@endforeach
@foreach($pages as $page)
<url><loc>{{ url('/' . $page->slug) }}</loc><lastmod>{{ optional($page->updated_at)->toDateString() }}</lastmod><changefreq>monthly</changefreq><priority>0.5</priority></url>
@endforeach
</urlset>
