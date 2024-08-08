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


        $startTime = \Carbon\Carbon::createFromFormat('H:i:s', $this->faker->time())
            ->minute($this->faker->randomElement([0, 30]))
            ->second(0)
            ->format('H:i:s');

        return [
            'name' => $uniqueShiftName,
            'start_time' => $startTime,
            'end_time' => \Carbon\Carbon::createFromFormat('H:i:s', $startTime)
                ->addHours(8)
                ->minute((\Carbon\Carbon::parse($startTime)->minute >= 30) ? 30 : 0)
                ->second(0)
                ->format('H:i:s'),
            'weekdays' => $this->faker->randomElements(Shift::$weekdays, $this->faker->numberBetween(3, 5)),
        ];
    }
}
