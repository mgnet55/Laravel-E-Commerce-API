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
            'status'=>$this->faker->randomElement(['Processing', 'On way', 'Done']),
            'shipping_company_id'=>1,
            'customer_id'=>$this->faker->randomElement([1,2,3,4,5]),
            'notes'=>$this->faker->text,
            'payment_ref'=>'diffghifo45',
        ];
    }
}
