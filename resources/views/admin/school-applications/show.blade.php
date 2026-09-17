@extends('admin.layouts.app')

@section('title', 'Application Details - ' . $schoolApplication->first_name . ' ' . $schoolApplication->last_name)

@section('content')
<div class="admin-page-header">
    <a href="{{ route('admin.school-applications.index') }}" class="btn btn-sm btn-light border mb-3">
        <i class="bi bi-arrow-left"></i> Back to list
    </a>
    <h1>School Application Profile</h1>
    <p class="text-muted">Student qualification, course interest, and academic application records.</p>
</div>

<div class="row mt-4">
    <div class="col-lg-8">
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
                    <div>
                        <h3 class="mb-1 fw-bold text-dark">{{ $schoolApplication->first_name }} {{ $schoolApplication->last_name }}</h3>
                        <p class="text-muted mb-0"><i class="bi bi-clock me-1"></i> Received on {{ $schoolApplication->created_at->format('F d, Y \a\t h:i A') }}</p>
                    </div>
                    <div>
                        @php
                            $badgeClass = match($schoolApplication->status) {
                                'pending' => 'bg-warning text-dark',
                                'reviewing' => 'bg-info text-white',
                                'accepted' => 'bg-success text-white',
                                'closed' => 'bg-secondary text-white',
                                default => 'bg-light text-dark'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }} text-uppercase px-3 py-2 fs-7 fw-semibold">
                            {{ $schoolApplication->status }}
                        </span>
                    </div>
                </div>

                <h5 class="fw-bold mb-3 text-secondary">Contact Information</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="text-muted d-block small uppercase fw-semibold">Email Address</label>
                        <a href="mailto:{{ $schoolApplication->email }}" class="text-dark fw-medium text-decoration-none">
                            {{ $schoolApplication->email }} <i class="bi bi-box-arrow-up-right small ms-1 text-primary"></i>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted d-block small uppercase fw-semibold">Phone Number</label>
                        <a href="tel:{{ $schoolApplication->phone }}" class="text-dark fw-medium text-decoration-none">
                            {{ $schoolApplication->phone }} <i class="bi bi-telephone small ms-1 text-primary"></i>
                        </a>
                    </div>
                </div>

                <h5 class="fw-bold mb-3 text-secondary">Academic Preferences</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="text-muted d-block small uppercase fw-semibold">Course of Interest</label>
                        <span class="text-dark fw-bold fs-5">
                            {{ $schoolApplication->course_of_interest }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted d-block small uppercase fw-semibold">Target Level of Study</label>
                        <span class="text-dark fw-medium text-capitalize">
                            {{ $schoolApplication->level_of_education }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted d-block small uppercase fw-semibold">Target Countries</label>
                        <span class="text-dark fw-medium">
                            {{ $schoolApplication->target_countries }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted d-block small uppercase fw-semibold">Has Valid Passport?</label>
                        <span class="badge {{ $schoolApplication->has_passport ? 'bg-success-subtle text-success border border-success' : 'bg-danger-subtle text-danger border border-danger' }} px-2 py-1">
                            {{ $schoolApplication->has_passport ? 'Yes' : 'No' }}
                        </span>
                    </div>
                </div>

                <h5 class="fw-bold mb-3 text-secondary">Qualifications & Budget</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="text-muted d-block small uppercase fw-semibold">Highest Educational Qualification</label>
                        <span class="text-dark fw-medium">
                            {{ $schoolApplication->highest_qualification }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted d-block small uppercase fw-semibold">Financial Budget Category</label>
                        <span class="text-dark fw-medium">
                            {{ $schoolApplication->budget }}
                        </span>
                    </div>
                </div>

                <h5 class="fw-bold mb-3 text-secondary">Uploaded Documents</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="text-muted d-block small uppercase fw-semibold">CV / Academic Resume</label>
                        @if($schoolApplication->resume_path)
                            <a href="{{ asset('storage/' . $schoolApplication->resume_path) }}" target="_blank" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center mt-1 gap-2">
                                <i class="bi bi-file-earmark-pdf-fill"></i> View Academic CV / Resume
                            </a>
                        @else
                            <span class="text-muted small">Not provided</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted d-block small uppercase fw-semibold">Academic Transcript</label>
                        @if($schoolApplication->transcript_path)
                            <a href="{{ asset('storage/' . $schoolApplication->transcript_path) }}" target="_blank" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center mt-1 gap-2">
                                <i class="bi bi-file-earmark-ruled-fill"></i> View Transcript
                            </a>
                        @else
                            <span class="text-muted small">Not provided</span>
                        @endif
                    </div>
                </div>

                <h5 class="fw-bold mb-2 text-secondary">Lead Context / Statement</h5>
                <div class="bg-light p-3 rounded text-dark" style="white-space: pre-line;">
                    {{ $schoolApplication->notes ?: 'No extra notes provided by the student.' }}
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
                <form action="{{ route('admin.school-applications.updateStatus', $schoolApplication->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted">Update Process Status</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $schoolApplication->status === 'pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="reviewing" {{ $schoolApplication->status === 'reviewing' ? 'selected' : '' }}>Reviewing Profile</option>
                            <option value="accepted" {{ $schoolApplication->status === 'accepted' ? 'selected' : '' }}>Admission Secured</option>
                            <option value="closed" {{ $schoolApplication->status === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted">Admin Office Notes</label>
                        <textarea name="notes" rows="4" class="form-select form-control" placeholder="Add educational consulting details, candidate tracking updates..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 fw-bold mb-2">
                        Update Application Details
                    </button>
                </form>

                <div class="border-top pt-3 mt-3">
                    <form action="{{ route('admin.school-applications.destroy', $schoolApplication->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this application record?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash"></i> Delete Student Application
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
