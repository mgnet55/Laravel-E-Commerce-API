<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory
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
            'name' => $this->faker->name(),
            'description' => $this->faker->text,
            'quantity' => $this->faker->randomNumber(2),
            'price' => $this->faker->randomElement([1,2,3,4,5]),
            'image'=>'product_'.$this->faker->randomElement([1,2,3,4,5]).'.jpg',
            'seller_id'=>$this->faker->randomElement([11,12,13,14,15]),
            'category_id'=>$this->faker->randomElement([1,2,3,4,5]),
            'available'=>$this->faker->randomElement([false,true]),
            'discount'=>$this->faker->randomDigit()/10,
        ];
    }
}
