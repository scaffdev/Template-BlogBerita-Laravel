<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function show(string $slug)
    {
        $category = Category::where('slug', $slug)->with(['parent', 'children'])->firstOrFail();

        $categoryIds = [$category->id];
        foreach ($category->children as $child) {
            $categoryIds[] = $child->id;
        }

        $posts = \App\Models\Post::published()
            ->whereIn('category_id', $categoryIds)
            ->recent()
            ->with(['author', 'category'])
            ->paginate(12)
            ->withQueryString();

        return view('frontend.category.show', compact('category', 'posts'));
    }
}
