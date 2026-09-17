@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Events</h1>
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Event
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                        <tr>
                            <td style="width: 100px;">
                                @if($event->image)
                                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="img-fluid rounded" style="max-height: 50px;">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>{{ $event->title }}</td>
                            <td>
                                <div>{{ $event->start_time->format('M d, Y') }}</div>
                                <small class="text-muted">{{ $event->start_time->format('h:i A') }} - {{ $event->end_time->format('h:i A') }}</small>
                            </td>
                            <td>{{ $event->location }}</td>
                            <td>
                                @if($event->price > 0)
                                    ${{ number_format($event->price, 2) }}
                                @else
                                    <span class="badge bg-success">Free</span>
                                @endif
                            </td>
                            <td>
                                @if($event->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-info text-white" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this event?');">
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
                            <td colspan="7" class="text-center py-4">No events found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end">
                {{ $events->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
