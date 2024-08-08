<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $departmentId = Department::inRandomOrder()->first()->id;

        $fatherLastName = $this->faker->lastName;
        $motherLastName = $this->faker->lastName;

        $gender = $this->faker->randomElement(Employee::$gender);

        return [
            'firstname' => $gender === 'Male' ? $this->faker->firstNameMale : $this->faker->firstNameFemale,
            'middlename' => $motherLastName,
            'lastname' => $fatherLastName,
            'nameextension' => $this->faker->boolean(2) ? $this->faker->randomElement(Employee::$suffixes) : null,
            'designation' => $this->faker->jobTitle,
            'department_id' => $departmentId,
            'birthdate' => $this->faker->dateTimeBetween('-74 years', '-14 years')->format('Y-m-d'),
            'birthplace' => $this->faker->city,
            'gender' => $gender,
            'citizenship' => 'Filipino',
            'civilstatus' => $this->faker->randomElement(Employee::$civilstatus),
            'residential_houseblock' => $this->faker->buildingNumber,
            'residential_street' => $this->faker->streetName,
            'residential_subdivision' => $this->faker->secondaryAddress,
            'residential_barangay' => $this->faker->streetSuffix,
            'residential_city' => $this->faker->city,
            'residential_province' => $this->faker->state,
            'residential_region' => $this->faker->stateAbbr,
            'residential_zipcode' => $this->faker->postcode,
            'permanent_houseblock' => $this->faker->buildingNumber,
            'permanent_street' => $this->faker->streetName,
            'permanent_subdivision' => $this->faker->secondaryAddress,
            'permanent_barangay' => $this->faker->streetSuffix,
            'permanent_city' => $this->faker->city,
            'permanent_province' => $this->faker->state,
            'permanent_region' => $this->faker->stateAbbr,
            'permanent_zipcode' => $this->faker->postcode,
            'height' => $this->faker->randomFloat(1, 1.5, 2), // height in meters
            'weight' => $this->faker->randomFloat(1, 50, 100), // weight in kg
            'bloodtype' => $this->faker->randomElement(Employee::$bloodtype),
            'gsis' => $this->faker->unique()->numerify('##########'),
            'pagibig' => $this->faker->unique()->numerify('##########'),
            'philhealth' => $this->faker->unique()->numerify('##########'),
            'sss' => $this->faker->unique()->numerify('##########'),
            'tin' => $this->faker->unique()->numerify('##########'),
            'agencynumber' => $this->faker->unique()->numerify('##########'),
            'telephone' => $this->faker->phoneNumber,
            'mobile' => $this->faker->phoneNumber,
            'email' => $this->faker->boolean ? $this->faker->unique()->safeEmail : null,
            'spouse_firstname' => $gender === 'Female' ? $this->faker->firstNameMale : $this->faker->firstNameFemale,
            'spouse_middlename' => $this->faker->lastName,
            'spouse_lastname' => $this->faker->lastName,
            'spouse_nameextension' => $this->faker->boolean(2) ? $this->faker->randomElement(Employee::$suffixes) : null,
            'spouse_occupation' => $this->faker->jobTitle,
            'spouse_employerbusiness' => $this->faker->company,
            'spouse_businessaddress' => $this->faker->address,
            'spouse_telephone' => $this->faker->phoneNumber,
            'father_firstname' => $this->faker->firstNameMale,
            'father_middlename' => $this->faker->lastName,
            'father_lastname' => $fatherLastName,
            'father_nameextension' => $this->faker->boolean(2) ? $this->faker->randomElement(Employee::$suffixes) : null,
            'mother_firstname' => $this->faker->firstNameFemale,
            'mother_middlename' => $gender === 'Female' ? $fatherLastName : $motherLastName,
            'mother_lastname' => $fatherLastName,
            'mother_nameextension' => $this->faker->boolean(1) ? $this->faker->randomElement(Employee::$suffixes) : null,
        ];
    }
}
