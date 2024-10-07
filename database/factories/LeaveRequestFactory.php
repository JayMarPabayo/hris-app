<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LeaveRequest>
 */
class LeaveRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reasons = ['Vacation Leave', 'Sick Leave', 'Leave with Pay', 'Maternity Leave', 'Paternity Leave', 'Others'];

        $userId = User::inRandomOrder()->first()->id;
        return [
            'user_id' => $userId,
            'reason' => $this->faker->randomElement($reasons),
            'custom_reason' => $this->faker->optional()->sentence(),
            'start' => $this->faker->date(),
            'end' => $this->faker->date(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}
