<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'quantity' => $this->faker->randomElement([1,2,3,4,5]),
            'price' => $this->faker->randomElement([1,2,3,4,5]),
            'image'=>'https://dummyimage.com/800x494/000/fff&text=product+image',
            'user_id'=>$this->faker->randomElement([1,2,3,4,5]),
            'cat_id'=>$this->faker->randomElement([1,2,3,4,5]),
        ];
    }
}
