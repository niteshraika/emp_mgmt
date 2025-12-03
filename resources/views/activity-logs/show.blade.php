@extends('layouts.app')

@section('header', 'Activity Log Details')

@section('content')
<div class="card">
    <div class="card-header flex justify-between items-center">
        <h3 class="text-lg font-medium">Activity Log Details</h3>
        <a href="{{ route('activity-logs.index') }}" class="btn btn-secondary" style="padding: 0.375rem 0.75rem; font-size: 0.8125rem;">← Back</a>
    </div>
    
    <div class="card-body">
        <div style="display: grid; gap: 1.5rem;">
            <!-- Header Info -->
            <div>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                    <div>
                        <h4 class="text-xs font-semibold text-gray" style="text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Action</h4>
                        <p style="font-weight: 500; text-transform: capitalize;">{{ $activityLog->action }}</p>
                    </div>
                    
                    <div>
                        <h4 class="text-xs font-semibold text-gray" style="text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Model</h4>
                        <p style="font-weight: 500;">{{ $activityLog->model_type }} #{{ $activityLog->model_id }}</p>
                    </div>
                    
                    <div>
                        <h4 class="text-xs font-semibold text-gray" style="text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">User</h4>
                        <p style="font-weight: 500;">{{ $activityLog->user?->email ?? 'System' }}</p>
                    </div>
                    
                    <div>
                        <h4 class="text-xs font-semibold text-gray" style="text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Date/Time</h4>
                        <p style="font-weight: 500;">{{ $activityLog->created_at->format('M d, Y H:i:s') }}</p>
                    </div>
                    
                    <div>
                        <h4 class="text-xs font-semibold text-gray" style="text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">IP Address</h4>
                        <p style="font-weight: 500;">{{ $activityLog->ip_address ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Changes -->
            @if($activityLog->changes)
            <div style="border-top: 1px solid var(--border); padding-top: 1.5rem;">
                <h4 class="text-sm font-semibold" style="margin-bottom: 1rem;">Changes Made</h4>
                <div style="background-color: var(--bg); border: 1px solid var(--border); border-radius: 0.375rem; padding: 1rem; overflow-x: auto;">
                    <table style="width: 100%; font-size: 0.875rem;">
                        <thead>
                            <tr>
                                <th style="text-align: left; padding: 0.5rem; border-bottom: 1px solid var(--border);">Field</th>
                                <th style="text-align: left; padding: 0.5rem; border-bottom: 1px solid var(--border);">Old Value</th>
                                <th style="text-align: left; padding: 0.5rem; border-bottom: 1px solid var(--border);">New Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activityLog->changes as $field => $change)
                            <tr>
                                <td style="padding: 0.5rem; border-bottom: 1px solid var(--border); font-weight: 500;">{{ $field }}</td>
                                <td style="padding: 0.5rem; border-bottom: 1px solid var(--border); color: #ef4444;">
                                    <code>{{ is_array($change['old']) ? json_encode($change['old']) : $change['old'] }}</code>
                                </td>
                                <td style="padding: 0.5rem; border-bottom: 1px solid var(--border); color: #22c55e;">
                                    <code>{{ is_array($change['new']) ? json_encode($change['new']) : $change['new'] }}</code>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            
            <!-- User Agent -->
            @if($activityLog->user_agent)
            <div style="border-top: 1px solid var(--border); padding-top: 1.5rem;">
                <h4 class="text-xs font-semibold text-gray" style="text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">User Agent</h4>
                <p class="text-gray" style="font-size: 0.8rem; word-break: break-all;">{{ $activityLog->user_agent }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
