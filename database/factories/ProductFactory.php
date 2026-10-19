<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'sku' => strtoupper(fake()->unique()->bothify('SKU-####??')),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'allergens' => null,
            'image_path' => null,
            'image_alt' => null,
            'product_options' => [],
            'base_price' => 12.00,
            'weight' => 0.50,
            'pre_order' => false,
            'pre_order_expectation' => null,
            'seasonal' => false,
            'season_start' => null,
            'season_end' => null,
            'inventory' => 10,
            'active' => true,
            'pickup_only' => false,
        ];
    }
}