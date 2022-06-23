<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    #[ArrayShape(['first_name' => "string", 'last_name' => "string", 'email' => "string", 'created_at' => "\DateTime"])] public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'created_at' => $this->faker->dateTime,
        ];
    }
}
