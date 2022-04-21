<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        \App\Models\User::factory(10)->create();
        \App\Models\Seller::factory(10)->create();
        \App\Models\Category::factory(10)->create();
        \App\Models\Governorate::factory(5)->create();
        \App\Models\City::factory(50)->create();
        \App\Models\Product::factory(100)->create();
        \App\Models\ShippingCompany::factory(1)->create();
        \App\Models\Order::factory(10)->create();
        \App\Models\OrderItems::factory(50)->create();
    }
}
