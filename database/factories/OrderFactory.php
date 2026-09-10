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
    public function definition(): array
    {
        return [
        'customer_name'  => $this->faker->name(),
        'customer_email' => $this->faker->safeEmail(),
        'customer_phone' => $this->faker->phoneNumber(),
        'address'        => $this->faker->address(),
        'total_price'    => $this->faker->randomFloat(2, 50, 1000),
        'status'         => $this->faker->randomElement(['pending', 'processing', 'completed', 'cancelled']),
        ];
    }
}
