<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');
        $authors = User::whereIn('role', ['author', 'editor', 'admin'])->pluck('id')->all()
            ?: User::pluck('id')->all();
        $categories = Category::pluck('id')->all();
        $tagIds = Tag::pluck('id')->all();

        $makeContent = function () use ($faker) {
            $html = '<p>'.$faker->paragraph(6).'</p>';
            $html .= '<h2>'.$faker->sentence(4).'</h2><p>'.$faker->paragraph(8).'</p>';
            $html .= '<blockquote>'.$faker->sentence(10).'</blockquote>';
            $html .= '<h3>'.$faker->sentence(3).'</h3><ul><li>'.$faker->sentence(6).'</li><li>'.$faker->sentence(6).'</li><li>'.$faker->sentence(6).'</li></ul>';
            $html .= '<p>'.$faker->paragraph(10).'</p>';

            return $html;
        };

        for ($i = 1; $i <= 20; $i++) {
            $title = rtrim($faker->sentence(rand(5, 9)), '.');
            $post = Post::firstOrCreate(['slug' => Str::slug($title).'-'.$i], [
                'user_id' => $faker->randomElement($authors),
                'category_id' => $faker->randomElement($categories),
                'title' => $title,
                'excerpt' => $faker->sentence(20),
                'content' => $makeContent(),
                'featured_image' => 'https://picsum.photos/seed/berita'.$i.'/1200/630',
                'status' => 'published',
                'published_at' => now()->subDays(rand(0, 60))->subHours(rand(0, 23)),
                'views_count' => rand(50, 15000),
                'reading_time' => rand(2, 8),
                'is_headline' => $i <= 3,
                'is_featured' => $i % 4 === 0,
                'allow_comments' => true,
                'meta_title' => Str::limit($title, 65),
                'meta_description' => $faker->sentence(18),
            ]);
            if (! empty($tagIds)) {
                $post->tags()->sync($faker->randomElements($tagIds, min(3, count($tagIds))));
            }
        }

        // 5 draft/pending
        $statuses = ['draft', 'draft', 'pending', 'pending', 'draft'];
        foreach ($statuses as $n => $st) {
            $title = rtrim($faker->sentence(6), '.').' (konsep)';
            Post::firstOrCreate(['slug' => Str::slug($title).'-d'.$n], [
                'user_id' => $faker->randomElement($authors),
                'category_id' => $faker->randomElement($categories),
                'title' => $title,
                'excerpt' => $faker->sentence(15),
                'content' => '<p>'.$faker->paragraph(5).'</p>',
                'status' => $st,
                'published_at' => $st === 'published' ? now() : null,
                'views_count' => 0,
                'reading_time' => 2,
                'allow_comments' => true,
            ]);
        }
    }
}
