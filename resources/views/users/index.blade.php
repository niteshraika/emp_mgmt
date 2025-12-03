@extends('layouts.app')

@section('header')
Users
@endsection

@section('content')
<div class="card">
    <div class="card-header flex justify-between items-center">
        <div class="text-lg font-semibold">All Users</div>
        @if(auth()->user()->canCreateUsers())
            <a href="{{ route('users.create') }}" class="btn btn-primary">Create User</a>
        @endif
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge">{{ ucfirst($user->role) }}</span></td>
                            <td>{{ $user->created_at?->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
