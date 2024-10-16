<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

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


        return [
            'rating' => $this->faker->randomElement([
                1.00,
                2.00,
                3.00,
                4.00,
                5.00,
            ]),
            'week' => Carbon::now()->startOfWeek(),
            'review' => $this->faker->paragraphs(3, true),
            'employee_id' => $employeeId,
        ];
    }
}
