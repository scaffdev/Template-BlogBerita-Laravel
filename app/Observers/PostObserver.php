<?php

namespace App\Observers;

use App\Models\Post;
use App\Models\PostRevision;
use App\Models\Redirect;
use Illuminate\Support\Str;

class PostObserver
{
    public function creating(Post $post): void
    {
        if (empty($post->slug) && ! empty($post->title)) {
            $post->slug = $this->uniqueSlug($post->title);
        }

        $post->reading_time = $this->calcReadingTime($post->content ?? '');

        if (empty($post->excerpt) && ! empty($post->content)) {
            $post->excerpt = $this->makeExcerpt($post->content);
        }
    }

    public function updating(Post $post): void
    {
        $originalSlug = $post->getOriginal('slug');
        $originalTitle = $post->getOriginal('title');
        $originalContent = $post->getOriginal('content');

        // Recalculate slug uniqueness if title changed and slug empty (safety)
        if (empty($post->slug) && ! empty($post->title)) {
            $post->slug = $this->uniqueSlug($post->title, $post->id);
        }

        // Recalculate reading time if content changed
        if ($post->isDirty('content')) {
            $post->reading_time = $this->calcReadingTime($post->content ?? '');
        }

        // If slug changed & post was published -> create redirect 301
        if ($post->isDirty('slug') && $originalSlug && $originalSlug !== $post->slug) {
            $wasPublished = ($post->getOriginal('status') === 'published');
            if ($wasPublished) {
                Redirect::updateOrCreate(
                    ['from_slug' => $originalSlug],
                    ['to_slug' => $post->slug, 'status_code' => 301]
                );
            }
        }

        // If title/content changed & published -> create revision snapshot of OLD version
        $isPublished = ($post->status === 'published' || $post->getOriginal('status') === 'published');
        if ($isPublished && ($post->isDirty('title') || $post->isDirty('content'))) {
            PostRevision::create([
                'post_id' => $post->id,
                'user_id' => $post->user_id,
                'title' => $originalTitle ?? $post->title,
                'content' => $originalContent ?? $post->content,
            ]);
        }

        // Auto excerpt if emptied
        if ($post->isDirty('content') && empty($post->excerpt) && ! empty($post->content)) {
            $post->excerpt = $this->makeExcerpt($post->content);
        }
    }

    protected function uniqueSlug(string $title, $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'post';
        $slug = $base;
        $i = 1;
        while (Post::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . (++$i);
        }

        return $slug;
    }

    protected function calcReadingTime(string $content): int
    {
        $words = str_word_count(strip_tags($content));

        return (int) max(1, ceil($words / 200));
    }

    protected function makeExcerpt(string $content, int $length = 160): string
    {
        $text = trim(preg_replace('/\s+/', ' ', strip_tags($content)));

        return Str::limit($text, $length);
    }
}
