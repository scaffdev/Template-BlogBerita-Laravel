<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Mail\ReviewNotificationMail;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Post;
use App\Models\Redirect;
use App\Models\Tag;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PostController extends Controller
{
    private function ensureOwnerOrPrivileged(Post $post): void
    {
        $user = auth()->user();
        if (in_array($user->role, ['editor', 'admin'], true)) {
            return;
        }
        if ($post->user_id !== $user->id) {
            abort(403, 'Anda hanya boleh mengelola artikel milik sendiri.');
        }
    }

    private function canPublish(): bool
    {
        return in_array(auth()->user()->role, ['editor', 'admin'], true);
    }

    public function index(Request $request)
    {
        $q = Post::with(['author', 'category', 'tags'])->latest();
        if (auth()->user()->role === 'author') {
            $q->where('user_id', auth()->id());
        }
        if ($request->filled('status')) {
            $q->where('status', $request->string('status'));
        }
        if ($request->filled('category_id')) {
            $q->where('category_id', $request->integer('category_id'));
        }
        if ($request->filled('q')) {
            $term = '%'.$request->string('q').'%';
            $q->where(fn ($w) => $w->where('title', 'like', $term)->orWhere('slug', 'like', $term));
        }
        $posts = $q->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    public function create()
    {
        return view('admin.posts.form', [
            'post' => new Post(['status' => 'draft', 'allow_comments' => true]),
            'categories' => Category::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }

    public function store(StorePostRequest $request, ImageService $images)
    {
        $data = $request->validated();
        $data = $this->normalizeExtra($request, $data);
        $data['slug'] ??= Str::slug($data['title']);
        $data['slug'] = Str::slug($data['slug']);

        if (! $this->canPublish() && in_array($data['status'], ['published', 'archived'], true)) {
            $data['status'] = 'pending'; // author tidak boleh langsung terbit
        }
        if (($data['status'] ?? '') === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $images->upload($request->file('featured_image'), 'posts');
        }
        $data['user_id'] = auth()->id();
        $data['reading_time'] = $this->readingTime($data['content'] ?? '');

        $tagIds = $this->resolveTagIds($request, $data);
        unset($data['tags'], $data['tags_ids']);

        $post = Post::create($data);
        if (! empty($tagIds)) {
            $post->tags()->sync($tagIds);
        }
        $this->log("membuat artikel #{$post->id}", $post);

        if ($post->status === 'pending') {
            $this->notifyEditors($post);
        }

        return redirect()->route('admin.posts.edit', $post)->with('success', 'Artikel berhasil disimpan.');
    }

    public function edit(Post $post)
    {
        $this->ensureOwnerOrPrivileged($post);

        return view('admin.posts.form', [
            'post' => $post->load(['tags', 'liveUpdates']),
            'categories' => Category::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }

    public function update(StorePostRequest $request, Post $post, ImageService $images)
    {
        $this->ensureOwnerOrPrivileged($post);
        $oldSlug = $post->slug;
        $data = $request->validated();
        $data = $this->normalizeExtra($request, $data);
        $data['slug'] = Str::slug($data['slug'] ?: $data['title']);

        if (! $this->canPublish() && in_array($data['status'], ['published', 'archived'], true)) {
            // author yang mengedit artikel published orang? sudah diblokir di atas.
            // author mengedit miliknya jadi pending agar direview ulang bila sebelumnya published
            $data['status'] = $post->status === 'published' ? 'published' : 'pending';
        }
        if (($data['status'] ?? '') === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }
        if ($request->hasFile('featured_image')) {
            $images->delete($post->featured_image);
            $data['featured_image'] = $images->upload($request->file('featured_image'), 'posts');
        } else {
            unset($data['featured_image']);
        }
        $data['reading_time'] = $this->readingTime($data['content'] ?? $post->content);

        $tagIds = $this->resolveTagIds($request, $data);
        unset($data['tags'], $data['tags_ids']);

        $post->update($data);
        if ($tagIds !== null) {
            $post->tags()->sync($tagIds);
        }

        // Fallback redirect bila slug berubah (Observer utama, ini cadangan)
        if ($oldSlug !== $post->slug) {
            Redirect::firstOrCreate(
                ['from_slug' => ltrim($oldSlug, '/')],
                ['to_slug' => ltrim($post->slug, '/'), 'status_code' => 301]
            );
        }
        $this->log("mengubah artikel #{$post->id}", $post);

        return back()->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Post $post)
    {
        $this->ensureOwnerOrPrivileged($post);
        if (auth()->user()->role === 'author' && $post->status === 'published') {
            abort(403, 'Penulis tidak boleh menghapus artikel yang sudah terbit.');
        }
        $post->delete();
        $this->log("menghapus (soft) artikel #{$post->id}", $post);

        return redirect()->route('admin.posts.index')->with('success', 'Artikel dipindah ke sampah.');
    }

    public function restore(int $id)
    {
        $post = Post::withTrashed()->findOrFail($id);
        $this->ensureOwnerOrPrivileged($post);
        $post->restore();
        $this->log("memulihkan artikel #{$post->id}", $post);

        return back()->with('success', 'Artikel dipulihkan.');
    }

    public function forceDelete(int $id)
    {
        abort_unless(auth()->user()->role === 'admin', 403);
        $post = Post::withTrashed()->findOrFail($id);
        app(ImageService::class)->delete($post->featured_image);
        $post->forceDelete();
        $this->log("menghapus permanen artikel #{$id}", null);

        return redirect()->route('admin.posts.index')->with('success', 'Artikel dihapus permanen.');
    }

    public function publish(Post $post)
    {
        abort_unless($this->canPublish(), 403);
        $post->update(['status' => 'published', 'published_at' => $post->published_at ?? now()]);
        $this->log("menerbitkan artikel #{$post->id}", $post);

        return back()->with('success', 'Artikel diterbitkan.');
    }

    public function schedule(Request $request, Post $post)
    {
        abort_unless($this->canPublish(), 403);
        $data = $request->validate(['published_at' => ['required', 'date', 'after:now']]);
        $post->update(['status' => 'published', 'published_at' => $data['published_at']]);
        $this->log("menjadwalkan artikel #{$post->id}", $post);

        return back()->with('success', 'Artikel dijadwalkan terbit.');
    }

    public function autosave(Request $request)
    {
        $data = $request->validate([
            'id' => ['nullable', 'integer'],
            'title' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);
        $post = null;
        if (! empty($data['id'])) {
            $post = Post::where('id', $data['id'])->where('user_id', auth()->id())->first();
        }
        if (! $post) {
            $post = new Post(['user_id' => auth()->id(), 'status' => 'draft']);
        } else {
            $this->ensureOwnerOrPrivileged($post);
        }
        $post->fill([
            'title' => $data['title'] ?: ($post->title ?? 'Tanpa judul (autosave)'),
            'slug' => $post->slug ?? Str::slug(($data['title'] ?: 'draft').'-'.Str::random(6)),
            'content' => $data['content'] ?? $post->content ?? '',
            'excerpt' => $data['excerpt'] ?? $post->excerpt,
            'category_id' => $data['category_id'] ?? $post->category_id,
            'status' => 'draft',
        ]);
        $post->save();

        return response()->json(['id' => $post->id, 'saved_at' => $post->updated_at]);
    }

    public function preview(Post $post)
    {
        $this->ensureOwnerOrPrivileged($post);

        return redirect()->route('posts.show', $post->slug)->with('preview', true);
    }

    private function resolveTagIds(Request $request, array $data): ?array
    {
        if ($request->filled('tags_ids')) {
            return $request->input('tags_ids');
        }
        if (! empty($data['tags']) && is_string($data['tags'])) {
            $names = collect(explode(',', $data['tags']))->map(fn ($t) => trim($t))->filter()->unique()->take(10);
            $ids = [];
            foreach ($names as $name) {
                $tag = Tag::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name]);
                $ids[] = $tag->id;
            }

            return $ids;
        }

        return $request->has('tags') || $request->has('tags_ids') ? [] : null;
    }

    private function readingTime(string $html): int
    {
        $words = str_word_count(strip_tags($html));

        return max(1, (int) ceil($words / 200));
    }

    private function normalizeExtra(Request $request, array $data): array
    {
        foreach (['is_headline', 'is_featured', 'allow_comments', 'is_sponsored', 'is_liveblog'] as $b) {
            $data[$b] = $request->boolean($b, $b === 'allow_comments');
            if ($b === 'allow_comments' && ! $request->has($b)) {
                $data[$b] = false;
            }
        }
        if (isset($data['co_authors']) && is_string($data['cons_authors'] ?? null)) {
            unset($data['cons_authors']);
        }
        if (isset($data['co_authors']) && is_string($data['co_authors'])) {
            $arr = collect(explode(',', $data['co_authors']))->map(fn ($s) => trim($s))->filter()->take(3)->values()->all();
            $data['co_authors'] = $arr ?: null;
        }
        if (empty($data['series_name'])) {
            $data['series_name'] = null;
            $data['series_order'] = null;
        }
        if (isset($data['summary_points']) && is_string($data['summary_points'])) {
            $data['summary_points'] = collect(preg_split('/\r\n|\r|\n/', $data['summary_points']))
                ->map(fn ($s) => trim($s))
                ->filter()
                ->take(4)
                ->values()
                ->all() ?: null;
        }
        if (empty($data['featured_image_credit'])) {
            $data['featured_image_credit'] = null;
        }
        if (empty($data['is_sponsored'])) {
            $data['sponsored_label'] = null;
        } elseif (empty($data['sponsored_label'])) {
            $data['sponsored_label'] = 'Konten Bersponsor';
        }

        return $data;
    }

    private function log(string $desc, ?Post $post): void
    {
        try {
            ActivityLog::create([
                'causer_id' => auth()->id(),
                'description' => auth()->user()->name.' '.$desc,
                'subject_type' => $post ? Post::class : null,
                'subject_id' => $post?->id,
                'properties' => $post ? ['id' => $post->id, 'title' => $post->title] : ['info' => $desc],
            ]);
        } catch (\Throwable $e) {
        }
    }

    private function notifyEditors(Post $post): void
    {
        try {
            $editors = \App\Models\User::whereIn('role', ['editor', 'admin'])->pluck('email');
            foreach ($editors as $email) {
                Mail::to($email)->queue(new ReviewNotificationMail($post));
            }
        } catch (\Throwable $e) {
        }
    }
}
