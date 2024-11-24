<?php

namespace App\Http\Requests;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $employeeId = $this->employee ? $this->employee->id : null;

        return [
            'picture' => [
                $this->isMethod('post') ? 'required' : 'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:2048'
            ],
            'firstname' => 'required|string|max:100',
            'middlename' => 'nullable|string|max:100',
            'lastname' => 'required|string|max:100',
            'nameextension' => 'nullable|string|max:10',
            'designation' => 'required|string|max:100',
            'birthdate' => 'required|date',
            'birthplace' => 'required|string|max:255',
            'gender' => 'required|in:' . implode(',', Employee::$gender),
            'citizenship' => 'required|string|max:50',
            'civilstatus' => 'required|in:' . implode(',', Employee::$civilstatus),
            'residential_houseblock' => 'nullable|string|max:50',
            'residential_street' => 'nullable|string|max:100',
            'residential_subdivision' => 'nullable|string|max:100',
            'residential_barangay' => 'nullable|string|max:100',
            'residential_city' => 'nullable|string|max:100',
            'residential_province' => 'nullable|string|max:100',
            'residential_region' => 'nullable|string|max:100',
            'residential_zipcode' => 'nullable|string|max:10',
            'permanent_houseblock' => 'nullable|string|max:50',
            'permanent_street' => 'nullable|string|max:100',
            'permanent_subdivision' => 'nullable|string|max:100',
            'permanent_barangay' => 'nullable|string|max:100',
            'permanent_city' => 'nullable|string|max:100',
            'permanent_province' => 'nullable|string|max:100',
            'permanent_region' => 'nullable|string|max:100',
            'permanent_zipcode' => 'nullable|string|max:10',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'bloodtype' => 'required|in:' . implode(',', Employee::$bloodtype),
            'pagibig' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('employees')->ignore($employeeId),
            ],
            'philhealth' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('employees')->ignore($employeeId),
            ],
            'sss' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('employees')->ignore($employeeId),
            ],
            'tin' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('employees')->ignore($employeeId),
            ],
            'agencynumber' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('employees')->ignore($employeeId),
            ],
            'mobile' => 'required|string|max:20',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
            ],
            'spouse_firstname' => 'nullable|string|max:100',
            'spouse_middlename' => 'nullable|string|max:100',
            'spouse_lastname' => 'nullable|string|max:100',
            'spouse_nameextension' => 'nullable|string|max:10',
            'spouse_occupation' => 'nullable|string|max:100',
            'spouse_employerbusiness' => 'nullable|string|max:255',
            'spouse_businessaddress' => 'nullable|string|max:255',
            'spouse_telephone' => 'nullable|string|max:20',
            'father_firstname' => 'nullable|string|max:100',
            'father_middlename' => 'nullable|string|max:100',
            'father_lastname' => 'nullable|string|max:100',
            'father_nameextension' => 'nullable|string|max:10',
            'mother_firstname' => 'nullable|string|max:100',
            'mother_middlename' => 'nullable|string|max:100',
            'mother_lastname' => 'nullable|string|max:100',
            'mother_nameextension' => 'nullable|string|max:10',
            'department_id' => 'required|exists:departments,id',

            'children' => 'sometimes|array',
            'children.*.fullname' => 'required|string|max:255',
            'children.*.gender' => 'required|in:' . implode(',', Employee::$gender),
            'children.*.birthdate' => 'required|date',

            'education' => 'sometimes|array',
            'education.*.level' => 'required|string|max:100',
            'education.*.school' => 'required|string|max:255',
            'education.*.degree' => 'required|string|max:255',
            'education.*.start' => 'required|integer|digits:4|min:1900|max:' . date('Y'),
            'education.*.end' => 'nullable|integer|digits:4|min:1900|max:' . date('Y'),
            'education.*.earned' => 'nullable|string',
            'education.*.graduated' => 'nullable|integer|digits:4|min:1900|max:' . date('Y'),
            'education.*.accolades' => 'nullable|string',

            // 'eligibilities' => 'sometimes|array',
            // 'eligibilities.*.examination' => 'required|string|max:200',
            // 'eligibilities.*.rating' => 'nullable|numeric|min:0|max:100',
            // 'eligibilities.*.examdate' => 'nullable|date',
            // 'eligibilities.*.address' => 'nullable|string',
            // 'eligibilities.*.license' => 'nullable|string|max:80',
            // 'eligibilities.*.validity' => 'nullable|integer|digits:4|min:1900|max:2100' . date('Y'),

            'workexperiences' => 'sometimes|array',
            'workexperiences.*.position' => 'required|string|max:120',
            'workexperiences.*.company' => 'required|string|max:200',
            'workexperiences.*.monthlysalary' => 'nullable|string',
            'workexperiences.*.paygrade' => 'nullable|string|max:10',
            'workexperiences.*.appointmentstatus' => 'required|string|max:70',
            // 'workexperiences.*.govtservice' => 'required|boolean',
            'workexperiences.*.start' => 'nullable|date',
            'workexperiences.*.end' => 'nullable|date|after_or_equal:start',

        ];
    }

    public function messages(): array
    {
        return [
            'picture.required' => 'An employee picture is required.',
            'firstname.required' => 'First name is required.',
            'lastname.required' => 'Last name is required.',
            'birthdate.required' => 'Date of birth is required.',
            'birthplace.required' => 'Place of birth is required.',
            'gender.required' => 'Please select the gender of the employee.',
            'citizenship.required' => 'Citizenship is required.',
            'civilstatus.required' => 'Civil status is required.',
            'mobile.required' => 'Mobile number is required.',
            'email.required' => 'Email address is required.',
            'bloodtype.required' => 'Blood type is required.',
            'designation.required' => 'Designation/Position is required.',
            'department_id.required' => 'Please select the department for the employee.',

            // Nested messages
            'children.*.fullname.required' => 'The full name of the child is required.',
            'children.*.gender.required' => 'Gender is required for each child.',
            'children.*.birthdate.required' => 'Birthdate is required for each child.',

            'education.*.level.required' => 'Education level is required.',
            'education.*.school.required' => 'School name is required.',
            'education.*.degree.required' => 'Degree earned is required.',
            'education.*.start.required' => 'Start year is required for each education entry.',
            'education.*.end.required' => 'End year is required for each education entry.',

            // 'eligibilities.*.examination.required' => 'Examination name is required.',
            // 'eligibilities.*.rating.required' => 'Rating is required for each eligibility.',
            // 'eligibilities.*.examdate.required' => 'Examination date is required for each eligibility.',

            'workexperiences.*.position.required' => 'Job position is required for each work experience.',
            'workexperiences.*.company.required' => 'Company name is required for each work experience.',
            'workexperiences.*.appointmentstatus.required' => 'Appointment status is required.',
            // 'workexperiences.*.govtservice.required' => 'Government service status is required.',
        ];
    }
}
