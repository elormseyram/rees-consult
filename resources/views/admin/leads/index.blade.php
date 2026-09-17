@extends('admin.layouts.app')

@section('title', 'Apply Now Leads')

@php
    $ratingBadge = ['HOT' => 'bg-success', 'WARM' => 'bg-warning text-dark', 'COLD' => 'bg-secondary'];
    $statusBadge = [
        'new' => 'bg-primary', 'contacted' => 'bg-info text-dark',
        'qualified' => 'bg-warning text-dark', 'won' => 'bg-success', 'lost' => 'bg-secondary',
    ];
@endphp

@section('content')
<div class="admin-page-header d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
        <h1><i class="bi bi-fire me-2" style="color:#F8A706;"></i> Apply Now Leads</h1>
        <p class="text-muted mb-0">Qualified leads from the Apply Now form, scored by willingness &amp; ability to buy.</p>
    </div>
    <a href="{{ route('admin.leads.export', request()->query()) }}" class="btn btn-sm btn-dark">
        <i class="bi bi-download me-1"></i> Export CSV
    </a>
</div>

<!-- Stat chips -->
<div class="row g-3 mb-4 mt-1">
    <div class="col-6 col-md">
        <div class="card border-0"><div class="card-body py-3">
            <div class="text-muted text-uppercase fw-semibold" style="font-size:.7rem;">Total</div>
            <div class="fs-4 fw-bold">{{ $counts['total'] }}</div>
        </div></div>
    </div>
    <div class="col-6 col-md">
        <a href="{{ route('admin.leads.index', array_merge(request()->query(), ['rating' => 'HOT'])) }}"
           class="card border-0 text-decoration-none h-100"><div class="card-body py-3">
            @include('admin.leads.partials.rating-badge', ['rating' => 'HOT'])
            <div class="fs-4 fw-bold text-dark mt-1">{{ $counts['hot'] }}</div>
        </div></a>
    </div>
    <div class="col-6 col-md">
        <a href="{{ route('admin.leads.index', array_merge(request()->query(), ['rating' => 'WARM'])) }}"
           class="card border-0 text-decoration-none h-100"><div class="card-body py-3">
            @include('admin.leads.partials.rating-badge', ['rating' => 'WARM'])
            <div class="fs-4 fw-bold text-dark mt-1">{{ $counts['warm'] }}</div>
        </div></a>
    </div>
    <div class="col-6 col-md">
        <a href="{{ route('admin.leads.index', array_merge(request()->query(), ['rating' => 'COLD'])) }}"
           class="card border-0 text-decoration-none h-100"><div class="card-body py-3">
            @include('admin.leads.partials.rating-badge', ['rating' => 'COLD'])
            <div class="fs-4 fw-bold text-dark mt-1">{{ $counts['cold'] }}</div>
        </div></a>
    </div>
    <div class="col-6 col-md">
        <div class="card border-0"><div class="card-body py-3">
            <div class="text-primary text-uppercase fw-semibold" style="font-size:.7rem;">New / Unworked</div>
            <div class="fs-4 fw-bold">{{ $counts['new'] }}</div>
        </div></div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white py-3">
        <form method="GET" action="{{ route('admin.leads.index') }}" class="row g-2 align-items-center">
            <div class="col-md-3">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search name, email, phone…">
            </div>
            <div class="col-md-2">
                <select name="rating" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Ratings</option>
                    @foreach (['HOT','WARM','COLD'] as $r)
                        <option value="{{ $r }}" {{ request('rating') === $r ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    @foreach (['new','contacted','qualified','won','lost'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="goal" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Goals</option>
                    <option value="test_prep" {{ request('goal') === 'test_prep' ? 'selected' : '' }}>Test Prep</option>
                    <option value="study_abroad" {{ request('goal') === 'study_abroad' ? 'selected' : '' }}>Study Abroad</option>
                    <option value="work_abroad" {{ request('goal') === 'work_abroad' ? 'selected' : '' }}>Work Abroad</option>
                </select>
            </div>
            <div class="col-md-3 text-end">
                <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                <a href="{{ route('admin.leads.index') }}" class="btn btn-sm btn-light border">Reset</a>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Lead</th>
                    <th>Goal</th>
                    <th>Rating</th>
                    <th>Willing / Able</th>
                    <th>Status</th>
                    <th>Received</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                    <tr class="{{ $lead->rating === 'HOT' ? 'lead-hot' : ($lead->rating === 'COLD' ? 'lead-cold' : '') }}">
                        <td class="ps-4">
                            <div class="fw-semibold text-dark">{{ $lead->full_name }}</div>
                            <small class="text-muted d-block"><i class="bi bi-envelope"></i> {{ $lead->email }}</small>
                            <small class="text-muted d-block"><i class="bi bi-telephone"></i> {{ $lead->phone }}</small>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $lead->goal_label }}</span></td>
                        <td>
                            @include('admin.leads.partials.rating-badge', ['rating' => $lead->rating])
                            <div class="text-muted mt-1" style="font-size:.7rem;">{{ $lead->score }}/12</div>
                        </td>
                        <td style="font-size:.85rem;">
                            <span class="{{ $lead->willing ? 'text-success' : 'text-muted' }}">
                                <i class="bi bi-{{ $lead->willing ? 'check-circle-fill' : 'dash-circle' }}"></i> Willing
                            </span><br>
                            <span class="{{ $lead->able ? 'text-success' : 'text-muted' }}">
                                <i class="bi bi-{{ $lead->able ? 'check-circle-fill' : 'dash-circle' }}"></i> Able
                            </span>
                        </td>
                        <td><span class="badge {{ $statusBadge[$lead->status] ?? 'bg-light text-dark' }} text-capitalize">{{ $lead->status }}</span></td>
                        <td class="text-muted" style="font-size:.85rem;">{{ $lead->created_at->format('M d, Y') }}<br><span style="font-size:.75rem;">{{ $lead->created_at->format('h:i A') }}</span></td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.leads.show', $lead->id) }}" class="btn btn-action btn-sm btn-primary">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <form action="{{ route('admin.leads.destroy', $lead->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this lead permanently?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-action btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox d-block fs-1 mb-2"></i>
                            No leads found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($leads->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $leads->links() }}
        </div>
    @endif
</div>
@endsection
