@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">View Message</h1>
            <a href="{{ route('admin.contact.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Inbox
            </a>
        </div>

        <div class="row">
            <!-- Message Details -->
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 fw-bold text-primary">Message Details</h6>
                        @if ($contactMessage->is_replied)
                            <span class="badge bg-success">Replied</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending Reply</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="small text-muted text-uppercase fw-bold">From</label>
                            <div>{{ $contactMessage->name }} &lt;{{ $contactMessage->email }}&gt;</div>
                        </div>
                        @if ($contactMessage->phone)
                            <div class="mb-3">
                                <label class="small text-muted text-uppercase fw-bold">Phone</label>
                                <div><a href="tel:{{ $contactMessage->phone }}">{{ $contactMessage->phone }}</a></div>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label class="small text-muted text-uppercase fw-bold">Subject</label>
                            <div>{{ $contactMessage->subject }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="small text-muted text-uppercase fw-bold">Date</label>
                            <div>{{ $contactMessage->created_at->format('F d, Y h:i A') }}</div>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label class="small text-muted text-uppercase fw-bold">Message</label>
                            <div class="p-3 bg-light rounded border">
                                {!! nl2br(e($contactMessage->message)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reply Form -->
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">Send Reply</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.contact.reply', $contactMessage) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="template" class="form-label">Use Template</label>
                                <select class="form-select" id="template" onchange="loadTemplate(this)">
                                    <option value="">Select a template...</option>
                                    @foreach ($templates as $template)
                                        <option value="{{ $template->body }}">{{ $template->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="reply_body" class="form-label">Reply Message</label>
                                <textarea class="form-control" id="reply_body" name="reply_body" rows="10" required></textarea>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send"></i> Send Reply
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function loadTemplate(select) {
                const body = select.value;
                if (body) {
                    document.getElementById('reply_body').value = body;
                }
            }
        </script>
    @endpush
@endsection
