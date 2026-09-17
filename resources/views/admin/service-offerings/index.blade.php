@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Service Offerings</h1>
        <a href="{{ route('admin.service-offerings.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Offering
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Title</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($offerings as $offering)
                        <tr>
                            <td>
                                <span class="badge bg-info">{{ $categories[$offering->category] ?? $offering->category }}</span>
                            </td>
                            <td>
                                <strong>{{ $offering->title }}</strong><br>
                                @if($offering->description)
                                    <small class="text-muted">{{ Str::limit($offering->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $offering->order }}</span>
                            </td>
                            <td>
                                @if($offering->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.service-offerings.edit', $offering->id) }}" class="btn btn-sm btn-info text-white">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.service-offerings.destroy', $offering->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">No service offerings found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end">
                {{ $offerings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
