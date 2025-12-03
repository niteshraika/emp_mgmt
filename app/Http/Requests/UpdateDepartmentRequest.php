<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartmentRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $departmentId = $this->route('department')->id;

        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'unique:departments,name,' . $departmentId,
                'regex:/^[a-zA-Z0-9\s\&\-\']+$/',
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.regex' => 'Department name can only contain letters, numbers, spaces, ampersand, hyphens, and apostrophes.',
            'name.unique' => 'A department with this name already exists.',
            'description.max' => 'Description cannot exceed 1000 characters.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim($this->name),
            'description' => trim($this->description),
        ]);
    }
}
