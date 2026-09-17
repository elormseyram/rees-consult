@extends('admin.layouts.app')

@section('title', 'Application Details - ' . $jobApplication->first_name . ' ' . $jobApplication->last_name)

@section('content')
<div class="admin-page-header">
    <a href="{{ route('admin.job-applications.index') }}" class="btn btn-sm btn-light border mb-3">
        <i class="bi bi-arrow-left"></i> Back to list
    </a>
    <h1>Job Application Profile</h1>
    <p class="text-muted">International job slot booking and candidate placement assessment.</p>
</div>

<div class="row mt-4">
    <div class="col-lg-8">
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
                    <div>
                        <h3 class="mb-1 fw-bold text-dark">{{ $jobApplication->first_name }} {{ $jobApplication->last_name }}</h3>
                        <p class="text-muted mb-0"><i class="bi bi-clock me-1"></i> Applied on {{ $jobApplication->created_at->format('F d, Y \a\t h:i A') }}</p>
                    </div>
                    <div>
                        @php
                            $badgeClass = match($jobApplication->status) {
                                'pending' => 'bg-warning text-dark',
                                'reviewing' => 'bg-info text-white',
                                'shortlisted' => 'bg-primary text-white',
                                'placed' => 'bg-success text-white',
                                'rejected' => 'bg-danger text-white',
                                default => 'bg-light text-dark'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }} text-uppercase px-3 py-2 fs-7 fw-semibold">
                            {{ $jobApplication->status }}
                        </span>
                    </div>
                </div>

                <h5 class="fw-bold mb-3 text-secondary">Contact Information</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="text-muted d-block small uppercase fw-semibold">Email Address</label>
                        <a href="mailto:{{ $jobApplication->email }}" class="text-dark fw-medium text-decoration-none">
                            {{ $jobApplication->email }} <i class="bi bi-box-arrow-up-right small ms-1 text-primary"></i>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted d-block small uppercase fw-semibold">Phone Number</label>
                        <a href="tel:{{ $jobApplication->phone }}" class="text-dark fw-medium text-decoration-none">
                            {{ $jobApplication->phone }} <i class="bi bi-telephone small ms-1 text-primary"></i>
                        </a>
                    </div>
                </div>

                <h5 class="fw-bold mb-3 text-secondary">Placement Details</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="text-muted d-block small uppercase fw-semibold">Applied Slot / Sector</label>
                        <span class="text-dark fw-bold fs-5">
                            {{ $jobApplication->service->title ?? 'N/A' }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted d-block small uppercase fw-semibold">Target Placement Country</label>
                        <span class="text-dark fw-bold fs-5 text-success">
                            {{ $jobApplication->service->country ?? 'N/A' }}
                        </span>
                    </div>
                </div>

                <h5 class="fw-bold mb-3 text-secondary">Candidate Qualifications</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="text-muted d-block small uppercase fw-semibold">Current Occupation / Role</label>
                        <span class="text-dark fw-medium fs-6">
                            {{ $jobApplication->current_occupation }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted d-block small uppercase fw-semibold">Years of Relevant Experience</label>
                        <span class="badge bg-dark-subtle text-dark border px-2 py-1 fs-6">
                            {{ $jobApplication->experience_years }} Years
                        </span>
                    </div>
                    <div class="col-md-12">
                        <label class="text-muted d-block small uppercase fw-semibold">Highest Completed Level of Education</label>
                        <span class="text-dark fw-medium">
                            {{ $jobApplication->highest_education }}
                        </span>
                    </div>
                </div>

                <h5 class="fw-bold mb-3 text-secondary">Uploaded Documentation</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-12">
                        <label class="text-muted d-block small uppercase fw-semibold">Professional Resume / CV</label>
                        @if($jobApplication->resume_path)
                            <a href="{{ asset('storage/' . $jobApplication->resume_path) }}" target="_blank" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center mt-1 gap-2">
                                <i class="bi bi-file-earmark-pdf-fill"></i> View Professional CV / Resume
                            </a>
                        @else
                            <span class="text-muted small">Not provided</span>
                        @endif
                    </div>
                </div>

                <h5 class="fw-bold mb-2 text-secondary">Candidate Profile Details / Experience Context</h5>
                <div class="bg-light p-3 rounded text-dark" style="white-space: pre-line;">
                    {{ $jobApplication->notes ?: 'No extra background info provided.' }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4 border-0 shadow-sm sticky-top" style="top: 2rem;">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="card-title fw-bold mb-0 text-dark">Status & Actions</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.job-applications.updateStatus', $jobApplication->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted">Update Process Status</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $jobApplication->status === 'pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="reviewing" {{ $jobApplication->status === 'reviewing' ? 'selected' : '' }}>Reviewing Profile</option>
                            <option value="shortlisted" {{ $jobApplication->status === 'shortlisted' ? 'selected' : '' }}>Shortlisted Candidate</option>
                            <option value="placed" {{ $jobApplication->status === 'placed' ? 'selected' : '' }}>Successfully Placed</option>
                            <option value="rejected" {{ $jobApplication->status === 'rejected' ? 'selected' : '' }}>Rejected / Closed</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted">Admin Office Notes</label>
                        <textarea name="notes" rows="4" class="form-select form-control" placeholder="Add candidate screening detail, interview slots, recruitment updates..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 fw-bold mb-2">
                        Update Job Profile
                    </button>
                </form>

                <div class="border-top pt-3 mt-3">
                    <form action="{{ route('admin.job-applications.destroy', $jobApplication->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this application record?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash"></i> Delete Job Application
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
