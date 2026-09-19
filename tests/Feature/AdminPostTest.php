<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPostTest extends TestCase
{
    use RefreshDatabase;

    public function test_author_bisa_buat_draft(): void
    {
        $author = User::factory()->create(['role' => 'author']);
        $cat = Category::factory()->create();
        $res = $this->actingAs($author)->post(route('admin.posts.store'), [
            'title' => 'Judul Uji Coba Draft Artikel Panjang Sekali',
            'content' => str_repeat('Isi konten artikel yang cukup panjang untuk lolos validasi. ', 10),
            'category_id' => $cat->id,
            'status' => 'draft',
        ]);
        $res->assertRedirect();
        $this->assertDatabaseHas('posts', ['title' => 'Judul Uji Coba Draft Artikel Panjang Sekali', 'status' => 'draft']);
    }

    public function test_author_tidak_bisa_publish_milik_orang_lain(): void
    {
        $author = User::factory()->create(['role' => 'author']);
        $other = User::factory()->create(['role' => 'author']);
        $post = Post::factory()->create(['user_id' => $other->id, 'status' => 'draft']);
        $this->actingAs($author)->post(route('admin.posts.publish', $post))->assertForbidden();
        $this->actingAs($author)->get(route('admin.posts.edit', $post))->assertForbidden();
    }

    public function test_editor_bisa_publish(): void
    {
        $editor = User::factory()->create(['role' => 'editor']);
        $post = Post::factory()->create(['status' => 'pending']);
        $this->actingAs($editor)->post(route('admin.posts.publish', $post))->assertRedirect();
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'status' => 'published']);
    }
}
