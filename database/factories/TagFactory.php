<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    public function definition(): array
    {
        $faker = \Faker\Factory::create('id_ID');
        $name = ucfirst($faker->unique()->word());

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(4),
        ];
    }
}
