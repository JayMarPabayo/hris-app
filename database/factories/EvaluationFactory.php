<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Evaluation>
 */
class EvaluationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $employeeId = Employee::inRandomOrder()->first()->id;

        // Get the current week number
        $currentWeek = date('W');

        // Calculate the last week number
        $lastWeek = $currentWeek - 1;

        // Format the week as "2024-W37"
        $formattedWeek = sprintf("%d-W%02d", date('Y'), $lastWeek);
        return [
            'rating' => $this->faker->randomElement([
                5.00,
                5.50,
                6.00,
                6.50,
                7.00,
                7.50,
                8.00,
                8.50,
                9.00,
                9.50,
                10.00
            ]),
            'week' => $formattedWeek,
            'review' => $this->faker->paragraphs(3, true),
            'employee_id' => $employeeId,
        ];
    }
}
