<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('departments', DepartmentController::class);
    Route::resource('employees', EmployeeController::class);
    
    // Trash/Restore routes
    Route::get('/trash/employees', [EmployeeController::class, 'trash'])->name('employees.trash');
    Route::post('/trash/employees/{id}/restore', [EmployeeController::class, 'restore'])->name('employees.restore');
    Route::delete('/trash/employees/{id}/force-delete', [EmployeeController::class, 'forceDelete'])->name('employees.force-delete');
    
    Route::get('/trash/departments', [DepartmentController::class, 'trash'])->name('departments.trash');
    Route::post('/trash/departments/{id}/restore', [DepartmentController::class, 'restore'])->name('departments.restore');
    Route::delete('/trash/departments/{id}/force-delete', [DepartmentController::class, 'forceDelete'])->name('departments.force-delete');
    
    Route::get('/trash', function () {
        return view('trash');
    })->name('trash');
});
