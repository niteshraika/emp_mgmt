@extends('layouts.app')

@section('header', 'Employees')

@section('content')
<div class="card">
    <div class="card-header flex justify-between items-center">
        <h3 class="text-lg font-medium">Employees</h3>
        <div class="flex gap-2" style="gap: 0.5rem;">
            <a href="{{ route('employees.export', request()->query()) }}" class="btn btn-secondary" style="padding: 0.375rem 0.75rem; font-size: 0.8125rem;">↓ Export CSV</a>
            <a href="{{ route('employees.import') }}" class="btn btn-secondary" style="padding: 0.375rem 0.75rem; font-size: 0.8125rem;">↑ Import CSV</a>
            <a href="{{ route('employees.create') }}" class="btn btn-primary" style="padding: 0.375rem 0.75rem; font-size: 0.8125rem;">+ Add</a>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="card-body" style="border-bottom: 1px solid var(--border); padding: 0.875rem 1.5rem;">
        <form method="GET" action="{{ route('employees.index') }}" class="flex gap-2 flex-wrap items-end">
            <div style="flex: 1; min-width: 160px;">
                <label for="search" class="text-xs font-semibold mb-1 text-gray" style="display: block; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.7rem;">Search</label>
                <input type="text" name="search" id="search" class="input" placeholder="Name or email..." value="{{ request('search') }}" style="font-size: 0.875rem; padding: 0.375rem 0.5rem;">
            </div>
            
            <div style="flex: 1; min-width: 160px;">
                <label for="department_id" class="text-xs font-semibold mb-1 text-gray" style="display: block; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.7rem;">Department</label>
                <select name="department_id" id="department_id" class="input" style="font-size: 0.875rem; padding: 0.375rem 0.5rem;">
                    <option value="">All</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div style="flex: 1; min-width: 160px;">
                <label for="salary_min" class="text-xs font-semibold mb-1 text-gray" style="display: block; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.7rem;">Min Salary</label>
                <input type="number" name="salary_min" id="salary_min" class="input" placeholder="0.00" step="0.01" value="{{ request('salary_min') }}" style="font-size: 0.875rem; padding: 0.375rem 0.5rem;">
            </div>
            
            <div style="flex: 1; min-width: 160px;">
                <label for="salary_max" class="text-xs font-semibold mb-1 text-gray" style="display: block; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.7rem;">Max Salary</label>
                <input type="number" name="salary_max" id="salary_max" class="input" placeholder="999999.99" step="0.01" value="{{ request('salary_max') }}" style="font-size: 0.875rem; padding: 0.375rem 0.5rem;">
            </div>
            
            <div style="flex: 1; min-width: 160px;">
                <label for="joining_date_from" class="text-xs font-semibold mb-1 text-gray" style="display: block; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.7rem;">From Date</label>
                <input type="date" name="joining_date_from" id="joining_date_from" class="input" value="{{ request('joining_date_from') }}" style="font-size: 0.875rem; padding: 0.375rem 0.5rem;">
            </div>
            
            <div style="flex: 1; min-width: 160px;">
                <label for="joining_date_to" class="text-xs font-semibold mb-1 text-gray" style="display: block; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.7rem;">To Date</label>
                <input type="date" name="joining_date_to" id="joining_date_to" class="input" value="{{ request('joining_date_to') }}" style="font-size: 0.875rem; padding: 0.375rem 0.5rem;">
            </div>
            
            <div class="flex gap-1" style="gap: 0.375rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.375rem 0.75rem; font-size: 0.8125rem;">Filter</button>
                <a href="{{ route('employees.index') }}" class="btn btn-secondary" style="padding: 0.375rem 0.75rem; font-size: 0.8125rem;">Clear</a>
            </div>
        </form>
    </div>

    <!-- Bulk Actions Form (hidden by default) -->
    <form id="bulkActionsForm" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="ids" id="bulkIds">
    </form>
    
    <!-- Bulk Actions Bar (shown when items selected) -->
    <div id="bulkActionsBar" style="display: none; padding: 0.875rem 1.5rem; border-bottom: 1px solid var(--border); background-color: rgba(79, 70, 229, 0.08);">
        <div class="flex gap-2 items-center justify-between flex-wrap">
            <span class="text-sm font-medium">
                <span id="selectedCount">0</span> employee(s) selected
            </span>
            <div class="flex gap-2" style="gap: 0.5rem;">
                <button type="button" id="bulkExportBtn" class="btn btn-secondary" style="padding: 0.375rem 0.75rem; font-size: 0.8125rem;">↓ Export Selected</button>
                <button type="button" id="bulkDeleteBtn" class="btn btn-danger" style="padding: 0.375rem 0.75rem; font-size: 0.8125rem;" onclick="return confirm('Delete selected employees? This cannot be undone.');">🗑 Delete Selected</button>
                <button type="button" id="clearSelectionBtn" class="btn btn-secondary" style="padding: 0.375rem 0.75rem; font-size: 0.8125rem;">Clear Selection</button>
            </div>
        </div>
    </div>
    
    <div class="card-body p-0">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 30px;">
                            <input type="checkbox" id="selectAllCheckbox" style="cursor: pointer;">
                        </th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Phone</th>
                        <th style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                    <tr>
                        <td style="text-align: center;">
                            <input type="checkbox" class="employeeCheckbox" value="{{ $employee->id }}" style="cursor: pointer;">
                        </td>
                        <td class="font-medium"><a href="{{ route('employees.show', $employee) }}" class="text-primary">{{ $employee->first_name }} {{ $employee->last_name }}</a></td>
                        <td class="text-gray" style="font-size: 0.875rem;">{{ $employee->email }}</td>
                        <td>
                            <span style="background-color: rgba(79, 70, 229, 0.12); color: var(--primary); padding: 0.2rem 0.4rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 500;">
                                {{ $employee->department?->name ?? '—' }}
                            </span>
                        </td>
                        <td class="text-gray" style="font-size: 0.875rem;">{{ $employee->phone ?? '—' }}</td>
                        <td>
                            <div class="flex gap-1" style="gap: 0.25rem;">
                                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                                <form action="{{ route('employees.destroy', $employee) }}" method="POST" onsubmit="return confirm('Delete this employee?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Del</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray">No employees found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    @if($employees->hasPages())
    <div class="card-body" style="border-top: 1px solid var(--border); padding: 0.875rem 1.5rem;">
        {{ $employees->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>

<script>
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const employeeCheckboxes = document.querySelectorAll('.employeeCheckbox');
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectedCountSpan = document.getElementById('selectedCount');
    const bulkExportBtn = document.getElementById('bulkExportBtn');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const clearSelectionBtn = document.getElementById('clearSelectionBtn');
    const bulkActionsForm = document.getElementById('bulkActionsForm');
    const bulkIds = document.getElementById('bulkIds');
    
    function updateBulkActionsBar() {
        const selectedIds = Array.from(employeeCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);
        
        if (selectedIds.length > 0) {
            bulkActionsBar.style.display = 'block';
            selectedCountSpan.textContent = selectedIds.length;
            bulkIds.value = JSON.stringify(selectedIds);
        } else {
            bulkActionsBar.style.display = 'none';
        }
        
        // Update select all checkbox state
        selectAllCheckbox.checked = selectedIds.length === employeeCheckboxes.length && employeeCheckboxes.length > 0;
    }
    
    selectAllCheckbox.addEventListener('change', function() {
        employeeCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActionsBar();
    });
    
    employeeCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActionsBar);
    });
    
    bulkExportBtn.addEventListener('click', function() {
        const selectedIds = Array.from(employeeCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);
        
        if (selectedIds.length === 0) {
            alert('Please select at least one employee.');
            return;
        }
        
        bulkIds.value = JSON.stringify(selectedIds);
        bulkActionsForm.action = '{{ route("employees.bulk-export") }}';
        bulkActionsForm.submit();
    });
    
    bulkDeleteBtn.addEventListener('click', function() {
        const selectedIds = Array.from(employeeCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);
        
        if (selectedIds.length === 0) {
            alert('Please select at least one employee.');
            return;
        }
        
        bulkIds.value = JSON.stringify(selectedIds);
        bulkActionsForm.action = '{{ route("employees.bulk-delete") }}';
        bulkActionsForm.submit();
    });
    
    clearSelectionBtn.addEventListener('click', function() {
        employeeCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateBulkActionsBar();
    });
</script>
@endsection

