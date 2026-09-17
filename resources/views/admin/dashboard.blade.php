@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="admin-page-header">
        <h1><i class="bi bi-grid-1x2-fill me-2" style="color: #F8A706;"></i> Dashboard</h1>
        <p>Welcome back, <strong>{{ auth()->user()->name }}</strong>! Here's a quick summary.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: #fff3e0;">
                            <i class="bi bi-calendar-check-fill fs-5" style="color: #F8A706;"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Consultations</div>
                            <div class="fs-4 fw-bold">{{ $stats['total_consultations'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: #fef3c7;">
                            <i class="bi bi-clock-history fs-5 text-warning"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Pending</div>
                            <div class="fs-4 fw-bold">{{ $stats['pending_consultations'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: #dbeafe;">
                            <i class="bi bi-check-circle-fill fs-5 text-primary"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Confirmed</div>
                            <div class="fs-4 fw-bold">{{ $stats['confirmed_consultations'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: #d1fae5;">
                            <i class="bi bi-check-all fs-5 text-success"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Completed</div>
                            <div class="fs-4 fw-bold">{{ $stats['completed_consultations'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Apply Now Leads highlight -->
    <div class="row g-3 mb-4">
        <div class="col-md-4 col-12">
            <div class="card border-0" style="background: linear-gradient(135deg, #b45309 0%, #F8A706 100%); color:white;">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;background:rgba(255,255,255,0.2);">
                            <i class="bi bi-fire fs-5 text-white"></i>
                        </div>
                        <div>
                            <div class="text-white-50 small fw-semibold text-uppercase" style="font-size:0.7rem;letter-spacing:0.5px;">Apply Now Leads</div>
                            <div class="fs-3 fw-bold">{{ $stats['total_leads'] }}</div>
                            <a href="{{ route('admin.leads.index') }}" class="text-white-50 small text-decoration-none d-block mt-1">Manage Leads <i class="bi bi-arrow-right-short"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-6">
            <div class="card border-0 h-100"><div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;background:#d1fae5;">
                    <i class="bi bi-fire fs-5 text-success"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold text-uppercase" style="font-size:0.7rem;">Hot Leads</div>
                    <div class="fs-4 fw-bold">{{ $stats['hot_leads'] }}</div>
                </div>
            </div></div>
        </div>
        <div class="col-md-4 col-6">
            <div class="card border-0 h-100"><div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;background:#dbeafe;">
                    <i class="bi bi-stars fs-5 text-primary"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold text-uppercase" style="font-size:0.7rem;">New / Unworked</div>
                    <div class="fs-4 fw-bold">{{ $stats['new_leads'] }}</div>
                </div>
            </div></div>
        </div>
    </div>

    <!-- Charts & analytics -->
    <div class="mb-4">
        @include('admin.partials.dashboard-charts')
    </div>

    <!-- Recent Apply Now Leads -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">Recent Apply Now Leads</h5>
                        <a href="{{ route('admin.leads.index') }}" class="btn btn-sm btn-primary">View All <i class="bi bi-arrow-right ms-1"></i></a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead><tr>
                                <th class="ps-4">Name</th><th>Goal</th><th>Rating</th><th>Status</th><th>Received</th><th></th>
                            </tr></thead>
                            <tbody>
                                @php $rb = ['HOT'=>'bg-success','WARM'=>'bg-warning text-dark','COLD'=>'bg-secondary']; @endphp
                                @forelse($recent_leads as $lead)
                                    <tr>
                                        <td class="ps-4 fw-semibold">{{ $lead->full_name }}<div class="text-muted" style="font-size:.75rem;">{{ $lead->email }}</div></td>
                                        <td>{{ $lead->goal_label }}</td>
                                        <td><span class="badge {{ $rb[$lead->rating] ?? 'bg-light text-dark' }}">{{ $lead->rating }}</span></td>
                                        <td><span class="badge bg-light text-dark border text-capitalize">{{ $lead->status }}</span></td>
                                        <td class="text-muted" style="font-size:.85rem;">{{ $lead->created_at->format('M d, Y') }}</td>
                                        <td><a href="{{ route('admin.leads.show', $lead->id) }}" class="btn btn-sm btn-primary btn-action"><i class="bi bi-eye-fill"></i></a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center py-4 text-muted">No leads yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Applicants & Leads Statistics -->
    <div class="row g-3 mb-4">
        <div class="col-md-4 col-12">
            <div class="card border-0" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); color: white;">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: rgba(255,255,255,0.2);">
                            <i class="bi bi-patch-check-fill fs-5 text-white"></i>
                        </div>
                        <div>
                            <div class="text-white-50 small fw-semibold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Test Enrollments</div>
                            <div class="fs-3 fw-bold">{{ $stats['total_test_signups'] }}</div>
                            <a href="{{ route('admin.service-signups.index') }}" class="text-white-50 small text-decoration-none d-block mt-1">
                                Manage Leads <i class="bi bi-arrow-right-short"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-12">
            <div class="card border-0" style="background: linear-gradient(135deg, #065f46 0%, #10b981 100%); color: white;">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: rgba(255,255,255,0.2);">
                            <i class="bi bi-mortarboard-fill fs-5 text-white"></i>
                        </div>
                        <div>
                            <div class="text-white-50 small fw-semibold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">School Applications</div>
                            <div class="fs-3 fw-bold">{{ $stats['total_school_applications'] }}</div>
                            <a href="{{ route('admin.school-applications.index') }}" class="text-white-50 small text-decoration-none d-block mt-1">
                                Manage Students <i class="bi bi-arrow-right-short"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-12">
            <div class="card border-0" style="background: linear-gradient(135deg, #7c2d12 0%, #f97316 100%); color: white;">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: rgba(255,255,255,0.2);">
                            <i class="bi bi-globe-americas fs-5 text-white"></i>
                        </div>
                        <div>
                            <div class="text-white-50 small fw-semibold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Job Placements</div>
                            <div class="fs-3 fw-bold">{{ $stats['total_job_applications'] }}</div>
                            <a href="{{ route('admin.job-applications.index') }}" class="text-white-50 small text-decoration-none d-block mt-1">
                                Manage Placement Leads <i class="bi bi-arrow-right-short"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Consultations -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">Recent Consultations</h5>
                        <a href="{{ route('admin.consultations.index') }}" class="btn btn-sm btn-primary">
                            View All <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Service</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_consultations as $consultation)
                                <tr>
                                    <td class="ps-4 fw-semibold text-primary">#{{ $consultation->id }}</td>
                                    <td class="fw-semibold">{{ $consultation->full_name }}</td>
                                    <td class="text-muted">{{ $consultation->email }}</td>
                                    <td>{{ $consultation->service }}</td>
                                    <td>{{ $consultation->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $consultation->status_color }}">
                                            {{ ucfirst($consultation->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.consultations.show', $consultation) }}" 
                                           class="btn btn-sm btn-primary btn-action">
                                            <i class="bi bi-eye-fill"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        No consultations yet
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
