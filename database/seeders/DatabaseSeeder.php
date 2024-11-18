<?php

namespace Database\Seeders;

use App\Models\Children;
use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Education;
use App\Models\Eligibilities;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\SystemConfig;
use App\Models\WorkExperience;
use App\Models\Question;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create(['name' => 'Kentjohn A. Branzuela', 'email' => 'kentjohnbranzuela13@gmail.com', 'username' => 'admin', 'role' => 'Administrator']);
        Department::factory(6)->create();
        SystemConfig::factory()->create([
            'maxCredits' => 5,
            'evaluation' => false,
        ]);


        foreach (Shift::$shiftnames as $shiftname) {
            Shift::factory()->create([
                'name' => $shiftname
            ]);
        }

        // $employees = Employee::factory(5)->create();

        // $employees->each(function ($employee, $index) {
        //     $imageNumber = $index + 1;
        //     $employee->update([
        //         'picture' => "ids/{$imageNumber}.jpg",
        //     ]);
        // });


        // Education::factory(12)->create();
        // Children::factory(10)->create();
        // Eligibilities::factory(10)->create();
        // WorkExperience::factory(10)->create();

        // $employees = Employee::take(5)->get();

        // foreach ($employees as $employee) {

        //     User::factory()->create([
        //         'name' => "$employee->firstname $employee->lastname",
        //         'username' => strtolower("$employee->firstname$employee->lastname"),
        //         'email' => 'jaymarpabayo@gmail.com',
        //         'role' => 'Employee',
        //         'password' => Hash::make('password'),
        //         'employee_id' => $employee->id,
        //     ]);


        //     // Schedule::factory()->create([
        //     //     'employee_id' => $employee->id,
        //     // ]);
        // }

        $questions = [
            [
                'number' => 1,
                'question' => 'Does the employee produce high-quality and good work?',
                'tagalog' => 'Nakakagawa ba ang empleyado ng mataas na kalidad at tumpak na trabaho?',
            ],
            [
                'number' => 2,
                'question' => 'Does the employee collaborate well with members?',
                'tagalog' => 'Nakikipagtulungan ba nang maayos ang empleyado sa mga kasamahan?',
            ],
            [
                'number' => 3,
                'question' => 'Is the employee dependable, and reliable?',
                'tagalog' => 'Maaasahan, maagap, at mapagkakatiwalaan ba ang empleyado?',
            ],
            [
                'number' => 4,
                'question' => 'Can the employee adapt to changes and challenges?',
                'tagalog' => 'Kayang mag-adjust ba ang empleyado sa mga pagbabago at hamon?',
            ],
            [
                'number' => 5,
                'question' => 'Does the employee communicate effectively and professionally?',
                'tagalog' => 'Epektibo at propesyonal ba ang pakikipagkomunika ng empleyado?',
            ],
            [
                'number' => 6,
                'question' => 'Is the employee open to feedback and self-improvement?',
                'tagalog' => 'Bukas ba ang empleyado sa puna at sa pagpapabuti ng sarili?',
            ],
        ];

        foreach ($questions as $question) {
            Question::create($question);
        }
    }
}
