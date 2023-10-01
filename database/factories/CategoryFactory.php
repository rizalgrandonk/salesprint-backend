<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->realText(15);
        return [
            'name' => implode(" ", array_map(fn ($val) => Str::ucfirst($val), explode(" ", $name))),
            'slug' => Str::slug($name),
            'image' => 'https://source.unsplash.com/random/?' . $name
        ];
    }
}
