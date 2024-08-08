<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkExperience>
 */
class WorkExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        // Fetch a random existing employee_id
        $employeeId = Employee::inRandomOrder()->first()->id;

        $appointmentStatuses = ['Permanent', 'Part-Time', 'Substitute', 'Contractual'];

        // Generate a start date up to 2 years ago
        $startDate = $this->faker->dateTimeBetween('-20 years', '-2 years');

        return [
            'position' => $this->faker->jobTitle(),
            'company' => $this->faker->company(),
            'monthlysalary' => $this->generateSalary(),
            'paygrade' => $this->faker->bothify('##-#'),
            'appointmentstatus' => $this->faker->boolean(80) ? 'Regular' : $this->faker->randomElement($appointmentStatuses),
            'govtservice' => $this->faker->boolean(),
            'start' => $startDate,
            'end' => $this->faker->dateTimeBetween($startDate, 'now'),
            'employee_id' => $employeeId,
        ];
    }

    private function generateSalary(): int
    {
        $minSalary = 10000;
        $maxSalary = 100000;
        $increment = 500;

        // 90% probability to be in the range of 10,000 - 25,000
        if ($this->faker->boolean(90)) {
            // Generate salary in the range of 10,000 - 25,000
            $minRange = 10000;
            $maxRange = 25000;
        } else {
            // Generate salary in the range of 25,500 - 100,000
            $minRange = 25500;
            $maxRange = 100000;
        }

        // Calculate the number of increments
        $range = ($maxRange - $minRange) / $increment;
        $randomIncrement = $this->faker->numberBetween(0, $range);

        return $minRange + ($randomIncrement * $increment);
    }
}
