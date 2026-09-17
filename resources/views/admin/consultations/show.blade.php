@extends('admin.layouts.app')

@section('title', 'Consultation Details')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-0">Consultation #{{ $consultation->id }}</h1>
            <p class="text-muted">View and manage consultation details</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.consultations.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Consultation Details -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">Consultation Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small text-muted">First Name</label>
                            <p class="mb-0 fw-semibold">{{ $consultation->first_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted">Last Name</label>
                            <p class="mb-0 fw-semibold">{{ $consultation->last_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted">Email</label>
                            <p class="mb-0">
                                <a href="mailto:{{ $consultation->email }}">{{ $consultation->email }}</a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted">Phone</label>
                            <p class="mb-0">
                                <a href="tel:{{ $consultation->phone }}">{{ $consultation->phone }}</a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted">Service</label>
                            <p class="mb-0">
                                <span class="badge bg-info">{{ $consultation->service }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted">Preferred Date & Time</label>
                            <p class="mb-0">
                                @if($consultation->preferred_date_time)
                                    {{ $consultation->preferred_date_time->format('F d, Y \a\t H:i') }}
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-12">
                            <label class="small text-muted">Message</label>
                            <p class="mb-0">
                                @if($consultation->message)
                                    {{ $consultation->message }}
                                @else
                                    <span class="text-muted">No message provided</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted">Created At</label>
                            <p class="mb-0">{{ $consultation->created_at->format('F d, Y \a\t H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted">Last Updated</label>
                            <p class="mb-0">{{ $consultation->updated_at->format('F d, Y \a\t H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            @if($consultation->payment)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">Payment Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small text-muted">Payment Method</label>
                            <p class="mb-0 fw-semibold">{{ $consultation->payment->payment_method }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted">Provider</label>
                            <p class="mb-0">{{ $consultation->payment->provider ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted">Amount</label>
                            <p class="mb-0 fw-semibold">
                                {{ $consultation->payment->currency }} {{ number_format($consultation->payment->amount, 2) }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted">Status</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ $consultation->payment->status_color }}">
                                    {{ ucfirst($consultation->payment->status) }}
                                </span>
                            </p>
                        </div>
                        @if($consultation->payment->screenshot)
                        <div class="col-12">
                            <label class="small text-muted">Payment Screenshot</label>
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $consultation->payment->screenshot) }}" 
                                     alt="Payment Screenshot" 
                                     class="img-fluid rounded" 
                                     style="max-height: 300px;">
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Actions Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">Update Status</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.consultations.update-status', $consultation) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label">Current Status</label>
                            <p class="mb-2">
                                <span class="badge bg-{{ $consultation->status_color }} fs-6">
                                    {{ ucfirst($consultation->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Status</label>
                            <select name="status" class="form-select" required>
                                <option value="pending" {{ $consultation->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $consultation->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="completed" {{ $consultation->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $consultation->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle me-2"></i>Update Status
                        </button>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm border-danger">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 text-danger">Danger Zone</h5>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-3">
                        Deleting this consultation is permanent and cannot be undone.
                    </p>
                    <form action="{{ route('admin.consultations.destroy', $consultation) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this consultation? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-trash me-2"></i>Delete Consultation
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
