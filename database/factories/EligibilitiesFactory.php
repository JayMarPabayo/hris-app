<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Eligibilities>
 */
class EligibilitiesFactory extends Factory
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

        $hasLicense = $this->faker->boolean(30);

        return [
            'examination' => $this->faker->words(3, true),
            'rating' => $this->faker->randomFloat(1, 85, 99),
            'examdate' => $this->faker->date(),
            'address' => $this->faker->address(),
            'license' => $hasLicense ? $this->faker->unique()->bothify('????-#####') : null,
            'validity' => $hasLicense ? $this->faker->numberBetween(2030, 2050) : null,
            'employee_id' => $employeeId,
        ];
    }
}
