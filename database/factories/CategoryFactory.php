<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $faker = \Faker\Factory::create('id_ID');
        $name = ucfirst($faker->unique()->words(mt_rand(1, 2), true));

        return [
            'parent_id' => null,
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(4),
            'description' => $faker->sentence(),
            'image' => null,
            'sort_order' => $faker->numberBetween(0, 100),
        ];
    }
}
