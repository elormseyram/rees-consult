@extends('admin.layouts.app')

@section('title', 'Job Placement Applications')

@section('content')
<div class="admin-page-header d-flex align-items-center justify-content-between">
    <div>
        <h1>Job Placement Applications</h1>
        <p class="text-muted">Review international career leads, occupational experience, and candidate resumes.</p>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header bg-white py-3">
        <form method="GET" action="{{ route('admin.job-applications.index') }}" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Review</option>
                    <option value="reviewing" {{ request('status') === 'reviewing' ? 'selected' : '' }}>Reviewing Profile</option>
                    <option value="shortlisted" {{ request('status') === 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                    <option value="placed" {{ request('status') === 'placed' ? 'selected' : '' }}>Successfully Placed</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-9 text-end">
                <a href="{{ route('admin.job-applications.index') }}" class="btn btn-sm btn-light border">Reset Filter</a>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Candidate Lead</th>
                    <th>Target Role / Country</th>
                    <th>Current Occupation</th>
                    <th>Experience</th>
                    <th>Booked On</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-semibold text-dark">{{ $app->first_name }} {{ $app->last_name }}</div>
                            <small class="text-muted d-block"><i class="bi bi-envelope"></i> {{ $app->email }}</small>
                            <small class="text-muted d-block"><i class="bi bi-telephone"></i> {{ $app->phone }}</small>
                        </td>
                        <td>
                            <div>
                                <span class="fw-semibold text-primary">{{ $app->service->title ?? 'N/A' }}</span>
                                <small class="text-muted d-block">Country: {{ $app->service->country ?? 'N/A' }}</small>
                            </div>
                        </td>
                        <td>
                            {{ $app->current_occupation }}
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2 py-1">
                                {{ $app->experience_years }} Years
                            </span>
                        </td>
                        <td>
                            {{ $app->created_at->format('M d, Y h:i A') }}
                        </td>
                        <td>
                            @php
                                $badgeClass = match($app->status) {
                                    'pending' => 'bg-warning text-dark',
                                    'reviewing' => 'bg-info text-white',
                                    'shortlisted' => 'bg-primary text-white',
                                    'placed' => 'bg-success text-white',
                                    'rejected' => 'bg-danger text-white',
                                    default => 'bg-light text-dark'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} text-uppercase px-2.5 py-1.5" style="font-size: 0.75rem;">
                                {{ $app->status }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.job-applications.show', $app->id) }}" class="btn btn-action btn-sm btn-primary">
                                <i class="bi bi-eye"></i> View Detail
                            </a>
                            <form action="{{ route('admin.job-applications.destroy', $app->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this job application record?')">
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
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-clipboard2-x d-block fs-1 mb-2"></i>
                            No job applications found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($applications->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $applications->links() }}
        </div>
    @endif
</div>
@endsection
