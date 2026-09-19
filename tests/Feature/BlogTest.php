<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    private function publishedPost(array $over = []): Post
    {
        $user = User::factory()->create(['role' => 'author']);
        $cat = Category::factory()->create();

        return Post::factory()->create(array_merge([
            'user_id' => $user->id,
            'category_id' => $cat->id,
            'status' => 'published',
            'published_at' => now()->subHour(),
        ], $over));
    }

    public function test_guest_bisa_buka_home(): void
    {
        $this->publishedPost();
        $this->get('/')->assertOk();
    }

    public function test_artikel_published_200_draft_404(): void
    {
        $pub = $this->publishedPost(['slug' => 'berita-published']);
        $draft = $this->publishedPost(['slug' => 'berita-draft', 'status' => 'draft', 'published_at' => null]);
        $this->get('/artikel/'.$pub->slug)->assertOk();
        $this->get('/artikel/'.$draft->slug)->assertNotFound();
    }

    public function test_search_sitemap_feed(): void
    {
        $this->publishedPost(['title' => 'Kabar Ekonomi Digital Hari Ini']);
        $this->get('/cari?q=ekonomi')->assertOk();
        $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $this->get('/feed')->assertOk();
    }
}
