<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->slug(3),
            'image' => $this->faker->imageUrl(640, 480, 'animals', true),
            'description'=>$this->faker->sentence(),
        ];
    }
}
