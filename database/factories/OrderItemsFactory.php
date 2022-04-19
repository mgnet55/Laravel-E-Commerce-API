<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItems>
 */
class OrderItemsFactory extends Factory
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
            'order_id' => $this->faker->randomDigitNotZero(),
            'description' => $this->faker->text,
            'quantity' => $this->faker->randomNumber(2),
            'price' => $this->faker->randomFloat(2,10,999),
            'image'=>'product_'.Str::random(10).'.jpg',
            'product_id'=>$this->faker->randomDigitNotZero(),
            'discount'=>$this->faker->randomDigit()/10,
            'picked'=>$this->faker->boolean(),
            'fulfilled'=>$this->faker->boolean(),
        ];
    }
}
