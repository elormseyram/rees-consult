@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Hero Slider Images</h1>
            <a href="{{ route('admin.hero-sliders.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add Slider Image
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
                                <th>Preview</th>
                                <th>Overlay Opacity</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sliders as $slider)
                                <tr>
                                    <td>
                                        <span class="badge bg-info">{{ $slider->order }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $slider->title ?? 'No Title' }}</strong><br>
                                        <small class="text-muted">{{ $slider->subtitle ?? 'No Subtitle' }}</small>
                                    </td>
                                    <td>
                                        @if ($slider->image_path || $slider->image_url)
                                            <img src="{{ $slider->formatted_image_url }}" alt="{{ $slider->title }}"
                                                style="max-height: 80px; max-width: 150px; object-fit: cover;">
                                        @else
                                            <span class="text-muted">No image</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($slider->overlay_opacity * 100, 0) }}%</td>
                                    <td>
                                        @if ($slider->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.hero-sliders.edit', $slider->id) }}"
                                                class="btn btn-sm btn-info text-white">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.hero-sliders.destroy', $slider->id) }}"
                                                method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
                                    <td colspan="6" class="text-center py-4">No hero slider images found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end">
                    {{ $sliders->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
