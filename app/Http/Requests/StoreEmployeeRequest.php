<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
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
        return [
            'first_name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-Z\s\'-]+$/', // Only letters, spaces, hyphens, apostrophes
            ],
            'last_name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-Z\s\'-]+$/',
            ],
            'email' => [
                'required',
                'email',
                'unique:employees,email',
                'max:255',
            ],
            'phone' => [
                'required',
                'string',
                'regex:/^[\d\s\+\(\)\-\.]+$/', // Only digits, spaces, +, (, ), -, .
                'min:10',
                'max:20',
            ],
            'department_id' => [
                'required',
                'integer',
                'exists:departments,id',
            ],
            'salary' => [
                'required',
                'numeric',
                'min:0',
                'max:999999.99',
            ],
            'joining_date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'address' => [
                'required',
                'string',
                'min:5',
                'max:500',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'first_name.regex' => 'First name can only contain letters, spaces, hyphens, and apostrophes.',
            'last_name.regex' => 'Last name can only contain letters, spaces, hyphens, and apostrophes.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'phone.regex' => 'Phone number contains invalid characters.',
            'phone.min' => 'Phone number must be at least 10 characters.',
            'joining_date.before_or_equal' => 'Joining date cannot be in the future.',
            'salary.min' => 'Salary must be a positive number.',
            'salary.max' => 'Salary exceeds maximum allowed value.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'first_name' => trim($this->first_name),
            'last_name' => trim($this->last_name),
            'email' => strtolower(trim($this->email)),
            'phone' => trim($this->phone),
            'address' => trim($this->address),
        ]);
    }
}
