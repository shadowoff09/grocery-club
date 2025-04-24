<?php

namespace Database\Factories;

use App\Models\Card;
use Illuminate\Database\Eloquent\Factories\Factory;

// TODO

class CardFactory extends Factory
{
    protected $model = Card::class;

    public function definition(): array
    {
        return [
            // O ID será atribuído manualmente quando ligares ao user
            'card_number' => $this->faker->unique()->numberBetween(100000, 999999),
            'balance' => $this->faker->randomFloat(2, 0, 500),
        ];
    }
}
