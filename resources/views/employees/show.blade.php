@extends('layouts.app')

@section('header', 'Employee Profile')

@section('content')
<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold">{{ $employee->first_name }} {{ $employee->last_name }}</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-secondary" style="padding: 0.375rem 0.75rem; font-size:0.875rem;">Edit</a>
                <form action="{{ route('employees.destroy', $employee) }}" method="POST" onsubmit="return confirm('Delete this employee?')" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" style="padding: 0.375rem 0.75rem; font-size:0.875rem;">Delete</button>
                </form>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-2">
                <h3 class="text-sm text-gray font-medium uppercase tracking-wider">Contact</h3>
                <div class="mt-2">
                    <p><strong>Email:</strong> <span class="text-gray">{{ $employee->email }}</span></p>
                    <p><strong>Phone:</strong> <span class="text-gray">{{ $employee->phone ?? '—' }}</span></p>
                    <p><strong>Address:</strong> <span class="text-gray">{{ $employee->address ?? '—' }}</span></p>
                </div>

                <div class="mt-4">
                    <h3 class="text-sm text-gray font-medium uppercase tracking-wider">About</h3>
                    <div class="mt-2 text-gray">
                        <p><strong>Department:</strong> {{ $employee->department?->name ?? '—' }}</p>
                        <p><strong>Joining Date:</strong> {{ optional($employee->joining_date)->format('M d, Y') ?? '—' }}</p>
                        <p><strong>Salary:</strong> {{ $employee->salary ? '$' . number_format($employee->salary, 2) : '—' }}</p>
                    </div>
                </div>
            </div>

            <div class="col-span-1">
                <div style="border:1px solid var(--border); padding:1rem; border-radius:0.5rem; background:var(--header-bg);">
                    <h4 class="text-sm text-gray font-medium">Quick Info</h4>
                    <div class="mt-3">
                        <p class="text-lg font-semibold">{{ $employee->first_name }} {{ $employee->last_name }}</p>
                        <p class="text-sm text-gray">{{ $employee->department?->name ?? '—' }}</p>
                        <p class="mt-3 text-sm text-gray">Joined: {{ optional($employee->joining_date)->format('M d, Y') ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
