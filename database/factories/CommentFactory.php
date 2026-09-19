<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    public function definition(): array
    {
        $faker = \Faker\Factory::create('id_ID');

        return [
            'post_id' => Post::factory(),
            'user_id' => User::factory(),
            'parent_id' => null,
            'author_name' => $faker->name(),
            'author_email' => $faker->safeEmail(),
            'content' => $faker->paragraph(2),
            'status' => $faker->randomElement(['pending', 'approved', 'approved']),
            'ip_address' => $faker->ipv4(),
        ];
    }
}
