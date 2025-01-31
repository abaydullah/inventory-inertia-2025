<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_name' => fake()->company(),
            'proprietor_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'photo' => fake()->imageUrl(),
            'mobile' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'due' => rand(0, 500),
            'user_id' => 1,
            'tenant_id' => 1
        ];
    }
}
