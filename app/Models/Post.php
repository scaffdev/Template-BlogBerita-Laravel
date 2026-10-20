<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'co_authors',
        'category_id',
        'title',
        'slug',
        'series_name',
        'series_order',
        'excerpt',
        'summary_points',
        'content',
        'featured_image',
        'featured_image_credit',
        'status',
        'published_at',
        'views_count',
        'likes_count',
        'bookmarks_count',
        'reading_time',
        'is_featured',
        'is_headline',
        'is_sponsored',
        'sponsored_label',
        'is_liveblog',
        'allow_comments',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'co_authors' => 'array',
            'summary_points' => 'array',
            'is_featured' => 'boolean',
            'is_headline' => 'boolean',
            'is_sponsored' => 'boolean',
            'is_liveblog' => 'boolean',
            'allow_comments' => 'boolean',
            'views_count' => 'integer',
            'likes_count' => 'integer',
            'bookmarks_count' => 'integer',
            'reading_time' => 'integer',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(PostRevision::class)->orderByDesc('created_at');
    }

    public function liveUpdates(): HasMany
    {
        return $this->hasMany(LiveUpdate::class)->orderByDesc('created_at');
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function seriesPosts(int $limit = 6)
    {
        if (empty($this->series_name)) {
            return collect();
        }

        return static::published()
            ->where('series_name', $this->series_name)
            ->where('id', '!=', $this->id)
            ->orderBy('series_order')
            ->orderBy('published_at')
            ->limit($limit)
            ->with(['author', 'category'])
            ->get();
    }

    public function coAuthorNames(): string
    {
        $extra = $this->co_authors ?? [];

        return collect($extra)->filter()->take(3)->join(', ');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->where('published_at', '<=', now());
    }

    public function scopeHeadline(Builder $query): Builder
    {
        return $query->where('is_headline', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopePopular(Builder $query): Builder
    {
        return $query->orderByDesc('views_count');
    }

    public function scopeRecent(Builder $query): Builder
    {
        return $query->orderByDesc('published_at')->orderByDesc('created_at');
    }

    public function scopeSearch(Builder $query, string $q): Builder
    {
        return $query->searchSmart($q);
    }

    /**
     * Pencarian toleran typo ringan (CARI-5 versi tanpa Meilisearch):
     * pecah query jadi kata, tiap kata boleh cocok di judul/ringkasan/isi/tag.
     */
    public function scopeSearchSmart(Builder $query, string $q): Builder
    {
        $words = collect(preg_split('/\s+/', trim($q)))
            ->filter(fn ($w) => mb_strlen($w) >= 2)
            ->take(6)
            ->values();

        if ($words->isEmpty()) {
            $like = '%'.$q.'%';

            return $query->where(function (Builder $inner) use ($like) {
                $inner->where('title', 'like', $like)
                    ->orWhere('excerpt', 'like', $like)
                    ->orWhere('content', 'like', $like);
            });
        }

        return $query->where(function (Builder $inner) use ($words) {
            foreach ($words as $w) {
                $like = '%'.$w.'%';
                $inner->where(function (Builder $sub) use ($like) {
                    $sub->where('title', 'like', $like)
                        ->orWhere('excerpt', 'like', $like)
                        ->orWhere('content', 'like', $like)
                        ->orWhereHas('tags', fn ($t) => $t->where('name', 'like', $like));
                });
            }
        });
    }

    public function excerptOrGenerate(int $length = 160): string
    {
        if (! empty($this->excerpt)) {
            return $this->excerpt;
        }

        $text = trim(preg_replace('/\s+/', ' ', strip_tags($this->content ?? '')));

        return Str::limit($text, $length);
    }

    public function readingTimeCalculate(): int
    {
        $words = str_word_count(strip_tags($this->content ?? ''));

        return (int) max(1, ceil($words / 200));
    }

    public function related(int $limit = 4)
    {
        $tagIds = $this->tags()->pluck('tags.id');

        return static::published()
            ->where('id', '!=', $this->id)
            ->when($this->category_id, fn ($q) => $q->where('category_id', $this->category_id))
            ->when($tagIds->isNotEmpty(), function ($q) use ($tagIds) {
                $q->orWhereHas('tags', fn ($t) => $t->whereIn('tags.id', $tagIds));
            })
            ->recent()
            ->limit($limit)
            ->get();
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    public function canonicalUrl(): string
    {
        return url('/artikel/'.$this->slug);
    }
}
