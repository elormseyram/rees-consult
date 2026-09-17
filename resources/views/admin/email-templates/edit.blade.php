@extends('admin.layouts.app')

@section('title', 'Edit · ' . $template->name)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.email-templates.index') }}" class="text-muted text-decoration-none small">
        <i class="bi bi-arrow-left"></i> Back to automated emails
    </a>
    <h1 class="mb-1 mt-1">{{ $template->name }}</h1>
    <p class="text-muted mb-0">{{ $template->description }}</p>
</div>

@if (session('success'))
    <div class="alert alert-success"><i class="bi bi-check-circle me-1"></i>{{ session('success') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <form action="{{ route('admin.email-templates.update', $template->id) }}" method="POST">
            @csrf @method('PUT')

            <div class="card border-0 mb-4">
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch"
                               id="is_enabled" name="is_enabled" value="1"
                               {{ old('is_enabled', $template->is_enabled) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_enabled">This email is active</label>
                    </div>

                    <label class="form-label small fw-semibold">Subject line</label>
                    <input type="text" name="subject" id="subject" required maxlength="255"
                           class="form-control mb-3 @error('subject') is-invalid @enderror"
                           value="{{ old('subject', $template->subject) }}">
                    @error('subject')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                    <label class="form-label small fw-semibold">Message body</label>
                    <textarea name="body" id="body" rows="18" required
                              class="form-control font-monospace @error('body') is-invalid @enderror"
                              style="font-size:.85rem;">{{ old('body', $template->body) }}</textarea>
                    @error('body')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    <div class="form-text">
                        Basic HTML is allowed (<code>&lt;p&gt;</code>, <code>&lt;strong&gt;</code>,
                        <code>&lt;ul&gt;</code>, <code>&lt;a href&gt;</code>). The header, footer and branding
                        are added automatically.
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> Save email</button>
            <a href="{{ route('admin.email-templates.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 mb-4">
            <div class="card-header bg-white py-3"><h6 class="fw-bold mb-0">Placeholders</h6></div>
            <div class="card-body">
                <p class="small text-muted">
                    Click to insert. Each is replaced with the recipient’s real details when the email is sent.
                </p>
                <div class="d-flex flex-wrap gap-1">
                    @foreach ($placeholders as $placeholder)
                        <button type="button"
                                class="btn btn-sm btn-outline-secondary placeholder-chip font-monospace"
                                style="font-size:.75rem;"
                                data-token="{{ $placeholder }}">
                            {{ $placeholder }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card border-0">
            <div class="card-header bg-white py-3"><h6 class="fw-bold mb-0">Send a preview</h6></div>
            <div class="card-body">
                <p class="small text-muted">Sends this email with sample data. Save your changes first.</p>
                <form action="{{ route('admin.email-templates.preview', $template->id) }}" method="POST">
                    @csrf
                    <input type="email" name="preview_email" class="form-control mb-2" required
                           value="{{ old('preview_email', auth()->user()->email) }}">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="bi bi-send me-1"></i> Send preview
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    // Insert a placeholder at the cursor of whichever field was last focused.
    let lastField = document.getElementById('body');

    ['subject', 'body'].forEach(function (id) {
        document.getElementById(id).addEventListener('focus', function () { lastField = this; });
    });

    // Braces are concatenated so Blade never tries to compile them as an echo.
    const OPEN = '{' + '{ ', CLOSE = ' }' + '}';

    document.querySelectorAll('.placeholder-chip').forEach(function (chip) {
        chip.addEventListener('click', function () {
            const token = OPEN + this.dataset.token + CLOSE;
            const start = lastField.selectionStart, end = lastField.selectionEnd;
            lastField.value = lastField.value.slice(0, start) + token + lastField.value.slice(end);
            lastField.focus();
            lastField.selectionStart = lastField.selectionEnd = start + token.length;
        });
    });
})();
</script>
@endpush
