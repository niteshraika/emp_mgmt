@extends('layouts.app')

@section('title', 'Preview Import')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Preview Import</h1>
        <p class="mt-2 text-gray-600">Review the data below before confirming the import</p>
    </div>

    @if (count($errors) > 0)
        <div class="mb-6 p-4 bg-yellow-100 border border-yellow-400 text-yellow-800 rounded">
            <strong>⚠️ Validation Errors Found:</strong>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Preview Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="p-6 bg-gray-50 border-b">
            <h2 class="text-lg font-semibold text-gray-900">
                Valid Records: {{ count($previewRows) }}
                @if (count($errors) > 0)
                    <span class="ml-2 text-yellow-600 text-sm">{{ count($errors) }} error(s) found</span>
                @endif
            </h2>
            <p class="text-sm text-gray-600 mt-1">Total rows to import: {{ $totalRowsToImport }}</p>
        </div>

        @if (count($previewRows) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Row</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">First Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Last Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Department</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Salary</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Joining Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($previewRows as $preview)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-700 font-medium">{{ $preview['row_number'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $preview['data']['first_name'] ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $preview['data']['last_name'] ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $preview['data']['email'] ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $preview['data']['phone'] ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @php
                                        $dept = \App\Models\Department::find($preview['data']['department_id']);
                                    @endphp
                                    {{ $dept ? $dept->name : 'Invalid' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">Rs. {{ number_format($preview['data']['salary'], 2) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $preview['data']['joining_date'] ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-6 text-center text-gray-500">
                <p>No valid records to display. Please fix the errors and try again.</p>
            </div>
        @endif
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-4 items-center justify-between">
        <div>
            @if (count($previewRows) > 0)
                <p class="text-sm text-gray-600">
                    ✓ Ready to import <span class="font-semibold">{{ count($previewRows) }}</span> record(s)
                </p>
            @endif
        </div>
        <div class="flex gap-4">
            <form action="{{ route('employees.import-process') }}" method="POST" id="confirmForm">
                @csrf
                @if (count($previewRows) > 0)
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700">
                        Confirm Import
                    </button>
                @endif
            </form>
            <a href="{{ route('employees.import') }}" class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg font-semibold hover:bg-gray-400">
                Back to Upload
            </a>
            <a href="{{ route('employees.index') }}" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg font-semibold hover:bg-gray-300">
                Cancel
            </a>
        </div>
    </div>

    <!-- Tips -->
    <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded">
        <h3 class="font-semibold text-blue-900 mb-2">📋 Tips</h3>
        <ul class="text-sm text-blue-800 list-disc list-inside space-y-1">
            <li>Review the preview carefully before confirming</li>
            <li>Rows with validation errors will be skipped during import</li>
            <li>All imported employees will be logged in the Activity Log</li>
            <li>Email addresses must be unique in the system</li>
        </ul>
    </div>
</div>

<script>
document.getElementById('confirmForm').addEventListener('submit', function(e) {
    const recordCount = {{ count($previewRows) }};
    if (!confirm(`Are you sure you want to import ${recordCount} employee(s)? This action cannot be undone.`)) {
        e.preventDefault();
    }
});
</script>
@endsection
