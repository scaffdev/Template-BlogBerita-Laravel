<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        $faker = \Faker\Factory::create('id_ID');
        $title = ucfirst(rtrim($faker->sentence(mt_rand(5, 9)), '.'));

        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(5),
            'excerpt' => $faker->sentence(20),
            'content' => '<p>' . implode('</p><p>', $faker->paragraphs(6)) . '</p>',
            'featured_image' => null,
            'status' => $faker->randomElement(['draft', 'pending', 'published', 'published']),
            'published_at' => now()->subDays($faker->numberBetween(0, 60)),
            'views_count' => $faker->numberBetween(0, 5000),
            'reading_time' => $faker->numberBetween(1, 10),
            'is_featured' => $faker->boolean(20),
            'is_headline' => $faker->boolean(10),
            'allow_comments' => true,
            'meta_title' => null,
            'meta_description' => $faker->sentence(15),
        ];
    }
}
