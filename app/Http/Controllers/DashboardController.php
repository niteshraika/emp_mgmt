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
        return view('dashboard', compact('totalEmployees', 'totalDepartments'));
    }
}
