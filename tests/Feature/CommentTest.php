<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_bisa_komentar_jadi_pending(): void
    {
        $post = Post::factory()->create(['status' => 'published', 'published_at' => now()->subHour(), 'allow_comments' => true]);
        $res = $this->post(route('comments.store', $post->slug), [
            'author_name' => 'Budi',
            'author_email' => 'budi@example.com',
            'content' => 'Artikel yang sangat bermanfaat, terima kasih!',
            'website' => '', // honeypot kosong = manusia
        ]);
        $res->assertRedirect();
        $this->assertDatabaseHas('comments', ['post_id' => $post->id, 'status' => 'pending']);
    }

    public function test_honeypot_spam_ditolak(): void
    {
        $post = Post::factory()->create(['status' => 'published', 'published_at' => now()->subHour(), 'allow_comments' => true]);
        $res = $this->post(route('comments.store', $post->slug), [
            'author_name' => 'Spammer',
            'author_email' => 'spam@example.com',
            'content' => 'Beli obat murah klik di sini!',
            'website' => 'http://spam.example.com', // honeypot terisi = bot
        ]);
        $res->assertRedirect();
        $this->assertDatabaseMissing('comments', ['author_email' => 'spam@example.com']);
    }
}
