<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'name'        => $this->faker->word() . ' ' . $this->faker->randomElement(['Pro', 'Max', 'Lite']),
            'description' => $this->faker->sentence(),
            'price'       => $this->faker->randomFloat(2, 20, 300),
            'stock'       => $this->faker->numberBetween(5, 50),
            'image'       => null,
        ];
    }
}