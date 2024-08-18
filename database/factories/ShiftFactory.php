<?php

namespace Database\Factories;

use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shift>
 */
class ShiftFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create();

        $shiftNames = Shift::$shiftnames;
        $uniqueShiftName = $faker->unique()->randomElement($shiftNames);

        $startTime = \Carbon\Carbon::createFromFormat('H:i:s', $faker->time())
            ->minute($faker->randomElement([0, 30]))
            ->second(0)
            ->format('H:i:s');

        return [
            'name' => $uniqueShiftName,
            'start_time' => $startTime,
            'end_time' => \Carbon\Carbon::createFromFormat('H:i:s', $startTime)
                ->addHours(8)
                ->minute(($faker->randomElement([0, 30]) >= 30) ? 30 : 0) // Changed this line for consistency
                ->second(0)
                ->format('H:i:s'),
            'weekdays' => $faker->randomElements(Shift::$weekdays, $faker->numberBetween(3, 5)),
        ];
    }
}
