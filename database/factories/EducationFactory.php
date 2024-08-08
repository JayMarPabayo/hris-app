<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Education>
 */
class EducationFactory extends Factory
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

        // Generate start year
        $startYear = $this->faker->numberBetween(
            (int)date('Y') - 30,
            (int)date('Y') - 10
        );

        // Generate end year to be 4-10 years from start year
        $endYear = $this->faker->numberBetween($startYear + 4, $startYear + 10);

        // Decide if graduated should be equal to end year or null
        $graduatedYear = $this->faker->optional()->randomElement([$endYear, null]);

        return [
            'level' => $this->faker->randomElement(['High School', 'Associate', 'Bachelor', 'Master', 'Doctorate']),
            'school' => $this->faker->company . ' University',
            'degree' => $this->faker->randomElement(['Computer Science', 'Business Administration', 'Engineering', 'Arts']),
            'start' => $startYear,
            'end' => $endYear,
            'earned' => $this->faker->optional()->randomElement(['Certificate', 'Diploma', 'Degree']),
            'graduated' => $graduatedYear,
            'accolades' => $this->faker->optional()->sentence(),
            'employee_id' => $employeeId,
        ];
    }
}
