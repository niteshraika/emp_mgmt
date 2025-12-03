<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::whereNull('deleted_at')
            ->withCount('employees');
        
        // Search by name or description (sanitized via parameterized queries)
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $departments = $query->paginate(15);
        
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        if (!auth()->user()->canDelete()) {
            return redirect()->route('departments.index')->with('error', 'You do not have permission to create departments.');
        }
        return view('departments.create');
    }

    public function store(StoreDepartmentRequest $request)
    {
        if (!auth()->user()->canDelete()) {
            return redirect()->route('departments.index')->with('error', 'You do not have permission to create departments.');
        }
        $department = Department::create($request->validated());
        \App\Models\ActivityLog::record('created', $department);
        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    public function show(Department $department)
    {
        return view('departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $oldValues = $department->getAttributes();
        $department->update($request->validated());
        $newValues = $department->getAttributes();

        $changes = [];
        foreach ($oldValues as $key => $oldValue) {
            if (isset($newValues[$key]) && $oldValue !== $newValues[$key]) {
                $changes[$key] = ['old' => $oldValue, 'new' => $newValues[$key]];
            }
        }

        if (!empty($changes)) {
            \App\Models\ActivityLog::record('updated', $department, $changes);
        }

        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        if (!auth()->user()->canDelete()) {
            return redirect()->route('departments.index')->with('error', 'You do not have permission to delete departments.');
        }
        \App\Models\ActivityLog::record('deleted', $department);
        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }

    public function trash()
    {
        if (!auth()->user()->canDelete()) {
            return redirect()->route('departments.index')->with('error', 'You do not have permission to access trash.');
        }
        $departments = Department::onlyTrashed()->get();
        return view('departments.trash', compact('departments'));
    }

    public function restore($id)
    {
        if (!auth()->user()->canRestore()) {
            return redirect()->route('departments.trash')->with('error', 'You do not have permission to restore departments.');
        }
        
        $id = (int) $id; // Cast to integer
        $department = Department::withTrashed()->find($id);
        if ($department) {
            $department->restore();
            \App\Models\ActivityLog::record('restored', $department);
            return redirect()->route('departments.trash')->with('success', 'Department restored successfully.');
        }
        return redirect()->route('departments.trash')->with('error', 'Department not found.');
    }

    public function forceDelete($id)
    {
        if (!auth()->user()->canRestore()) {
            return redirect()->route('departments.trash')->with('error', 'You do not have permission to permanently delete departments.');
        }
        
        $id = (int) $id; // Cast to integer
        $department = Department::withTrashed()->find($id);
        if ($department) {
            // Log a permanent deletion. Model still available in memory before forceDelete.
            \App\Models\ActivityLog::record('force_deleted', $department);
            $department->forceDelete();
            return redirect()->route('departments.trash')->with('success', 'Department permanently deleted.');
        }
        return redirect()->route('departments.trash')->with('error', 'Department not found.');
    }

    public function export(Request $request)
    {
        // Build query with same filters as index
        $query = Department::whereNull('deleted_at')
            ->withCount('employees');
        
        // Search by name or description
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $departments = $query->get();
        
        $response = new StreamedResponse(function () use ($departments) {
            $handle = fopen('php://output', 'w');
            
            // Write CSV header
            fputcsv($handle, ['Name', 'Description', 'Employee Count']);
            
            // Write rows
            foreach ($departments as $department) {
                fputcsv($handle, [
                    $department->name,
                    $department->description ?? '',
                    $department->employees_count,
                ]);
            }
            
            fclose($handle);
        });
        
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="departments_' . date('Y-m-d_His') . '.csv"');
        
        return $response;
    }
}

