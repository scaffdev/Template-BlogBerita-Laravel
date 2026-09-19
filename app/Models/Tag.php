<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    protected static function booted(): void
    {
        static::saving(function (Tag $tag) {
            if (empty($tag->slug) && ! empty($tag->name)) {
                $base = Str::slug($tag->name) ?: 'tag';
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->when($tag->id, fn ($q) => $q->where('id', '!=', $tag->id))->exists()) {
                    $slug = $base . '-' . (++$i);
                }
                $tag->slug = $slug;
            }
        });
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_tag');
    }
}
