<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShippingCompany>
 */
class ShippingCompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'=>$this->faker->company(),
            'shipping_manager_id'=>20,
            'phone'=>$this->faker->phoneNumber(),
            'city_id'=>$this->faker->randomElement([1,2,3,4]),
            'address_street'=>$this->faker->streetAddress(),
        ];
    }
}
