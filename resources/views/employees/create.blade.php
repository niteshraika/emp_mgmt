@extends('layouts.app')

@section('header', 'Add Employee')

@section('content')
<div class="card" style="max-width: 800px;">
    <div class="card-body">
        <form action="{{ route('employees.store') }}" method="POST" class="grid grid-cols-2 gap-6">
            @csrf
            
            <div>
                <label for="first_name" class="text-sm font-medium mb-4" style="display:block;">First Name</label>
                <input type="text" name="first_name" id="first_name" class="input" value="{{ old('first_name') }}" required>
                @error('first_name')
                    <span class="text-sm text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="last_name" class="text-sm font-medium mb-4" style="display:block;">Last Name</label>
                <input type="text" name="last_name" id="last_name" class="input" value="{{ old('last_name') }}" required>
                @error('last_name')
                    <span class="text-sm text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="email" class="text-sm font-medium mb-4" style="display:block;">Email Address</label>
                <input type="email" name="email" id="email" class="input" value="{{ old('email') }}" required>
                @error('email')
                    <span class="text-sm text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="phone" class="text-sm font-medium mb-4" style="display:block;">Phone Number</label>
                <input type="text" name="phone" id="phone" class="input" value="{{ old('phone') }}" required>
                @error('phone')
                    <span class="text-sm text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="department_id" class="text-sm font-medium mb-4" style="display:block;">Department</label>
                <select name="department_id" id="department_id" class="input" required>
                    <option value="">Select Department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
                @error('department_id')
                    <span class="text-sm text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="salary" class="text-sm font-medium mb-4" style="display:block;">Salary</label>
                <input type="number" step="0.01" name="salary" id="salary" class="input" value="{{ old('salary') }}" required>
                @error('salary')
                    <span class="text-sm text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="joining_date" class="text-sm font-medium mb-4" style="display:block;">Joining Date</label>
                <input type="date" name="joining_date" id="joining_date" class="input" value="{{ old('joining_date') }}" required>
                @error('joining_date')
                    <span class="text-sm text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-span-2">
                <label for="address" class="text-sm font-medium mb-4" style="display:block;">Address</label>
                <textarea name="address" id="address" class="input" rows="3" required>{{ old('address') }}</textarea>
                @error('address')
                    <span class="text-sm text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-span-2 flex gap-4 mt-4">
                <button type="submit" class="btn btn-primary">Create Employee</button>
                <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
