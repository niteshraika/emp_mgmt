<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::whereNull('deleted_at');
        
        // Search by name or description
        if ($request->filled('search')) {
            $search = $request->search;
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
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Department::create($request->all());

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

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $department->update($request->all());

        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }

    public function trash()
    {
        $departments = Department::onlyTrashed()->get();
        return view('departments.trash', compact('departments'));
    }

    public function restore($id)
    {
        $department = Department::withTrashed()->find($id);
        if ($department) {
            $department->restore();
            return redirect()->route('departments.trash')->with('success', 'Department restored successfully.');
        }
        return redirect()->route('departments.trash')->with('error', 'Department not found.');
    }

    public function forceDelete($id)
    {
        $department = Department::withTrashed()->find($id);
        if ($department) {
            $department->forceDelete();
            return redirect()->route('departments.trash')->with('success', 'Department permanently deleted.');
        }
        return redirect()->route('departments.trash')->with('error', 'Department not found.');
    }
}

