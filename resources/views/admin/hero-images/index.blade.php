@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Hero Images</h1>
            <a href="{{ route('admin.hero-images.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add Hero Image
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Page</th>
                                <th>Preview</th>
                                <th>Overlay Opacity</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($heroImages as $heroImage)
                                <tr>
                                    <td>
                                        <strong>{{ $heroImage->page_title }}</strong><br>
                                        <small class="text-muted">{{ $heroImage->page_slug }}</small>
                                    </td>
                                    <td>
                                        @if ($heroImage->image_path || $heroImage->image_url)
                                            <img src="{{ $heroImage->formatted_image_url }}"
                                                alt="{{ $heroImage->page_title }}"
                                                style="max-height: 80px; max-width: 150px; object-fit: cover;">
                                        @else
                                            <span class="text-muted">No image</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($heroImage->overlay_opacity * 100, 0) }}%</td>
                                    <td>
                                        @if ($heroImage->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.hero-images.edit', $heroImage->id) }}"
                                                class="btn btn-sm btn-info text-white">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.hero-images.destroy', $heroImage->id) }}"
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
                                    <td colspan="5" class="text-center py-4">No hero images found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end">
                    {{ $heroImages->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
