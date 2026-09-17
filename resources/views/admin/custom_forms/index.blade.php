@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Custom Forms</h1>
            <p class="text-muted mb-0 mt-1">Create, manage, and share forms — just like Google Forms.</p>
        </div>
        <a href="{{ route('admin.custom_forms.create') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-lg"></i> Create New Form
        </a>
    </div>

    @if($forms->isEmpty())
        <div class="card shadow border-0">
            <div class="card-body text-center py-5">
                <i class="bi bi-ui-checks" style="font-size: 4rem; color: #6c757d; opacity: 0.4;"></i>
                <h4 class="mt-3 text-muted">No forms yet</h4>
                <p class="text-muted mb-4">Create your first custom form to start collecting responses from users.</p>
                <a href="{{ route('admin.custom_forms.create') }}" class="btn btn-primary btn-lg px-5">
                    <i class="bi bi-plus-circle"></i> Create Your First Form
                </a>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($forms as $form)
                <div class="col-md-6 col-xl-4">
                    <div class="card shadow-sm border-0 h-100 form-card">
                        {{-- Card Header with status indicator --}}
                        <div class="card-header border-0 bg-white d-flex justify-content-between align-items-start pt-3">
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1 fw-bold">{{ $form->title }}</h5>
                                <small class="text-muted">Created {{ $form->created_at->diffForHumans() }}</small>
                            </div>
                            @if($form->is_active)
                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill"><i class="bi bi-check-circle-fill"></i> Active</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill"><i class="bi bi-x-circle-fill"></i> Inactive</span>
                            @endif
                        </div>

                        <div class="card-body pt-2">
                            {{-- Stats Row --}}
                            <div class="d-flex gap-3 mb-3">
                                <div class="d-flex align-items-center gap-1">
                                    <i class="bi bi-people-fill text-primary"></i>
                                    <span class="fw-semibold">{{ $form->submissions_count }}</span>
                                    <span class="text-muted small">responses</span>
                                </div>
                                <div class="d-flex align-items-center gap-1">
                                    <i class="bi bi-list-check text-secondary"></i>
                                    <span class="fw-semibold">{{ $form->questions_count ?? '—' }}</span>
                                    <span class="text-muted small">questions</span>
                                </div>
                            </div>

                            {{-- Public Link --}}
                            <div class="input-group input-group-sm mb-3">
                                <input type="text" class="form-control bg-light text-muted" value="{{ route('custom_forms.show', $form->slug) }}" readonly id="link-{{ $form->id }}">
                                <button class="btn btn-outline-primary" type="button" onclick="copyToClipboard('{{ route('custom_forms.show', $form->slug) }}')">
                                    <i class="bi bi-clipboard"></i> Copy
                                </button>
                            </div>
                        </div>

                        {{-- Card Footer with actions --}}
                        <div class="card-footer bg-white border-top d-flex gap-2 flex-wrap py-3">
                            <a href="{{ route('admin.custom_forms.submissions', $form) }}" class="btn btn-sm btn-info text-white flex-fill">
                                <i class="bi bi-bar-chart-line-fill"></i> Responses
                            </a>
                            <a href="{{ route('custom_forms.show', $form->slug) }}" target="_blank" class="btn btn-sm btn-dark flex-fill">
                                <i class="bi bi-eye-fill"></i> Preview
                            </a>
                            <a href="{{ route('admin.custom_forms.edit', $form) }}" class="btn btn-sm btn-primary flex-fill">
                                <i class="bi bi-pencil-fill"></i> Edit
                            </a>
                            <form action="{{ route('admin.custom_forms.destroy', $form) }}" method="POST" class="flex-fill" onsubmit="return confirm('Delete this form and ALL its responses? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger w-100">
                                    <i class="bi bi-trash-fill"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $forms->links() }}
        </div>
    @endif
</div>

<style>
    .form-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-radius: 12px;
        overflow: hidden;
    }
    .form-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    .form-card .card-footer .btn {
        font-size: 0.8rem;
        font-weight: 500;
        border-radius: 6px;
    }
</style>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            Toast.fire({
                icon: 'success',
                title: 'Link copied to clipboard!'
            });
        }, function(err) {
            console.error('Could not copy text: ', err);
        });
    }
</script>
@endsection
