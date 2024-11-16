<?php

namespace App\Http\Requests;

use App\Models\Schedule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ScheduleRequest extends FormRequest
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
        $scheduleId = $this->schedule ? $this->schedule->id : null;

        return [
            'employee_id' => [
                'required',
                'exists:employees,id',
                function ($attribute, $value, $fail) use ($scheduleId) {
                    $week = $this->week;

                    $exists = Schedule::where('employee_id', $value)
                        ->where('week', $week)
                        ->when($scheduleId, fn($query) => $query->where('id', '!=', $scheduleId))
                        ->exists();

                    if ($exists) {
                        $fail('The employee is already assigned a schedule for this week.');
                    }
                },
            ],
            'shift_id' => 'required|exists:shifts,id',
            'week' => 'required|string',
            'dayoffs' => 'nullable|array',

        ];
    }


    public function messages(): array
    {
        return [
            'employee_id.required' => 'The employee is required.',
            'employee_id.exists' => 'The selected employee does not exist.',
            'shift_id.required' => 'The shift is required.',
            'shift_id.exists' => 'The selected shift does not exist.',
            'week.required' => 'The week is required.',
            'week.string' => 'The week must be a valid string.',
            'dayoffs.array' => 'The day-offs must be an array of values.',
        ];
    }
}
