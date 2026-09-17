@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add Hero Slider Image</h1>
        <a href="{{ route('admin.hero-sliders.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.hero-sliders.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title (Optional)</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="e.g., Travel Abroad">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="subtitle" class="form-label">Subtitle (Optional)</label>
                            <input type="text" class="form-control @error('subtitle') is-invalid @enderror" id="subtitle" name="subtitle" value="{{ old('subtitle') }}" placeholder="e.g., With Ease">
                            @error('subtitle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image_path" class="form-label">Upload Image</label>
                            <input type="file" class="form-control @error('image_path') is-invalid @enderror" id="image_path" name="image_path" accept="image/*">
                            <small class="text-muted">Max 5MB. Formats: JPEG, PNG, JPG, GIF, WebP</small>
                            @error('image_path')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image_url" class="form-label">Or Image URL</label>
                            <input type="url" class="form-control @error('image_url') is-invalid @enderror" id="image_url" name="image_url" value="{{ old('image_url') }}" placeholder="https://example.com/image.jpg">
                            <small class="text-muted">Use this if you prefer to link to an external image</small>
                            @error('image_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="overlay_opacity" class="form-label">Overlay Opacity</label>
                            <div class="input-group">
                                <input type="range" class="form-range" id="overlay_opacity" name="overlay_opacity" min="0" max="1" step="0.1" value="{{ old('overlay_opacity', 0.5) }}" style="height: 40px;">
                                <span class="input-group-text" id="opacity-display">50%</span>
                            </div>
                            <small class="text-muted">Controls the darkness of the overlay on the slider image (0 = transparent, 1 = fully opaque)</small>
                        </div>

                        <div class="mb-3">
                            <label for="order" class="form-label">Display Order</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', 0) }}" min="0">
                            <small class="text-muted">Lower numbers appear first in the carousel</small>
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Add Slider Image</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('overlay_opacity').addEventListener('input', function() {
        document.getElementById('opacity-display').textContent = Math.round(this.value * 100) + '%';
    });
</script>
@endsection
