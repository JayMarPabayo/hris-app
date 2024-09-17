<?php

namespace Database\Seeders;

use App\Models\Children;
use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Education;
use App\Models\Eligibilities;
use App\Models\Evaluation;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\WorkExperience;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create(['name' => 'Kentjohn A. Branzuela', 'username' => 'admin', 'role' => 'Administrator']);
        Department::factory(6)->create();
        Employee::factory(10)->create();
        Education::factory(12)->create();
        Children::factory(10)->create();
        Eligibilities::factory(10)->create();
        WorkExperience::factory(10)->create();


        foreach (Shift::$shiftnames as $shiftname) {
            Shift::factory()->create([
                'name' => $shiftname
            ]);
        }

        // Create unique schedules for each employee
        $employees = Employee::take(10)->get();

        foreach ($employees as $employee) {

            User::factory()->create([
                'name' => "$employee->firstname $employee->lastname",
                'username' => strtolower("$employee->firstname$employee->lastname"),
                'role' => 'Employee',
                'password' => Hash::make('password'),
                'employee_id' => $employee->id,
            ]);


            Schedule::factory()->create([
                'employee_id' => $employee->id,
            ]);

            Evaluation::factory(20)->create([
                'employee_id' => $employee->id,
            ]);
        }
    }
}
