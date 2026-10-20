<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $postId = $this->route('post')?->id ?? $this->route('post') ?? $this->route('id');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('posts', 'slug')->ignore($postId)],
            'category_id' => ['nullable', 'exists:categories,id'],
            'content' => ['required', 'string'],
            'excerpt' => ['nullable', 'string'],
            'summary_points' => ['nullable', 'string', 'max:1000'],
            'featured_image_credit' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:draft,pending,published,archived'],
            'published_at' => ['nullable', 'date'],
            'tags' => ['nullable'],
            'tags_ids' => ['nullable', 'array'],
            'tags_ids.*' => ['exists:tags,id'],
            'series_name' => ['nullable', 'string', 'max:255'],
            'series_order' => ['nullable', 'integer', 'min:1', 'max:999'],
            'co_authors' => ['nullable', 'string', 'max:500'],
            'is_sponsored' => ['nullable', 'boolean'],
            'sponsored_label' => ['nullable', 'string', 'max:255'],
            'is_liveblog' => ['nullable', 'boolean'],
            'featured_image' => ['nullable', 'image', 'max:2048'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'is_featured' => ['nullable', 'boolean'],
            'is_headline' => ['nullable', 'boolean'],
            'allow_comments' => ['nullable', 'boolean'],
        ];
    }
}
