@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Services</h1>
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Service
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Price (GH₵)</th>
                            <th>Proc. Fee (GH₵)</th>
                            <th>Icon</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                        <tr>
                            <td>{{ $service->order }}</td>
                            <td>
                                <strong>{{ $service->title }}</strong>
                                @if($service->country)
                                    <div class="small text-muted"><i class="bi bi-geo-alt"></i> {{ $service->country }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ ucwords(str_replace('_', ' ', $service->category)) }}</span>
                            </td>
                            <td>GH₵ {{ number_format($service->price * 12, 0) }}</td>
                            <td>GH₵ {{ number_format($service->processing_fee * 12, 0) }}</td>
                            <td><i class="bi {{ $service->icon }} fs-4"></i></td>
                            <td>
                                @if($service->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-info text-white">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
                            <td colspan="8" class="text-center py-4">No services found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end">
                {{ $services->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
