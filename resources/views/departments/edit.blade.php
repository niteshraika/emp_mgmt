@extends('layouts.app')

@section('header', 'Edit Department')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form action="{{ route('departments.update', $department) }}" method="POST" class="flex flex-col gap-4">
            @csrf
            @method('PUT')
            <div>
                <label for="name" class="text-sm font-medium mb-4" style="display:block;">Department Name</label>
                <input type="text" name="name" id="name" class="input" value="{{ old('name', $department->name) }}" required>
                @error('name')
                    <span class="text-sm text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="description" class="text-sm font-medium mb-4" style="display:block;">Description</label>
                <textarea name="description" id="description" class="input" rows="4">{{ old('description', $department->description) }}</textarea>
                @error('description')
                    <span class="text-sm text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex gap-4 mt-4">
                <button type="submit" class="btn btn-primary">Update Department</button>
                <a href="{{ route('departments.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
