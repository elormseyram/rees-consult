@extends('admin.layouts.app')

@section('title', 'Standardized Test Signups')

@section('content')
<div class="admin-page-header d-flex align-items-center justify-content-between">
    <div>
        <h1>Standardized Test Signups</h1>
        <p class="text-muted">Manage client enrollments and tuition prep requests for exams like IELTS, TOEFL, etc.</p>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header bg-white py-3">
        <form method="GET" action="{{ route('admin.service-signups.index') }}" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Tuition</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="col-md-9 text-end">
                <a href="{{ route('admin.service-signups.index') }}" class="btn btn-sm btn-light border">Reset Filter</a>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Lead Info</th>
                    <th>Selected Test</th>
                    <th>Preferred Mode</th>
                    <th>Booked On</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($signups as $signup)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-semibold text-dark">{{ $signup->first_name }} {{ $signup->last_name }}</div>
                            <small class="text-muted d-block"><i class="bi bi-envelope"></i> {{ $signup->email }}</small>
                            <small class="text-muted d-block"><i class="bi bi-telephone"></i> {{ $signup->phone }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-primary border border-primary-subtle px-2 py-1 fw-bold">
                                {{ $signup->service->title ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <span class="text-capitalize">{{ $signup->preferred_mode }}</span>
                        </td>
                        <td>
                            {{ $signup->created_at->format('M d, Y h:i A') }}
                        </td>
                        <td>
                            @php
                                $badgeClass = match($signup->status) {
                                    'pending' => 'bg-warning text-dark',
                                    'contacted' => 'bg-info text-white',
                                    'active' => 'bg-success text-white',
                                    'completed' => 'bg-secondary text-white',
                                    default => 'bg-light text-dark'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} text-uppercase px-2.5 py-1.5" style="font-size: 0.75rem;">
                                {{ $signup->status }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.service-signups.show', $signup->id) }}" class="btn btn-action btn-sm btn-primary">
                                <i class="bi bi-eye"></i> View Detail
                            </a>
                            <form action="{{ route('admin.service-signups.destroy', $signup->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this signup record?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-action btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-clipboard2-x d-block fs-1 mb-2"></i>
                            No standardized test signups found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($signups->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $signups->links() }}
        </div>
    @endif
</div>
@endsection
