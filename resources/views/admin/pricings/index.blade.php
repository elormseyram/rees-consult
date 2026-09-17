@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pricing Management</h1>
        <a href="{{ route('admin.pricings.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Pricing
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Test Name</th>
                            <th>Group (In Person) (GH₵)</th>
                            <th>Group (Online) (GH₵)</th>
                            <th>1-on-1 (In Person) (GH₵)</th>
                            <th>1-on-1 (Online) (GH₵)</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pricings as $pricing)
                        <tr>
                            <td><strong>{{ $pricing->test_name }}</strong></td>
                            <td>{{ number_format($pricing->group_in_person) }}</td>
                            <td>{{ number_format($pricing->group_online) }}</td>
                            <td>{{ number_format($pricing->one_on_one_in_person) }}</td>
                            <td>{{ number_format($pricing->one_on_one_online) }}</td>
                            <td><span class="badge bg-secondary">{{ $pricing->order }}</span></td>
                            <td>
                                @if($pricing->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.pricings.edit', $pricing->id) }}" class="btn btn-sm btn-info text-white">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.pricings.destroy', $pricing->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
                            <td colspan="8" class="text-center py-4">No pricing found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end">
                {{ $pricings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
