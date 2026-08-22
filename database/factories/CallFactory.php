<?php

namespace Database\Factories;

use App\Enums\CallRequestStatus;
use App\Enums\CallType;
use App\Models\Call;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Call>
 */
class CallFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $stated_at = fake()->dateTimeBetween('-1 month' , 'now');
        $ended_at = fake()->dateTimeBetween($stated_at , $stated_at->format('Y-m-d H:i:s') . '+1 hour');

        return [
            'status' => fake()->randomElement(CallRequestStatus::cases()),
            'type' => fake()->randomElement(CallType::cases()),
            'started_at' => $stated_at,
            'ended_at' => $ended_at
        ];
    }
}
