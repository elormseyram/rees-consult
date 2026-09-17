@extends('admin.layouts.app')

@section('title', 'Consultations')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-0">Consultations</h1>
            <p class="text-muted">Manage all consultation bookings</p>
        </div>
        <div class="col-md-6 text-end">
            <form action="{{ route('admin.consultations.index') }}" method="GET" class="d-inline-block">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.consultations.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Service</label>
                    <select name="service" class="form-select form-select-sm">
                        <option value="">All Services</option>
                        <option value="IELTS" {{ request('service') == 'IELTS' ? 'selected' : '' }}>IELTS</option>
                        <option value="GRE" {{ request('service') == 'GRE' ? 'selected' : '' }}>GRE</option>
                        <option value="SAT" {{ request('service') == 'SAT' ? 'selected' : '' }}>SAT</option>
                        <option value="TOEFL" {{ request('service') == 'TOEFL' ? 'selected' : '' }}>TOEFL</option>
                        <option value="OET" {{ request('service') == 'OET' ? 'selected' : '' }}>OET</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-sm me-2">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.consultations.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-circle me-1"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Consultations Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Service</th>
                            <th>Preferred Date</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($consultations as $consultation)
                        <tr>
                            <td class="fw-bold">#{{ $consultation->id }}</td>
                            <td>{{ $consultation->full_name }}</td>
                            <td>
                                <div class="small">
                                    <i class="bi bi-envelope me-1"></i>{{ $consultation->email }}<br>
                                    <i class="bi bi-telephone me-1"></i>{{ $consultation->phone }}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $consultation->service }}</span>
                            </td>
                            <td>
                                @if($consultation->preferred_date_time)
                                    {{ $consultation->preferred_date_time->format('M d, Y H:i') }}
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $consultation->status_color }}">
                                    {{ ucfirst($consultation->status) }}
                                </span>
                            </td>
                            <td>{{ $consultation->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.consultations.show', $consultation) }}" 
                                       class="btn btn-outline-primary" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.consultations.destroy', $consultation) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this consultation?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                No consultations found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($consultations->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $consultations->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
