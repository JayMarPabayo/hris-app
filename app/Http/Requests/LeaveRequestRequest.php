<?php

namespace App\Http\Requests;

use App\Models\SystemConfig;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeaveRequestRequest extends FormRequest
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

        $config = SystemConfig::first();


        return [
            'user_id' => [
                'required',
                'exists:users,id',
            ],
            'reason' => 'required|string|in:Vacation Leave,Sick Leave,Leave with Pay,Maternity Leave,Paternity Leave,Others',
            'custom_reason' => 'nullable|string|max:255',
            'start' => 'required|date|after_or_equal:today',
            'end' => [
                'required',
                'date',
                'after_or_equal:start',
            ],
            'status' => 'string',
        ];
    }

    public function messages()
    {
        return [
            'reason.required' => 'A reason for the leave request is required.',
            'reason.in' => 'The selected reason is invalid.',
            'custom_reason.max' => 'The custom reason may not be greater than 255 characters.',
            'start.required' => 'The start date is required.',
            'start.after_or_equal' => 'The start date must be today or a future date.',
            'end.required' => 'The end date is required.',
            'end.after_or_equal' => 'The end date must be after or equal to the start date.',
        ];
    }
}
