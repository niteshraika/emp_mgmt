@extends('layouts.app')

@section('header')
Department Details
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="text-lg font-semibold">{{ $department->name }}</div>
    </div>
    <div class="card-body">
        <dl class="grid grid-cols-1 gap-3">
            <div>
                <dt class="text-gray">Name</dt>
                <dd>{{ $department->name }}</dd>
            </div>
            <div>
                <dt class="text-gray">Description</dt>
                <dd>{{ $department->description ?? '—' }}</dd>
            </div>
        </dl>
    </div>
</div>
@endsection
