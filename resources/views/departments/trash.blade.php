@extends('layouts.app')

@section('header', 'Trash - Departments')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold">Deleted Departments</h2>
            <a href="{{ route('departments.index') }}" class="btn btn-secondary">Back to Departments</a>
        </div>
    </div>
    <div class="card-body">
        @if($departments->isEmpty())
            <p class="text-gray text-center py-8">No deleted departments in trash.</p>
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
