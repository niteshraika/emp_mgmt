@extends('layouts.app')

@section('header')
My Profile
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="text-lg font-semibold">Update Profile</div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('profile.update') }}" class="flex flex-col gap-6" style="max-width: 640px;">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="text-sm font-medium mb-2" style="display:block;">Name</label>
                <input type="text" id="name" name="name" class="input" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <div class="text-sm text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="email" class="text-sm font-medium mb-2" style="display:block;">Email</label>
                <input type="email" id="email" name="email" class="input" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="text-sm text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="password" class="text-sm font-medium mb-2" style="display:block;">New Password <span class="text-gray text-sm">(leave blank to keep current)</span></label>
                <input type="password" id="password" name="password" class="input">
                @error('password')
                    <div class="text-sm text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="text-sm font-medium mb-2" style="display:block;">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="input">
            </div>

            <div class="flex gap-4 mt-2">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
