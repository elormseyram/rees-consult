@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800">{{ $customForm->title }}</h1>
            <p class="text-muted mt-1 mb-0"><i class="bi bi-people-fill me-1"></i> {{ $submissions->total() }} total responses</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.custom_forms.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Forms
            </a>
            <a href="{{ route('admin.custom_forms.edit', $customForm) }}" class="btn btn-primary">
                <i class="bi bi-pencil-fill"></i> Edit Form
            </a>
            @if($submissions->total() > 0)
            <a href="{{ route('admin.custom_forms.export', $customForm) }}" class="btn btn-success">
                <i class="bi bi-file-earmark-spreadsheet-fill"></i> Export CSV
            </a>
            @endif
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #e8f5e9;">
                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Responses</div>
                        <div class="fs-4 fw-bold">{{ $submissions->total() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #e3f2fd;">
                        <i class="bi bi-list-check text-primary fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Questions</div>
                        <div class="fs-4 fw-bold">{{ $customForm->questions->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #fff3e0;">
                        <i class="bi bi-clock-fill text-warning fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Latest Response</div>
                        <div class="fw-bold">{{ $submissions->first() ? $submissions->first()->created_at->diffForHumans() : 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
        <div class="card-body p-0">
            @if($submissions->isEmpty())
                <div class="text-center py-5 px-4">
                    <i class="bi bi-inbox" style="font-size: 4rem; color: #6c757d; opacity: 0.3;"></i>
                    <h5 class="mt-3 text-muted">No submissions yet</h5>
                    <p class="text-muted">Share the form link to start collecting responses.</p>
                    <div class="input-group input-group-sm mx-auto" style="max-width: 500px;">
                        <input type="text" class="form-control bg-light" value="{{ route('custom_forms.show', $customForm->slug) }}" readonly>
                        <button class="btn btn-primary" onclick="navigator.clipboard.writeText('{{ route('custom_forms.show', $customForm->slug) }}').then(() => Toast.fire({icon:'success', title:'Link copied!'}))">
                            <i class="bi bi-clipboard"></i> Copy Link
                        </button>
                    </div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="white-space: nowrap;">
                        <thead style="background: #f8f9fc;">
                            <tr>
                                <th class="ps-4 text-muted small fw-bold text-uppercase">#</th>
                                <th class="text-muted small fw-bold text-uppercase">Date Submitted</th>
                                @foreach($customForm->questions as $question)
                                    @if($question->type !== 'section_break')
                                        <th class="text-muted small fw-bold text-uppercase">{{ \Illuminate\Support\Str::limit($question->question_text, 25) }}</th>
                                    @endif
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($submissions as $submission)
                                <tr>
                                    <td class="ps-4 fw-semibold text-primary">{{ $submission->id }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $submission->created_at->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $submission->created_at->format('h:i A') }}</small>
                                    </td>
                                    @foreach($customForm->questions as $question)
                                        @if($question->type !== 'section_break')
                                            @php
                                                $answer = $submission->answers->firstWhere('custom_form_question_id', $question->id);
                                                $answerText = '—';
                                                if ($answer) {
                                                    $decoded = json_decode($answer->answer, true);
                                                    if (is_array($decoded)) {
                                                        $answerText = implode(', ', $decoded);
                                                    } else {
                                                        $answerText = $answer->answer;
                                                    }
                                                }
                                            @endphp
                                            <td title="{{ $answerText }}">{{ \Illuminate\Support\Str::limit($answerText, 40) }}</td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="p-3">
                    {{ $submissions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
