@extends('layouts.app')

@section('header', 'Employees')

@section('content')
<div class="card">
    <div class="card-header flex justify-between items-center">
        <h3 class="text-lg font-medium">Employees</h3>
        <a href="{{ route('employees.create') }}" class="btn btn-primary" style="padding: 0.375rem 0.75rem; font-size: 0.8125rem;">+ Add</a>
    </div>
    
    <!-- Filters -->
    <div class="card-body" style="border-bottom: 1px solid var(--border); padding: 0.875rem 1.5rem;">
        <form method="GET" action="{{ route('employees.index') }}" class="flex gap-2 flex-wrap items-end">
            <div style="flex: 1; min-width: 160px;">
                <label for="search" class="text-xs font-semibold mb-1 text-gray" style="display: block; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.7rem;">Search</label>
                <input type="text" name="search" id="search" class="input" placeholder="Name or email..." value="{{ request('search') }}" style="font-size: 0.875rem; padding: 0.375rem 0.5rem;">
            </div>
            
            <div style="flex: 1; min-width: 160px;">
                <label for="department_id" class="text-xs font-semibold mb-1 text-gray" style="display: block; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.7rem;">Department</label>
                <select name="department_id" id="department_id" class="input" style="font-size: 0.875rem; padding: 0.375rem 0.5rem;">
                    <option value="">All</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex gap-1" style="gap: 0.375rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.375rem 0.75rem; font-size: 0.8125rem;">Filter</button>
                <a href="{{ route('employees.index') }}" class="btn btn-secondary" style="padding: 0.375rem 0.75rem; font-size: 0.8125rem;">Clear</a>
            </div>
        </form>
    </div>
    
    <div class="card-body p-0">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Phone</th>
                        <th style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                    <tr>
                        <td class="font-medium"><a href="{{ route('employees.show', $employee) }}" class="text-primary">{{ $employee->first_name }} {{ $employee->last_name }}</a></td>
                        <td class="text-gray" style="font-size: 0.875rem;">{{ $employee->email }}</td>
                        <td>
                            <span style="background-color: rgba(79, 70, 229, 0.12); color: var(--primary); padding: 0.2rem 0.4rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 500;">
                                {{ $employee->department?->name ?? '—' }}
                            </span>
                        </td>
                        <td class="text-gray" style="font-size: 0.875rem;">{{ $employee->phone ?? '—' }}</td>
                        <td>
                            <div class="flex gap-1" style="gap: 0.25rem;">
                                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                                <form action="{{ route('employees.destroy', $employee) }}" method="POST" onsubmit="return confirm('Delete this employee?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Del</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray">No employees found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    @if($employees->hasPages())
    <div class="card-body" style="border-top: 1px solid var(--border); padding: 0.875rem 1.5rem;">
        {{ $employees->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>
@endsection

