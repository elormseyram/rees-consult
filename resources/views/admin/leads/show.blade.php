@extends('admin.layouts.app')

@section('title', 'Lead · ' . $lead->full_name)

@php
    $ratingBadge = ['HOT' => 'bg-success', 'WARM' => 'bg-warning text-dark', 'COLD' => 'bg-secondary'];
    $statusBadge = [
        'new' => 'bg-primary', 'contacted' => 'bg-info text-dark',
        'qualified' => 'bg-warning text-dark', 'won' => 'bg-success', 'lost' => 'bg-secondary',
    ];
    $waNumber = preg_replace('/\D/', '', $lead->phone);
@endphp

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
    <div>
        <a href="{{ route('admin.leads.index') }}" class="text-muted text-decoration-none small"><i class="bi bi-arrow-left"></i> Back to leads</a>
        <h1 class="mb-0 mt-1">{{ $lead->full_name }}
            <span class="align-middle ms-2">
                @include('admin.leads.partials.rating-badge', ['rating' => $lead->rating, 'size' => 'lg'])
            </span>
            <span class="badge {{ $statusBadge[$lead->status] ?? 'bg-light text-dark' }} align-middle text-capitalize">{{ $lead->status }}</span>
        </h1>
        <p class="text-muted mb-0">{{ $lead->goal_label }} · received {{ $lead->created_at->format('M d, Y \a\t h:i A') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="mailto:{{ $lead->email }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-envelope"></i> Email</a>
        <a href="tel:{{ $lead->phone }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-telephone"></i> Call</a>
        <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="btn btn-sm btn-success"><i class="bi bi-whatsapp"></i> WhatsApp</a>
    </div>
</div>

<div class="row g-4">
    <!-- Left: assessment + transcript -->
    <div class="col-lg-8">
        <!-- Assessment -->
        <div class="card border-0 mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3"><i class="bi bi-graph-up-arrow me-2" style="color:#F8A706;"></i>Buying Assessment</h5>
                <div class="row g-3 text-center">
                    <div class="col-6 col-md-3">
                        <div class="border rounded-3 py-3">
                            <div class="text-muted text-uppercase" style="font-size:.68rem;">Rating</div>
                            <div class="fw-bold fs-5">{{ $lead->rating }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="border rounded-3 py-3">
                            <div class="text-muted text-uppercase" style="font-size:.68rem;">Score</div>
                            <div class="fw-bold fs-5">{{ $lead->score }}/12</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="border rounded-3 py-3">
                            <div class="text-muted text-uppercase" style="font-size:.68rem;">Willing</div>
                            <div class="fw-bold fs-5 {{ $lead->willing ? 'text-success' : 'text-danger' }}">
                                {{ $lead->willing ? 'Yes' : 'Not yet' }}
                            </div>
                            <div class="text-muted" style="font-size:.7rem;">{{ $lead->willing_score }}/6 intent</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="border rounded-3 py-3">
                            <div class="text-muted text-uppercase" style="font-size:.68rem;">Able</div>
                            <div class="fw-bold fs-5 {{ $lead->able ? 'text-success' : 'text-danger' }}">
                                {{ $lead->able ? 'Yes' : 'Unclear' }}
                            </div>
                            <div class="text-muted" style="font-size:.7rem;">{{ $lead->able_score }}/6 capacity</div>
                        </div>
                    </div>
                </div>
                @if ($lead->verdict)
                    <div class="alert alert-light border mt-3 mb-0">
                        <strong>Recommended action:</strong> {{ $lead->verdict }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Call recorder -->
        @include('admin.leads.partials.recorder')

        <!-- Notes timeline -->
        @include('admin.leads.partials.notes')

        <!-- Transcript -->
        <div class="card border-0 mt-4">
            <div class="card-header bg-white py-3"><h5 class="fw-bold mb-0"><i class="bi bi-list-check me-2" style="color:#F8A706;"></i>Full Submission</h5></div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <tbody>
                        @forelse(($lead->transcript ?? []) as $label => $value)
                            <tr>
                                <td class="ps-4 text-muted" style="width:40%;">{{ $label }}</td>
                                <td class="fw-semibold pe-4">{{ $value !== null && $value !== '' ? $value : '—' }}</td>
                            </tr>
                        @empty
                            <tr><td class="ps-4 py-4 text-muted">No transcript stored.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right: status + contact -->
    <div class="col-lg-4">
        <div class="card border-0 mb-4">
            <div class="card-header bg-white py-3"><h6 class="fw-bold mb-0">Update Status</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.leads.update-status', $lead->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <label class="form-label small fw-semibold">Follow-up status</label>
                    <select name="status" class="form-select mb-3">
                        @foreach ($statuses as $s)
                            <option value="{{ $s }}" {{ $lead->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    <label class="form-label small fw-semibold">Internal note (optional)</label>
                    <textarea name="notes" class="form-control mb-3" rows="3" placeholder="Add a follow-up note…">{{ $lead->notes }}</textarea>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check2-circle me-1"></i> Save</button>
                </form>
            </div>
        </div>

        <div class="card border-0">
            <div class="card-header bg-white py-3"><h6 class="fw-bold mb-0">Contact</h6></div>
            <div class="card-body small">
                <div class="mb-2"><i class="bi bi-person text-muted me-2"></i>{{ $lead->full_name }}</div>
                <div class="mb-2"><i class="bi bi-envelope text-muted me-2"></i><a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a></div>
                <div class="mb-2"><i class="bi bi-telephone text-muted me-2"></i><a href="tel:{{ $lead->phone }}">{{ $lead->phone }}</a></div>
                @if ($lead->contact_method)
                    <div class="mb-0"><i class="bi bi-chat-dots text-muted me-2"></i>Prefers: {{ $lead->contact_method }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
