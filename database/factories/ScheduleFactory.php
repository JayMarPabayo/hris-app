<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Fetch a random
        $employeeId = Employee::inRandomOrder()->first()->id;
        $shiftId = Shift::inRandomOrder()->first()->id;

        $week = '2025-W2';

        return [
            'employee_id' => $employeeId,
            'shift_id' => $shiftId,
            'week' => $week,
            'dayoffs' => [],
        ];
    }
}
