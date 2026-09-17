@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Reply Templates</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTemplateModal">
            <i class="bi bi-plus-lg"></i> Add New Template
        </button>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Subject Line</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($templates as $template)
                        <tr>
                            <td>{{ $template->name }}</td>
                            <td>{{ $template->subject }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-info text-white" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editTemplateModal{{ $template->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('admin.reply_templates.destroy', $template) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this template?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editTemplateModal{{ $template->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.reply_templates.update', $template) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Template</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Template Name</label>
                                                        <input type="text" class="form-control" name="name" value="{{ $template->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Email Subject</label>
                                                        <input type="text" class="form-control" name="subject" value="{{ $template->subject }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Email Body</label>
                                                        <textarea class="form-control" name="body" rows="5" required>{{ $template->body }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4">No templates found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end">
                {{ $templates->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createTemplateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.reply_templates.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Create New Template</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Template Name</label>
                        <input type="text" class="form-control" name="name" placeholder="e.g., General Inquiry Reply" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Subject</label>
                        <input type="text" class="form-control" name="subject" placeholder="e.g., Re: Your Inquiry" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Body</label>
                        <textarea class="form-control" name="body" rows="5" placeholder="Enter the template content..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Template</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
