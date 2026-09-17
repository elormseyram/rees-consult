@extends('admin.layouts.app')

@section('title', 'Email Settings')

@php
    $driver = old('mail_driver', $settings['mail_driver'] ?? config('mail.default'));
    $hasResendKey = ! empty($settings['resend_api_key'] ?? null);
@endphp

@section('content')
<div class="mb-4">
    <h1 class="mb-1">Email Delivery</h1>
    <p class="text-muted mb-0">Choose how the site sends email and who receives internal notifications.</p>
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
        <form action="{{ route('admin.settings.email.update') }}" method="POST">
            @csrf @method('PUT')

            <div class="card border-0 mb-4">
                <div class="card-header bg-white py-3"><h6 class="fw-bold mb-0">Delivery provider</h6></div>
                <div class="card-body">
                    <label class="form-label small fw-semibold">Send email using</label>
                    <select name="mail_driver" id="mail_driver" class="form-select mb-3">
                        <option value="smtp"   {{ $driver === 'smtp' ? 'selected' : '' }}>SMTP (current Hostinger mailbox)</option>
                        <option value="resend" {{ $driver === 'resend' ? 'selected' : '' }}>Resend (API)</option>
                        <option value="log"    {{ $driver === 'log' ? 'selected' : '' }}>Log only (nothing is delivered — for testing)</option>
                    </select>

                    <div id="resend-fields" class="{{ $driver === 'resend' ? '' : 'd-none' }}">
                        <label class="form-label small fw-semibold">Resend API key</label>
                        <input type="password" name="resend_api_key" class="form-control" autocomplete="new-password"
                               placeholder="{{ $hasResendKey ? 'A key is saved — leave blank to keep it' : 're_xxxxxxxxxxxxxxxx' }}">
                        <div class="form-text">
                            Create one at <a href="https://resend.com/api-keys" target="_blank" rel="noopener">resend.com/api-keys</a>.
                            The sending domain must be verified in Resend, and the “From” address below must belong to it.
                            @if ($hasResendKey)
                                <span class="text-success d-block mt-1"><i class="bi bi-check-circle"></i> A key is currently saved.</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 mb-4">
                <div class="card-header bg-white py-3"><h6 class="fw-bold mb-0">Sender identity</h6></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">From address</label>
                            <input type="email" name="mail_from_address" class="form-control" required
                                   value="{{ old('mail_from_address', $settings['mail_from_address'] ?? config('mail.from.address')) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">From name</label>
                            <input type="text" name="mail_from_name" class="form-control" required
                                   value="{{ old('mail_from_name', $settings['mail_from_name'] ?? "Ree's Consult") }}">
                            <div class="form-text">Shown as the sender. Keep it short.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Reply-to address <span class="text-muted fw-normal">(optional)</span></label>
                            <input type="email" name="mail_reply_to" class="form-control"
                                   value="{{ old('mail_reply_to', $settings['mail_reply_to'] ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Internal notification inbox(es)</label>
                            <input type="text" name="mail_admin_recipients" class="form-control"
                                   value="{{ old('mail_admin_recipients', $settings['mail_admin_recipients'] ?? '') }}"
                                   placeholder="team@reesconsult.com, sales@reesconsult.com">
                            <div class="form-text">Comma-separated. Where new-lead alerts go.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 mb-4">
                <div class="card-header bg-white py-3"><h6 class="fw-bold mb-0">Automation</h6></div>
                <div class="card-body">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="mail_automation_enabled"
                               name="mail_automation_enabled" value="1"
                               {{ old('mail_automation_enabled', $settings['mail_automation_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="mail_automation_enabled">
                            Send automated emails to customers
                        </label>
                    </div>
                    <div class="form-text">
                        Master switch. When off, no welcome or confirmation emails go out, whatever the individual
                        templates say. Internal staff notifications are unaffected.
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> Save settings</button>
            <a href="{{ route('admin.email-templates.index') }}" class="btn btn-outline-secondary">Edit automated emails</a>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 mb-4">
            <div class="card-header bg-white py-3"><h6 class="fw-bold mb-0">Send a test</h6></div>
            <div class="card-body">
                <p class="small text-muted">Uses the settings as currently <strong>saved</strong>. Save first, then test.</p>
                <form action="{{ route('admin.settings.email.test') }}" method="POST">
                    @csrf
                    <input type="email" name="test_email" class="form-control mb-2" required
                           placeholder="you@example.com" value="{{ old('test_email', auth()->user()->email) }}">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="bi bi-send me-1"></i> Send test email
                    </button>
                </form>
            </div>
        </div>

        <div class="card border-0">
            <div class="card-header bg-white py-3"><h6 class="fw-bold mb-0">Status</h6></div>
            <div class="card-body small">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Active mailer</span>
                    <span class="fw-semibold">{{ $envMailer }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">From</span>
                    <span class="fw-semibold text-end">{{ config('mail.from.address') }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Automation</span>
                    <span class="fw-semibold">
                        {{ ($settings['mail_automation_enabled'] ?? '1') == '1' ? 'On' : 'Off' }}
                    </span>
                </div>
                <hr>
                <p class="text-muted mb-0" style="font-size:.8rem;">
                    Emails are sent immediately during the request (not queued), so they arrive without a
                    background worker running.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('mail_driver').addEventListener('change', function () {
        document.getElementById('resend-fields').classList.toggle('d-none', this.value !== 'resend');
    });
</script>
@endpush
