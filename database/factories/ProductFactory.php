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
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'image' => fake()->imageUrl(),
            'description' => fake()->text(100),
            'buy_price' => rand(200, 250),
            'sell_price' => rand(260, 300),
            'barcode' => rand(100000, 200000),
            'warning_stock' => rand(0, 50),
            'category_id' => rand(1, 50),
            'user_id' => 1,
            'group_id' => rand(1, 100),
            'status' => true,
            'tenant_id' => 1
        ];
    }
}
