<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportEmployeesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'csv_file' => 'required|file|mimes:csv,txt|max:5120', // 5MB max
        ];
    }

    public function messages(): array
    {
        return [
            'csv_file.required' => 'Please select a CSV file.',
            'csv_file.mimes' => 'The file must be a CSV file.',
            'csv_file.max' => 'The file must not exceed 5MB.',
        ];
    }
}
