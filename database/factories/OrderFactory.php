<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'city_id'=>1,
            'street'=>$this->faker->sentence,
            'status'=>$this->faker->randomElement(['Processing','On the Way','Done']),
            'total_price'=>$this->faker->randomFloat(2),
            'shipping_id'=>1,
            'user_id'=>1,
            'notes'=>$this->faker->text
        ];
    }
}
