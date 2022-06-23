<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    #[ArrayShape(['title' => "string", 'description' => "string", 'image' => "string", 'price' => "int"])] public function definition(): array
    {
        return [
            'title' => $this->faker->text(30),
            'description' => $this->faker->text,
            'image' => $this->faker->imageUrl(),
            'price' => $this->faker->numberBetween(10, 100)
        ];
    }
}
