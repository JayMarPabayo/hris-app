<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShiftRequest extends FormRequest
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
        $shiftId = $this->shift ? $this->shift->id : null;
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('shifts')->ignore($shiftId),
            ],
            'weekdays' => 'required|array',
            'weekdays.*' => 'string',
            'start_time' => 'required',
            'end_time' => 'required',
        ];
    }
}
