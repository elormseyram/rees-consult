@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Contact Messages</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Name</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $message)
                        <tr class="{{ $message->is_replied ? '' : 'fw-bold bg-light' }}">
                            <td>
                                @if($message->is_replied)
                                    <span class="badge bg-success">Replied</span>
                                @else
                                    <span class="badge bg-warning text-dark">New</span>
                                @endif
                            </td>
                            <td>
                                {{ $message->name }}
                                <div class="small text-muted">{{ $message->email }}</div>
                            </td>
                            <td>{{ Str::limit($message->subject, 50) }}</td>
                            <td>{{ $message->created_at->format('M d, Y h:i A') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.contact.show', $message) }}" class="btn btn-sm btn-primary" title="View & Reply">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <form action="{{ route('admin.contact.destroy', $message) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">No messages found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end">
                {{ $messages->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
