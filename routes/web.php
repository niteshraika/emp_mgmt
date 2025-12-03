<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate')->middleware('throttle:5,1'); // 5 attempts per minute
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('departments', DepartmentController::class);
    Route::resource('employees', EmployeeController::class);
    
    // Export routes
    Route::get('/employees/export/csv', [EmployeeController::class, 'export'])->name('employees.export');
    Route::get('/departments/export/csv', [DepartmentController::class, 'export'])->name('departments.export');
    
    // Bulk action routes
    Route::post('/employees/bulk-delete', [EmployeeController::class, 'bulkDelete'])->name('employees.bulk-delete');
    Route::post('/employees/bulk-export', [EmployeeController::class, 'bulkExport'])->name('employees.bulk-export');
    
    // Import routes
    Route::get('/employees/import', [EmployeeController::class, 'importShow'])->name('employees.import');
    Route::post('/employees/import-preview', [EmployeeController::class, 'importPreview'])->name('employees.import-preview');
    Route::post('/employees/import-process', [EmployeeController::class, 'importProcess'])->name('employees.import-process');
    
    // Activity Log routes
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/{activityLog}', [ActivityLogController::class, 'show'])->name('activity-logs.show');

    // User management routes (RBAC protected within controller methods)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
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
