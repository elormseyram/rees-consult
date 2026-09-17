@extends('admin.layouts.app')

@section('title', 'Staff & Employees')

@section('content')
<div class="admin-page-header d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
        <h1><i class="bi bi-people-fill me-2" style="color:#F8A706;"></i> Staff &amp; Employees</h1>
        <p class="text-muted mb-0">Create and manage admin &amp; employee accounts that can log in to this panel.</p>
    </div>
    <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus-fill me-1"></i> Add Employee
    </a>
</div>

<div class="card mt-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Added</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                    <tr>
                        <td class="ps-4 fw-semibold">
                            {{ $employee->name }}
                            @if($employee->id === auth()->id())
                                <span class="badge bg-light text-muted border ms-1">You</span>
                            @endif
                        </td>
                        <td class="text-muted">{{ $employee->email }}</td>
                        <td>
                            @if($employee->isAdmin())
                                <span class="badge bg-dark"><i class="bi bi-shield-lock-fill me-1"></i>Administrator</span>
                            @else
                                <span class="badge bg-info text-dark"><i class="bi bi-person-badge me-1"></i>Employee</span>
                            @endif
                        </td>
                        <td>
                            @if($employee->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Deactivated</span>
                            @endif
                        </td>
                        <td class="text-muted" style="font-size:.85rem;">{{ $employee->created_at?->format('M d, Y') }}</td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-action btn-sm btn-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            @if($employee->id !== auth()->id())
                                <form action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this staff account? They will no longer be able to log in.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-action btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-people d-block fs-1 mb-2"></i>
                            No staff accounts yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($employees->hasPages())
        <div class="card-footer bg-white border-top py-3">{{ $employees->links() }}</div>
    @endif
</div>
@endsection
