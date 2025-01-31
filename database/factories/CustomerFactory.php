<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
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
            'email' => fake()->unique()->safeEmail(),
            'photo' => fake()->imageUrl(),
            'mobile' => fake()->phoneNumber(),
            'gender' => 'male',
            'address' => fake()->address(),
            'due' => rand(0, 500),
            'status' => true,
            'user_id' => 1,
            'tenant_id' => 1
        ];
    }
}
