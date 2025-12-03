@extends('layouts.app')

@section('header')
Create User
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="text-lg font-semibold">New User</div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('users.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf

            <div>
                <label for="name" class="text-sm font-medium mb-4" style="display:block;">Name</label>
                <input type="text" id="name" name="name" class="input" value="{{ old('name') }}" required>
                @error('name')
                    <div class="text-sm text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="email" class="text-sm font-medium mb-4" style="display:block;">Email</label>
                <input type="email" id="email" name="email" class="input" value="{{ old('email') }}" required>
                @error('email')
                    <div class="text-sm text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="password" class="text-sm font-medium mb-4" style="display:block;">Password</label>
                <input type="password" id="password" name="password" class="input" required>
                @error('password')
                    <div class="text-sm text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="role" class="text-sm font-medium mb-4" style="display:block;">Role</label>
                <select id="role" name="role" class="input" required>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>Manager</option>
                    <option value="viewer" {{ old('role') === 'viewer' ? 'selected' : '' }}>Viewer</option>
                </select>
                @error('role')
                    <div class="text-sm text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="md:col-span-2 flex gap-4 mt-4">
                <button type="submit" class="btn btn-primary">Create User</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
