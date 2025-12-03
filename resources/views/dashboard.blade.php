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
        <h3 class="text-lg font-medium">Departments Overview</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Department</th>
                        <th style="text-align: center; width: 120px;">Employees</th>
                        <th style="text-align: center; width: 120px;">Avg Salary</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departmentStats as $dept)
                    <tr>
                        <td class="font-medium">{{ $dept->name }}</td>
                        <td style="text-align: center; color: var(--primary); font-weight: 500;">{{ $dept->employees_count }}</td>
                        <td style="text-align: center; color: var(--primary); font-weight: 500;">{{ $dept->avg_salary ? '$' . number_format($dept->avg_salary, 2) : '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-gray">No departments yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
