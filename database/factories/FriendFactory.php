<?php

namespace Database\Factories;

use App\Enums\FriendType;
use App\Models\Friend;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Friend>
 */
class FriendFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(FriendType::cases())
        ];
    }
}
