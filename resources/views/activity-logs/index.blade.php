@extends('layouts.app')

@section('header', 'Activity Log')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="text-lg font-medium">Activity Log</h3>
    </div>
    
    <!-- Filters -->
    <div class="card-body" style="border-bottom: 1px solid var(--border); padding: 0.875rem 1.5rem;">
        <form method="GET" action="{{ route('activity-logs.index') }}" class="flex gap-2 flex-wrap items-end">
            <div style="flex: 1; min-width: 160px;">
                <label for="model_type" class="text-xs font-semibold mb-1 text-gray" style="display: block; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.7rem;">Model Type</label>
                <select name="model_type" id="model_type" class="input" style="font-size: 0.875rem; padding: 0.375rem 0.5rem;">
                    <option value="">All</option>
                    <option value="Employee" {{ request('model_type') === 'Employee' ? 'selected' : '' }}>Employee</option>
                    <option value="Department" {{ request('model_type') === 'Department' ? 'selected' : '' }}>Department</option>
                </select>
            </div>
            
            <div style="flex: 1; min-width: 160px;">
                <label for="action" class="text-xs font-semibold mb-1 text-gray" style="display: block; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.7rem;">Action</label>
                <select name="action" id="action" class="input" style="font-size: 0.875rem; padding: 0.375rem 0.5rem;">
                    <option value="">All</option>
                    <option value="created" {{ request('action') === 'created' ? 'selected' : '' }}>Created</option>
                    <option value="updated" {{ request('action') === 'updated' ? 'selected' : '' }}>Updated</option>
                    <option value="deleted" {{ request('action') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                    <option value="restored" {{ request('action') === 'restored' ? 'selected' : '' }}>Restored</option>
                </select>
            </div>
            
            <div style="flex: 1; min-width: 160px;">
                <label for="date_from" class="text-xs font-semibold mb-1 text-gray" style="display: block; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.7rem;">From Date</label>
                <input type="date" name="date_from" id="date_from" class="input" value="{{ request('date_from') }}" style="font-size: 0.875rem; padding: 0.375rem 0.5rem;">
            </div>
            
            <div style="flex: 1; min-width: 160px;">
                <label for="date_to" class="text-xs font-semibold mb-1 text-gray" style="display: block; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.7rem;">To Date</label>
                <input type="date" name="date_to" id="date_to" class="input" value="{{ request('date_to') }}" style="font-size: 0.875rem; padding: 0.375rem 0.5rem;">
            </div>
            
            <div class="flex gap-1" style="gap: 0.375rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.375rem 0.75rem; font-size: 0.8125rem;">Filter</button>
                <a href="{{ route('activity-logs.index') }}" class="btn btn-secondary" style="padding: 0.375rem 0.75rem; font-size: 0.8125rem;">Clear</a>
            </div>
        </form>
    </div>
    
    <div class="card-body p-0">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Model</th>
                        <th>User</th>
                        <th>Date/Time</th>
                        <th style="width: 80px;">View</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>
                            @php
                                $colors = [
                                    'created' => 'rgba(34, 197, 94, 0.12)',
                                    'updated' => 'rgba(59, 130, 246, 0.12)',
                                    'deleted' => 'rgba(239, 68, 68, 0.12)',
                                    'restored' => 'rgba(168, 85, 247, 0.12)',
                                ];
                                $textColors = [
                                    'created' => '#22c55e',
                                    'updated' => '#3b82f6',
                                    'deleted' => '#ef4444',
                                    'restored' => '#a855f7',
                                ];
                            @endphp
                            <span style="background-color: {{ $colors[$log->action] ?? 'var(--bg)' }}; color: {{ $textColors[$log->action] ?? 'var(--text)' }}; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 500; text-transform: capitalize;">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="text-gray" style="font-size: 0.875rem;">{{ $log->model_type }} #{{ $log->model_id }}</td>
                        <td class="text-gray" style="font-size: 0.875rem;">{{ $log->user?->email ?? 'System' }}</td>
                        <td class="text-gray" style="font-size: 0.875rem;">{{ $log->created_at->format('M d, Y H:i') }}</td>
                        <td>
                            <a href="{{ route('activity-logs.show', $log) }}" class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray">No activity logs found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    @if($logs->hasPages())
    <div class="card-body" style="border-top: 1px solid var(--border); padding: 0.875rem 1.5rem;">
        {{ $logs->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>
@endsection
