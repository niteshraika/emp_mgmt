@extends('layouts.app')

@section('header', 'Trash - Deleted Records')

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="text-xl font-bold">Deleted Employees & Departments</h2>
    </div>
    <div class="card-body">
        @php
            $employees = \App\Models\Employee::onlyTrashed()->with('department')->get();
            $departments = \App\Models\Department::onlyTrashed()->get();
        @endphp
        <h3 class="text-lg font-semibold mb-2">Employees</h3>
        @if($employees->isEmpty())
            <p class="text-gray text-center py-4">No deleted employees in trash.</p>
        @else
            <div class="table-container mb-6">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Department</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                            <tr>
                                <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                <td>{{ $employee->email }}</td>
                                <td>{{ $employee->phone }}</td>
                                <td>{{ $employee->department?->name ?? '-' }}</td>
                                <td class="text-sm text-gray">{{ $employee->deleted_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('employees.restore', $employee->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-primary" style="padding: 0.375rem 0.75rem; font-size: 0.75rem;">Restore</button>
                                        </form>
                                        <form action="{{ route('employees.force-delete', $employee->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" style="padding: 0.375rem 0.75rem; font-size: 0.75rem;" onclick="return confirm('Permanently delete this employee?');">Delete Forever</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        <h3 class="text-lg font-semibold mb-2">Departments</h3>
        @if($departments->isEmpty())
            <p class="text-gray text-center py-4">No deleted departments in trash.</p>
        @else
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($departments as $department)
                            <tr>
                                <td>{{ $department->name }}</td>
                                <td class="text-sm text-gray">{{ $department->description ? Str::limit($department->description, 50) : '-' }}</td>
                                <td class="text-sm text-gray">{{ $department->deleted_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('departments.restore', $department->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-primary" style="padding: 0.375rem 0.75rem; font-size: 0.75rem;">Restore</button>
                                        </form>
                                        <form action="{{ route('departments.force-delete', $department->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" style="padding: 0.375rem 0.75rem; font-size: 0.75rem;" onclick="return confirm('Permanently delete this department?');">Delete Forever</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
