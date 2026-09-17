@extends('admin.layouts.app')

@section('title', 'Automated Emails')

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
    <div>
        <h1 class="mb-1">Automated Emails</h1>
        <p class="text-muted mb-0">The emails customers receive automatically. Edit the wording, or switch any of them off.</p>
    </div>
    <a href="{{ route('admin.settings.email') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-gear me-1"></i> Delivery settings
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success"><i class="bi bi-check-circle me-1"></i>{{ session('success') }}</div>
@endif

@unless (\App\Services\MailSettings::automationEnabled())
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-1"></i>
        Automated customer emails are currently <strong>switched off</strong> globally.
        <a href="{{ route('admin.settings.email') }}">Turn them on in delivery settings.</a>
    </div>
@endunless

<div class="card border-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Email</th>
                    <th>Subject</th>
                    <th class="text-center">Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($templates as $template)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-semibold">{{ $template->name }}</div>
                            <div class="text-muted small">{{ $template->description }}</div>
                        </td>
                        <td class="small">{{ $template->subject }}</td>
                        <td class="text-center">
                            <form action="{{ route('admin.email-templates.toggle', $template->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="btn btn-sm {{ $template->is_enabled ? 'btn-success' : 'btn-outline-secondary' }}">
                                    {{ $template->is_enabled ? 'On' : 'Off' }}
                                </button>
                            </form>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.email-templates.edit', $template->id) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            No templates installed yet. Run <code>php artisan db:seed --class=EmailTemplateSeeder</code>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
