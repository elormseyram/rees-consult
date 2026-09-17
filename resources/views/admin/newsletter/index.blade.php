@extends('admin.layouts.app')

@section('title', 'Newsletter Subscribers')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Newsletter Subscribers</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">All Subscribers</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Subscribed Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subscribers as $subscriber)
                                <tr>
                                    <td>{{ $subscriber->email }}</td>
                                    <td>
                                        @if ($subscriber->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $subscriber->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <form action="{{ route('admin.newsletter.toggle', $subscriber) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="btn btn-sm {{ $subscriber->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                                    title="{{ $subscriber->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i
                                                        class="bi {{ $subscriber->is_active ? 'bi-x-circle' : 'bi-check-circle' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.newsletter.destroy', $subscriber) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this subscriber?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger ms-1"
                                                    title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No subscribers found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $subscribers->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
