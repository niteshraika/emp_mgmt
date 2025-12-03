@extends('layouts.app')

@section('title', 'Import Employees')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Import Employees</h1>
        <p class="mt-2 text-gray-600">Upload a CSV file to bulk import employees</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <strong>Please fix the following errors:</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <!-- File Upload Form -->
            <form id="importForm" action="{{ route('employees.import-preview') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Drag & Drop Area -->
                <div id="dropZone" class="relative rounded-lg border-2 border-dashed border-gray-300 p-12 text-center cursor-pointer transition hover:border-blue-500 hover:bg-blue-50">
                    <input type="file" id="csvFile" name="csv_file" accept=".csv,.txt" style="display: none;">
                    
                    <div class="flex justify-center mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <p class="text-lg font-semibold text-gray-700">Drop CSV file here</p>
                    <p class="text-gray-600">or click to browse</p>
                    <p class="text-sm text-gray-500 mt-2">Supports CSV and TXT files up to 5MB</p>
                </div>

                <!-- Selected File Display -->
                <div id="fileInfo" class="mt-6 hidden">
                    <div class="flex items-center justify-between bg-gray-50 p-4 rounded">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <div class="ml-4">
                                <p id="fileName" class="text-sm font-medium text-gray-900"></p>
                                <p id="fileSize" class="text-xs text-gray-500"></p>
                            </div>
                        </div>
                        <button type="button" id="clearFile" class="text-red-600 hover:text-red-800">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- CSV Format Guide -->
                <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded">
                    <h3 class="font-semibold text-blue-900 mb-3">CSV Format Requirements</h3>
                    <p class="text-sm text-blue-800 mb-3">Your CSV file must contain the following headers (in any order):</p>
                    <div class="grid grid-cols-2 gap-2 text-sm text-blue-800">
                        <div class="font-mono bg-white p-2 rounded">first_name</div>
                        <div class="font-mono bg-white p-2 rounded">last_name</div>
                        <div class="font-mono bg-white p-2 rounded">email</div>
                        <div class="font-mono bg-white p-2 rounded">phone</div>
                        <div class="font-mono bg-white p-2 rounded">department_id</div>
                        <div class="font-mono bg-white p-2 rounded">salary</div>
                        <div class="font-mono bg-white p-2 rounded">joining_date</div>
                        <div class="font-mono bg-white p-2 rounded">address</div>
                    </div>
                    <p class="text-xs text-blue-700 mt-3">
                        <strong>Example:</strong> first_name,last_name,email,phone,department_id,salary,joining_date,address
                    </p>
                    <p class="text-xs text-blue-700 mt-2">
                        <strong>Date format:</strong> YYYY-MM-DD | <strong>Salary:</strong> Numeric value
                    </p>
                </div>

                <!-- Buttons -->
                <div class="mt-8 flex gap-4">
                    <button type="submit" id="previewBtn" disabled class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold disabled:opacity-50 disabled:cursor-not-allowed hover:bg-blue-700">
                        Preview & Validate
                    </button>
                    <a href="{{ route('employees.index') }}" class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg font-semibold hover:bg-gray-400">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const csvFile = document.getElementById('csvFile');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const clearFile = document.getElementById('clearFile');
    const previewBtn = document.getElementById('previewBtn');

    // Click to select file
    dropZone.addEventListener('click', function() {
        csvFile.click();
    });

    // File selection
    csvFile.addEventListener('change', function() {
        if (this.files.length > 0) {
            updateFileInfo(this.files[0]);
        }
    });

    // Drag and drop
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.add('border-blue-500', 'bg-blue-50');
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');

        if (e.dataTransfer.files.length > 0) {
            const file = e.dataTransfer.files[0];
            
            // Validate file type
            if (!['text/csv', 'text/plain', 'application/vnd.ms-excel'].includes(file.type)) {
                alert('Please select a CSV file');
                return;
            }
            
            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must not exceed 5MB');
                return;
            }
            
            csvFile.files = e.dataTransfer.files;
            updateFileInfo(file);
        }
    });

    // Clear file
    clearFile.addEventListener('click', function() {
        csvFile.value = '';
        fileInfo.classList.add('hidden');
        previewBtn.disabled = true;
    });

    // Update file info
    function updateFileInfo(file) {
        fileName.textContent = file.name;
        fileSize.textContent = (file.size / 1024).toFixed(2) + ' KB';
        fileInfo.classList.remove('hidden');
        previewBtn.disabled = false;
    }
});
</script>
@endsection
