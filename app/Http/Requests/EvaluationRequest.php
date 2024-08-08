<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EvaluationRequest extends FormRequest
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
        $evaluationId = $this->evaluation ? $this->evaluation->id : null;

        return [
            'employee_id' => [
                'required',
                'exists:employees,id',
                Rule::unique('evaluations')->ignore($evaluationId),
            ],
            'rating' => 'required|numeric|min:1|max:10',
            'review' => 'required'
        ];
    }
}
