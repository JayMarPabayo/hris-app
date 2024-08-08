<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory

{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    private static array $usedDepartments = [];


    public function definition(): array
    {

        $departments = [
            'Human Resources',
            'Finance',
            'IT',
            'Marketing',
            'Sales',
            'Customer Service',
            'R&D',
            'Operations',
            'Legal',
            'Administration'
        ];


        // Get the available departments that haven't been used yet
        $availableDepartments = array_diff($departments, self::$usedDepartments);

        if (empty($availableDepartments)) {
            // Reset if all departments have been used
            self::$usedDepartments = [];
            $availableDepartments = $departments;
        }

        // Select a random department from the available list
        $selectedDepartment = $this->faker->randomElement($availableDepartments);

        // Add the selected department to the used list
        self::$usedDepartments[] = $selectedDepartment;

        return [
            'name' => $selectedDepartment,
        ];
    }
}
