@extends('layouts.app')

@section('header', 'Departments')

@section('content')
<div class="card">
    <div class="card-header flex justify-between items-center">
        <h3 class="text-lg font-medium">Departments</h3>
        <a href="{{ route('departments.create') }}" class="btn btn-primary" style="padding: 0.375rem 0.75rem; font-size: 0.8125rem;">+ Add</a>
    </div>
    
    <!-- Filters -->
    <div class="card-body" style="border-bottom: 1px solid var(--border); padding: 0.875rem 1.5rem;">
        <form method="GET" action="{{ route('departments.index') }}" class="flex gap-2 flex-wrap items-end">
            <div style="flex: 1; min-width: 160px;">
                <label for="search" class="text-xs font-semibold mb-1 text-gray" style="display: block; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.7rem;">Search</label>
                <input type="text" name="search" id="search" class="input" placeholder="Name or description..." value="{{ request('search') }}" style="font-size: 0.875rem; padding: 0.375rem 0.5rem;">
            </div>
            
            <div class="flex gap-1" style="gap: 0.375rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.375rem 0.75rem; font-size: 0.8125rem;">Filter</button>
                <a href="{{ route('departments.index') }}" class="btn btn-secondary" style="padding: 0.375rem 0.75rem; font-size: 0.8125rem;">Clear</a>
            </div>
        </form>
    </div>
    
    <div class="card-body p-0">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $department)
                    <tr>
                        <td class="font-medium">{{ $department->name }}</td>
                        <td class="text-gray" style="font-size: 0.875rem;">{{ $department->description ?? '—' }}</td>
                        <td>
                            <div class="flex gap-1" style="gap: 0.25rem;">
                                <a href="{{ route('departments.edit', $department) }}" class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                                <form action="{{ route('departments.destroy', $department) }}" method="POST" onsubmit="return confirm('Delete this department?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Del</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-8 text-gray">No departments found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    @if($departments->hasPages())
    <div class="card-body" style="border-top: 1px solid var(--border); padding: 0.875rem 1.5rem;">
        {{ $departments->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>
@endsection
