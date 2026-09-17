@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Scholarships</h1>
        <a href="{{ route('admin.scholarships.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Scholarship
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Name</th>
                            <th>Country</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($scholarships as $scholarship)
                        <tr>
                            <td>{{ $scholarship->order }}</td>
                            <td>{{ $scholarship->name }}</td>
                            <td>{{ $scholarship->country }}</td>
                            <td>{{ $scholarship->deadline ?? 'N/A' }}</td>
                            <td>
                                @if($scholarship->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.scholarships.edit', $scholarship->id) }}" class="btn btn-sm btn-info text-white">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.scholarships.destroy', $scholarship->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
                            <td colspan="6" class="text-center py-4">No scholarships found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end">
                {{ $scholarships->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
