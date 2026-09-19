<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Setting;

class SeoService
{
    public function metaDefault(): array
    {
        return [
            'title' => Setting::get('site_name', config('app.name', 'Blog Berita')),
            'description' => Setting::get('site_description', 'Portal blog dan berita terkini.'),
            'image' => Setting::get('site_logo', asset('images/og-default.jpg')),
            'canonical' => url()->current(),
        ];
    }

    public function metaForPost(Post $post): array
    {
        $title = $post->meta_title ?: $post->title;
        $description = $post->meta_description ?: $post->excerptOrGenerate();
        $image = $post->featured_image
            ? (str_starts_with($post->featured_image, 'http') ? $post->featured_image : asset('storage/' . ltrim($post->featured_image, '/')))
            : $this->metaDefault()['image'];

        return [
            'title' => $title,
            'description' => $description,
            'image' => $image,
            'canonical' => $post->canonicalUrl(),
            'og' => [
                'og:title' => $title,
                'og:description' => $description,
                'og:image' => $image,
                'og:url' => $post->canonicalUrl(),
                'og:type' => 'article',
            ],
            'twitter' => [
                'twitter:card' => 'summary_large_image',
                'twitter:title' => $title,
                'twitter:description' => $description,
                'twitter:image' => $image,
            ],
        ];
    }

    public function jsonLdNewsArticle(Post $post): array
    {
        $meta = $this->metaForPost($post);

        return [
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => $post->title,
            'description' => $meta['description'],
            'image' => [$meta['image']],
            'datePublished' => optional($post->published_at ?? $post->created_at)->toIso8601String(),
            'dateModified' => optional($post->updated_at)->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $post->author?->name ?? 'Redaksi',
                'url' => $post->author?->username ? url('/penulis/' . $post->author->username) : null,
            ],
            'publisher' => $this->jsonLdOrganization(),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $post->canonicalUrl(),
            ],
        ];
    }

    public function jsonLdBreadcrumb(Post $post): array
    {
        $items = [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => url('/')],
        ];

        if ($post->category) {
            $items[] = ['@type' => 'ListItem', 'position' => 2, 'name' => $post->category->name, 'item' => url('/kategori/' . $post->category->slug)];
        }

        $items[] = ['@type' => 'ListItem', 'position' => count($items) + 1, 'name' => $post->title, 'item' => $post->canonicalUrl()];

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }

    public function jsonLdOrganization(): array
    {
        return [
            '@type' => 'Organization',
            'name' => Setting::get('site_name', config('app.name', 'Blog Berita')),
            'url' => url('/'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => Setting::get('site_logo', asset('images/logo.png')),
            ],
        ];
    }
}
