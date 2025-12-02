@extends('layouts.app')

@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-2 gap-6">
    <div class="card">
        <div class="card-body">
            <h3 class="text-sm text-gray font-medium uppercase tracking-wider">Total Employees</h3>
            <div class="mt-4 flex items-baseline">
                <span class="text-2xl font-bold">{{ $totalEmployees }}</span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h3 class="text-sm text-gray font-medium uppercase tracking-wider">Total Departments</h3>
            <div class="mt-4 flex items-baseline">
                <span class="text-2xl font-bold">{{ $totalDepartments }}</span>
            </div>
        </div>
    </div>
</div>

<div class="mt-6 card">
    <div class="card-header">
        <h3 class="text-lg font-medium">Welcome to Employee Management System</h3>
    </div>
    <div class="card-body">
        <p class="text-gray">
            Use the sidebar to manage departments and employees.
        </p>
    </div>
</div>
@endsection
