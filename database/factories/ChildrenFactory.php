<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Children>
 */
class ChildrenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        // Fetch a random existing employee who is at least 19 years old
        $employee = Employee::where('birthdate', '<=', now()->subYears(19)->toDateString())
            ->orWhere('spouse_firstname', '!==', null)
            ->inRandomOrder()
            ->first();

        // Calculate the employee's 19th birthday
        $employeeBirthdate = new \DateTime($employee->birthdate);
        $employeeNineteenthBirthday = $employeeBirthdate->modify('+19 years');

        // Ensure child's birthdate is within a reasonable range
        // The child's birthdate should be at least the employee's 19th birthday and up to today
        // $childBirthdate = $this->faker->dateBetween($employeeNineteenthBirthday->format('Y-m-d'), now()->toDateString());
        $childBirthdate = $this->faker->dateTimeBetween($employeeNineteenthBirthday, 'now');

        return [
            'fullname' => $this->faker->firstName . ' ' . ($employee->gender === 'Male' ? $employee->lastname : $employee->spouse_lastname),
            'gender' => $this->faker->randomElement(Employee::$gender),
            'birthdate' => $childBirthdate,
            'employee_id' => $employee->id,
        ];
    }
}
