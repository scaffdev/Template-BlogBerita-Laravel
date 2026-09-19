{{-- RSS 2.0 --}}
{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
<title>{{ $siteName ?? config('app.name') }}</title>
<link>{{ url('/') }}</link>
<description>Berita terkini dari {{ $siteName ?? config('app.name') }}</description>
<language>id</language>
<atom:link href="{{ url('/feed') }}" rel="self" type="application/rss+xml"/>
@foreach($posts as $post)
<item>
<title><![CDATA[{{ $post->title }}]]></title>
<link>{{ url('/artikel/' . $post->slug) }}</link>
<guid isPermaLink="true">{{ url('/artikel/' . $post->slug) }}</guid>
<description><![CDATA[{{ $post->excerpt ?? substr(strip_tags($post->content), 0, 200) }}]]></description>
<pubDate>{{ optional($post->published_at)->toRfc2822String() }}</pubDate>
<author>{{ $post->author?->email ?? 'redaksi@example.com' }} ({{ $post->author?->name ?? 'Redaksi' }})</author>
@if($post->category)<category>{{ $post->category->name }}</category>@endif
</item>
@endforeach
</channel>
</rss>
