@extends('admin.layouts.app')

@section('title', 'Signup Details - ' . $serviceSignup->first_name . ' ' . $serviceSignup->last_name)

@section('content')
<div class="admin-page-header">
    <a href="{{ route('admin.service-signups.index') }}" class="btn btn-sm btn-light border mb-3">
        <i class="bi bi-arrow-left"></i> Back to list
    </a>
    <h1>Signup Details</h1>
    <p class="text-muted">Lead profile and signup requirements for tuition & test registration</p>
</div>

<div class="row mt-4">
    <div class="col-lg-8">
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
                    <div>
                        <h3 class="mb-1 fw-bold text-dark">{{ $serviceSignup->first_name }} {{ $serviceSignup->last_name }}</h3>
                        <p class="text-muted mb-0"><i class="bi bi-clock me-1"></i> Registered on {{ $serviceSignup->created_at->format('F d, Y \a\t h:i A') }}</p>
                    </div>
                    <div>
                        @php
                            $badgeClass = match($serviceSignup->status) {
                                'pending' => 'bg-warning text-dark',
                                'contacted' => 'bg-info text-white',
                                'active' => 'bg-success text-white',
                                'completed' => 'bg-secondary text-white',
                                default => 'bg-light text-dark'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }} text-uppercase px-3 py-2 fs-7 fw-semibold">
                            {{ $serviceSignup->status }}
                        </span>
                    </div>
                </div>

                <h5 class="fw-bold mb-3 text-secondary">Contact Information</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="text-muted d-block small uppercase fw-semibold">Email Address</label>
                        <a href="mailto:{{ $serviceSignup->email }}" class="text-dark fw-medium text-decoration-none">
                            {{ $serviceSignup->email }} <i class="bi bi-box-arrow-up-right small ms-1 text-primary"></i>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted d-block small uppercase fw-semibold">Phone Number</label>
                        <a href="tel:{{ $serviceSignup->phone }}" class="text-dark fw-medium text-decoration-none">
                            {{ $serviceSignup->phone }} <i class="bi bi-telephone small ms-1 text-primary"></i>
                        </a>
                    </div>
                </div>

                <h5 class="fw-bold mb-3 text-secondary">Tuition & Test Details</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="text-muted d-block small uppercase fw-semibold">Selected Course / Test</label>
                        <span class="fw-bold text-dark fs-5">
                            {{ $serviceSignup->service->title ?? 'N/A' }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted d-block small uppercase fw-semibold">Preferred Mode of Learning</label>
                        <span class="text-dark fw-medium text-capitalize">
                            {{ $serviceSignup->preferred_mode }}
                        </span>
                    </div>
                    <div class="col-md-12">
                        <label class="text-muted d-block small uppercase fw-semibold">Candidate's Motivation / Extra Details</label>
                        <div class="bg-light p-3 rounded text-dark mt-1" style="white-space: pre-line;">
                            {{ $serviceSignup->notes ?: 'No extra motivation or details provided.' }}
                        </div>
                    </div>
                </div>

                <h5 class="fw-bold mb-3 text-secondary">Service Package Pricing (Reference)</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted d-block small uppercase fw-semibold">Tuition & Prep Price</label>
                        <span class="fw-bold text-dark">
                            ${{ number_format($serviceSignup->service->price, 2) }} 
                            <span class="text-muted small">(${{ number_format($serviceSignup->service->price * 12, 2) }} GHS)</span>
                        </span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted d-block small uppercase fw-semibold">Exams Booking / Processing Fee</label>
                        <span class="fw-bold text-dark">
                            ${{ number_format($serviceSignup->service->processing_fee, 2) }}
                            <span class="text-muted small">(${{ number_format($serviceSignup->service->processing_fee * 12, 2) }} GHS)</span>
                        </span>
                    </div>
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
                <form action="{{ route('admin.service-signups.updateStatus', $serviceSignup->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted">Update Progress Status</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $serviceSignup->status === 'pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="contacted" {{ $serviceSignup->status === 'contacted' ? 'selected' : '' }}>Contacted Lead</option>
                            <option value="active" {{ $serviceSignup->status === 'active' ? 'selected' : '' }}>Active Class / tuition</option>
                            <option value="completed" {{ $serviceSignup->status === 'completed' ? 'selected' : '' }}>Completed / Passed</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted">Admin Office Notes</label>
                        <textarea name="notes" rows="4" class="form-select form-control" placeholder="Add administrative details, call log notes, intake details..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 fw-bold mb-2">
                        Update Details
                    </button>
                </form>

                <div class="border-top pt-3 mt-3">
                    <form action="{{ route('admin.service-signups.destroy', $serviceSignup->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this lead record?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash"></i> Delete Signup Lead
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
