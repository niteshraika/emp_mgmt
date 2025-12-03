<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\ActivityLog;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Requests\ImportEmployeesRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with('department')->whereNull('deleted_at');
        
        // Filter by department
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->integer('department_id'));
        }
        
        // Search by name or email (sanitized via parameterized queries)
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Filter by salary range
        if ($request->filled('salary_min')) {
            $salary_min = (float) $request->input('salary_min');
            $query->where('salary', '>=', $salary_min);
        }
        
        if ($request->filled('salary_max')) {
            $salary_max = (float) $request->input('salary_max');
            $query->where('salary', '<=', $salary_max);
        }
        
        // Filter by joining date range
        if ($request->filled('joining_date_from')) {
            $joining_date_from = $request->input('joining_date_from');
            $query->where('joining_date', '>=', $joining_date_from);
        }
        
        if ($request->filled('joining_date_to')) {
            $joining_date_to = $request->input('joining_date_to');
            $query->where('joining_date', '<=', $joining_date_to);
        }
        
        $employees = $query->paginate(15);
        $departments = Department::whereNull('deleted_at')->get();
        
        return view('employees.index', compact('employees', 'departments'));
    }

    public function create()
    {
        if (!auth()->user()->canDelete()) {
            return redirect()->route('employees.index')->with('error', 'You do not have permission to create employees.');
        }
        
        $departments = Department::whereNull('deleted_at')->get();
        return view('employees.create', compact('departments'));
    }

    public function store(StoreEmployeeRequest $request)
    {
        if (!auth()->user()->canDelete()) {
            return redirect()->route('employees.index')->with('error', 'You do not have permission to create employees.');
        }
        
        $employee = Employee::create($request->validated());
        ActivityLog::record('created', $employee);
        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::whereNull('deleted_at')->get();
        return view('employees.edit', compact('employee', 'departments'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $oldValues = $employee->getAttributes();
        $employee->update($request->validated());
        $newValues = $employee->getAttributes();
        
        // Log only changed attributes
        $changes = [];
        foreach ($oldValues as $key => $oldValue) {
            if (isset($newValues[$key]) && $oldValue !== $newValues[$key]) {
                $changes[$key] = ['old' => $oldValue, 'new' => $newValues[$key]];
            }
        }
        
        if (!empty($changes)) {
            ActivityLog::record('updated', $employee, $changes);
        }
        
        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        if (!auth()->user()->canDelete()) {
            return redirect()->route('employees.index')->with('error', 'You do not have permission to delete employees.');
        }
        
        ActivityLog::record('deleted', $employee);
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }

    public function trash()
    {
        if (!auth()->user()->canDelete()) {
            return redirect()->route('employees.index')->with('error', 'You do not have permission to access trash.');
        }
        
        $employees = Employee::onlyTrashed()->with('department')->get();
        return view('employees.trash', compact('employees'));
    }

    public function restore($id)
    {
        if (!auth()->user()->canRestore()) {
            return redirect()->route('employees.trash')->with('error', 'You do not have permission to restore employees.');
        }
        
        $id = (int) $id; // Cast to integer
        $employee = Employee::withTrashed()->find($id);
        if ($employee) {
            $employee->restore();
            ActivityLog::record('restored', $employee);
            return redirect()->route('employees.trash')->with('success', 'Employee restored successfully.');
        }
        return redirect()->route('employees.trash')->with('error', 'Employee not found.');
    }

    public function forceDelete($id)
    {
        if (!auth()->user()->canRestore()) {
            return redirect()->route('employees.trash')->with('error', 'You do not have permission to permanently delete employees.');
        }
        
        $id = (int) $id; // Cast to integer
        $employee = Employee::withTrashed()->find($id);
        if ($employee) {
            $employee->forceDelete();
            return redirect()->route('employees.trash')->with('success', 'Employee permanently deleted.');
        }
        return redirect()->route('employees.trash')->with('error', 'Employee not found.');
    }

    public function export(Request $request)
    {
        // Build query with same filters as index
        $query = Employee::with('department')->whereNull('deleted_at');
        
        // Filter by department
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->integer('department_id'));
        }
        
        // Search by name or email
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Filter by salary range
        if ($request->filled('salary_min')) {
            $salary_min = (float) $request->input('salary_min');
            $query->where('salary', '>=', $salary_min);
        }
        
        if ($request->filled('salary_max')) {
            $salary_max = (float) $request->input('salary_max');
            $query->where('salary', '<=', $salary_max);
        }
        
        // Filter by joining date range
        if ($request->filled('joining_date_from')) {
            $joining_date_from = $request->input('joining_date_from');
            $query->where('joining_date', '>=', $joining_date_from);
        }
        
        if ($request->filled('joining_date_to')) {
            $joining_date_to = $request->input('joining_date_to');
            $query->where('joining_date', '<=', $joining_date_to);
        }
        
        $employees = $query->get();
        
        $response = new StreamedResponse(function () use ($employees) {
            $handle = fopen('php://output', 'w');
            
            // Write CSV header
            fputcsv($handle, ['First Name', 'Last Name', 'Email', 'Phone', 'Department', 'Salary', 'Joining Date', 'Address']);
            
            // Write rows
            foreach ($employees as $employee) {
                fputcsv($handle, [
                    $employee->first_name,
                    $employee->last_name,
                    $employee->email,
                    $employee->phone ?? '',
                    $employee->department?->name ?? '',
                    $employee->salary,
                    $employee->joining_date,
                    $employee->address ?? '',
                ]);
            }
            
            fclose($handle);
        });
        
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="employees_' . date('Y-m-d_His') . '.csv"');
        
        return $response;
    }

    public function bulkDelete(Request $request)
    {
        if (!auth()->user()->canDelete()) {
            return redirect()->route('employees.index')->with('error', 'You do not have permission to delete employees.');
        }
        
        $idsInput = $request->input('ids', '[]');
        
        // Decode JSON if it's a string
        $ids = is_string($idsInput) ? json_decode($idsInput, true) : $idsInput;
        
        if (empty($ids)) {
            return redirect()->route('employees.index')->with('error', 'No employees selected.');
        }
        
        // Validate and cast IDs to integers for security
        $ids = array_filter(array_map('intval', $ids));
        
        if (empty($ids)) {
            return redirect()->route('employees.index')->with('error', 'Invalid employee IDs.');
        }
        
        // Delete selected employees
        Employee::whereNull('deleted_at')->whereIn('id', $ids)->delete();
        
        return redirect()->route('employees.index')->with('success', count($ids) . ' employee(s) deleted successfully.');
    }

    public function bulkExport(Request $request)
    {
        $idsInput = $request->input('ids', '[]');
        
        // Decode JSON if it's a string
        $ids = is_string($idsInput) ? json_decode($idsInput, true) : $idsInput;
        
        if (empty($ids)) {
            return redirect()->route('employees.index')->with('error', 'No employees selected.');
        }
        
        // Validate and cast IDs to integers for security
        $ids = array_filter(array_map('intval', $ids));
        
        if (empty($ids)) {
            return redirect()->route('employees.index')->with('error', 'Invalid employee IDs.');
        }
        
        $employees = Employee::with('department')->whereNull('deleted_at')->whereIn('id', $ids)->get();
        
        if ($employees->isEmpty()) {
            return redirect()->route('employees.index')->with('error', 'No employees found.');
        }
        
        $response = new StreamedResponse(function () use ($employees) {
            $handle = fopen('php://output', 'w');
            
            // Write CSV header
            fputcsv($handle, ['First Name', 'Last Name', 'Email', 'Phone', 'Department', 'Salary', 'Joining Date', 'Address']);
            
            // Write rows
            foreach ($employees as $employee) {
                fputcsv($handle, [
                    $employee->first_name,
                    $employee->last_name,
                    $employee->email,
                    $employee->phone ?? '',
                    $employee->department?->name ?? '',
                    $employee->salary,
                    $employee->joining_date,
                    $employee->address ?? '',
                ]);
            }
            
            fclose($handle);
        });
        
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="employees_bulk_' . date('Y-m-d_His') . '.csv"');
        
        return $response;
    }

    public function importShow()
    {
        return view('employees.import');
    }

    public function importPreview(ImportEmployeesRequest $request)
    {
        $file = $request->file('csv_file');
        $handle = fopen($file->path(), 'r');
        
        // Read CSV header
        $headers = fgetcsv($handle);
        
        if (!$headers) {
            fclose($handle);
            return back()->with('error', 'Invalid CSV file format.');
        }
        
        // Expected headers
        $expectedHeaders = ['first_name', 'last_name', 'email', 'phone', 'department_id', 'salary', 'joining_date', 'address'];
        $headers = array_map('strtolower', array_map('trim', $headers));
        
        // Check if required headers match
        $missingHeaders = array_diff($expectedHeaders, $headers);
        if (!empty($missingHeaders)) {
            fclose($handle);
            return back()->with('error', 'CSV must contain headers: ' . implode(', ', $expectedHeaders));
        }
        
        // Read preview rows (first 5)
        $previewRows = [];
        $errors = [];
        $rowNumber = 1;
        
        while (($row = fgetcsv($handle)) !== false && count($previewRows) < 5) {
            $rowNumber++;
            
            // Combine headers with values
            $data = array_combine($headers, array_pad($row, count($headers), null));
            
            // Basic validation
            $rowErrors = $this->validateImportRow($data, $rowNumber);
            
            if (empty($rowErrors)) {
                $previewRows[] = [
                    'row_number' => $rowNumber,
                    'data' => $data,
                    'valid' => true,
                ];
            } else {
                $errors[] = "Row $rowNumber: " . implode('; ', $rowErrors);
            }
        }
        
        fclose($handle);
        
        if (empty($previewRows) && !empty($errors)) {
            return back()->with('error', 'No valid rows to import. ' . implode(' ', $errors));
        }
        
        // Store file path in session for processing
        session(['import_file_path' => $file->path()]);
        
        return view('employees.import-preview', [
            'previewRows' => $previewRows,
            'errors' => $errors,
            'totalRowsToImport' => $rowNumber - 1, // Subtract header row
        ]);
    }

    public function importProcess(Request $request)
    {
        $filePath = session('import_file_path');
        
        if (!$filePath || !file_exists($filePath)) {
            return redirect()->route('employees.index')->with('error', 'Import session expired. Please try again.');
        }
        
        $handle = fopen($filePath, 'r');
        $headers = fgetcsv($handle);
        $headers = array_map('strtolower', array_map('trim', $headers));
        
        $imported = 0;
        $skipped = 0;
        $rowNumber = 1;
        
        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;
            $data = array_combine($headers, array_pad($row, count($headers), null));
            
            // Validate row
            $errors = $this->validateImportRow($data, $rowNumber);
            
            if (!empty($errors)) {
                $skipped++;
                continue;
            }
            
            try {
                // Create employee
                $employee = Employee::create([
                    'first_name' => trim($data['first_name']),
                    'last_name' => trim($data['last_name']),
                    'email' => strtolower(trim($data['email'])),
                    'phone' => trim($data['phone'] ?? ''),
                    'department_id' => (int) $data['department_id'],
                    'salary' => (float) $data['salary'],
                    'joining_date' => $data['joining_date'],
                    'address' => trim($data['address'] ?? ''),
                ]);
                
                // Log the import as a creation
                ActivityLog::record('created', $employee, null, auth()->user());
                $imported++;
            } catch (\Exception $e) {
                $skipped++;
            }
        }
        
        fclose($handle);
        session()->forget('import_file_path');
        
        $message = "$imported employee(s) imported successfully";
        if ($skipped > 0) {
            $message .= ", $skipped row(s) skipped due to validation errors";
        }
        
        return redirect()->route('employees.index')->with('success', $message);
    }

    private function validateImportRow($data, $rowNumber)
    {
        $errors = [];
        
        // Validate first_name
        if (empty($data['first_name']) || strlen(trim($data['first_name'])) < 2) {
            $errors[] = 'First name must be at least 2 characters';
        } elseif (!preg_match("/^[a-zA-Z\s\'-]+$/", trim($data['first_name']))) {
            $errors[] = 'First name contains invalid characters';
        }
        
        // Validate last_name
        if (empty($data['last_name']) || strlen(trim($data['last_name'])) < 2) {
            $errors[] = 'Last name must be at least 2 characters';
        } elseif (!preg_match("/^[a-zA-Z\s\'-]+$/", trim($data['last_name']))) {
            $errors[] = 'Last name contains invalid characters';
        }
        
        // Validate email
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email address';
        } elseif (Employee::where('email', strtolower(trim($data['email'])))->exists()) {
            $errors[] = 'Email already exists';
        }
        
        // Validate phone
        if (!empty($data['phone'])) {
            $phone = trim($data['phone']);
            if (strlen($phone) < 10 || strlen($phone) > 20) {
                $errors[] = 'Phone must be 10-20 characters';
            } elseif (!preg_match("/^[\d\s\+\(\)\-\.]+$/", $phone)) {
                $errors[] = 'Phone contains invalid characters';
            }
        }
        
        // Validate department_id
        if (empty($data['department_id'])) {
            $errors[] = 'Department ID is required';
        } elseif (!Department::where('id', (int) $data['department_id'])->exists()) {
            $errors[] = 'Department does not exist';
        }
        
        // Validate salary
        if (empty($data['salary']) || !is_numeric($data['salary'])) {
            $errors[] = 'Salary must be a number';
        } elseif ((float) $data['salary'] < 0 || (float) $data['salary'] > 999999.99) {
            $errors[] = 'Salary must be between 0 and 999999.99';
        }
        
        // Validate joining_date
        if (empty($data['joining_date'])) {
            $errors[] = 'Joining date is required';
        } elseif (!strtotime($data['joining_date'])) {
            $errors[] = 'Invalid joining date format';
        } elseif (strtotime($data['joining_date']) > time()) {
            $errors[] = 'Joining date cannot be in the future';
        }
        
        // Validate address
        if (!empty($data['address'])) {
            $address = trim($data['address']);
            if (strlen($address) < 5 || strlen($address) > 500) {
                $errors[] = 'Address must be 5-500 characters';
            }
        }
        
        return $errors;
    }
}