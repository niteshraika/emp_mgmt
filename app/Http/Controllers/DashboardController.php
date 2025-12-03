<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Department;
use App\Models\Employee;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = Employee::whereNull('deleted_at')->count();
        $totalDepartments = Department::whereNull('deleted_at')->count();
        
        // Get department statistics with employee counts and average salary
        $departmentStats = Department::whereNull('deleted_at')
            ->withCount('employees')
            ->selectRaw('departments.*, 
                (SELECT AVG(salary) FROM employees 
                 WHERE employees.department_id = departments.id 
                 AND employees.deleted_at IS NULL) as avg_salary')
            ->get();
        
        return view('dashboard', compact('totalEmployees', 'totalDepartments', 'departmentStats'));
    }
}
