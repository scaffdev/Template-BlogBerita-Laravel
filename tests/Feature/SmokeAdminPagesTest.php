<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmokeAdminPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_pages_render(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $routes = [
            'admin.dashboard', 'admin.posts.index', 'admin.posts.create',
            'admin.categories.index', 'admin.tags.index', 'admin.media.index',
            'admin.comments.index', 'admin.users.index', 'admin.pages.index',
            'admin.subscribers.index', 'admin.ads.index', 'admin.analytics.index',
            'admin.settings.edit', 'admin.activity.index', 'admin.redirects.index',
        ];

        foreach ($routes as $route) {
            $this->actingAs($admin)->get(route($route))->assertOk();
        }
    }

    public function test_frontend_pages_render(): void
    {
        $author = User::factory()->create(['role' => 'author', 'username' => 'jurnalis1']);
        $category = Category::factory()->create(['slug' => 'politik']);
        $tag = Tag::factory()->create(['slug' => 'pemilu']);
        $post = Post::factory()->create([
            'user_id' => $author->id,
            'category_id' => $category->id,
            'status' => 'published',
            'published_at' => now(),
            'summary_points' => ['Poin pertama', 'Poin kedua'],
        ]);
        $post->tags()->attach($tag->id);

        $this->get('/')->assertOk();
        $this->get('/artikel')->assertOk();
        $this->get('/kategori/politik')->assertOk();
        $this->get('/tag/pemilu')->assertOk();
        $this->get('/penulis/jurnalis1')->assertOk();
        $this->get('/cari?q=pemilu')->assertOk();
        $this->get('/arsip/'.now()->format('Y').'/'.now()->month)->assertOk();
        $this->get('/artikel/'.$post->slug)->assertOk()->assertSee('Yang perlu diketahui')->assertSee('Poin pertama');
        $this->get('/login')->assertOk();
        $this->get('/admin/login')->assertOk();
        $this->get('/register')->assertOk();
        $this->get('/forgot-password')->assertOk();
        $this->get('/reset-password/dummy-token')->assertOk();
        $this->actingAs($author)->get('/disimpan')->assertOk();
        $this->actingAs($author)->get('/profil')->assertOk();
    }
}
