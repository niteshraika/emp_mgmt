<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with('department')->whereNull('deleted_at');
        
        // Filter by department
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        
        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $employees = $query->paginate(15);
        $departments = Department::whereNull('deleted_at')->get();
        
        return view('employees.index', compact('employees', 'departments'));
    }

    public function create()
    {
        $departments = Department::whereNull('deleted_at')->get();
        return view('employees.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'required|string|max:20',
            'department_id' => 'required|exists:departments,id',
            'salary' => 'required|numeric',
            'joining_date' => 'required|date',
            'address' => 'required|string',
        ]);

        Employee::create($request->all());

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

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'required|string|max:20',
            'department_id' => 'required|exists:departments,id',
            'salary' => 'required|numeric',
            'joining_date' => 'required|date',
            'address' => 'required|string',
        ]);

        $employee->update($request->all());

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }

    public function trash()
    {
        $employees = Employee::onlyTrashed()->with('department')->get();
        return view('employees.trash', compact('employees'));
    }

    public function restore($id)
    {
        $employee = Employee::withTrashed()->find($id);
        if ($employee) {
            $employee->restore();
            return redirect()->route('employees.trash')->with('success', 'Employee restored successfully.');
        }
        return redirect()->route('employees.trash')->with('error', 'Employee not found.');
    }

    public function forceDelete($id)
    {
        $employee = Employee::withTrashed()->find($id);
        if ($employee) {
            $employee->forceDelete();
            return redirect()->route('employees.trash')->with('success', 'Employee permanently deleted.');
        }
        return redirect()->route('employees.trash')->with('error', 'Employee not found.');
    }
}

