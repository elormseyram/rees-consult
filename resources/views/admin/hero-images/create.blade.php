@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add Hero Image</h1>
        <a href="{{ route('admin.hero-images.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.hero-images.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="page_slug" class="form-label">Page</label>
                            <select class="form-select @error('page_slug') is-invalid @enderror" id="page_slug" name="page_slug" required>
                                <option value="">Select a page...</option>
                                @foreach($pages as $slug => $title)
                                    <option value="{{ $slug }}" {{ old('page_slug') == $slug ? 'selected' : '' }}>{{ $title }}</option>
                                @endforeach
                            </select>
                            @error('page_slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="page_title" class="form-label">Page Title</label>
                            <input type="text" class="form-control @error('page_title') is-invalid @enderror" id="page_title" name="page_title" value="{{ old('page_title') }}" required>
                            @error('page_title')
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
                                <input type="range" class="form-range" id="overlay_opacity" name="overlay_opacity" min="0" max="1" step="0.1" value="{{ old('overlay_opacity', 0.8) }}" style="height: 40px;">
                                <span class="input-group-text" id="opacity-display">80%</span>
                            </div>
                            <small class="text-muted">Controls the darkness of the overlay on the hero image (0 = transparent, 1 = fully opaque)</small>
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
                            <button type="submit" class="btn btn-primary">Add Hero Image</button>
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
