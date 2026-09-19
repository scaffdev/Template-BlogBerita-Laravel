<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');
        $posts = Post::where('status', 'published')->pluck('id')->all();
        if (empty($posts)) {
            return;
        }
        foreach ($posts as $postId) {
            foreach (range(1, rand(1, 3)) as $i) {
                Comment::create([
                    'post_id' => $postId,
                    'author_name' => $faker->name,
                    'author_email' => $faker->email,
                    'content' => $faker->sentence(rand(6, 20)),
                    'status' => $faker->randomElement(['approved', 'approved', 'pending']),
                    'ip_address' => '127.0.0.1',
                ]);
            }
        }
    }
}
