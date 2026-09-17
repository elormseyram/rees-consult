@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Testimonial</h1>
        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role/Title</label>
                            <input type="text" class="form-control @error('role') is-invalid @enderror" id="role" name="role" value="{{ old('role') }}" placeholder="e.g., Student, Ghana" required>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Testimonial Message (Optional for Video)</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="4">{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="youtube_video_url" class="form-label">YouTube Video URL (Optional)</label>
                            <input type="url" class="form-control @error('youtube_video_url') is-invalid @enderror" id="youtube_video_url" name="youtube_video_url" value="{{ old('youtube_video_url') }}" placeholder="https://www.youtube.com/watch?v=...">
                            @error('youtube_video_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="video-category-wrap">
                            <label for="video_category" class="form-label">Video Category</label>
                            <select class="form-select @error('video_category') is-invalid @enderror" id="video_category" name="video_category">
                                <option value="testimonial" {{ old('video_category', 'testimonial') == 'testimonial' ? 'selected' : '' }}>
                                    Testimonial (shows on Testimonials page)
                                </option>
                                <option value="award" {{ old('video_category') == 'award' ? 'selected' : '' }}>
                                    Award (shows on Awards page)
                                </option>
                                <option value="other" {{ old('video_category') == 'other' ? 'selected' : '' }}>
                                    Other (hidden from public pages)
                                </option>
                            </select>
                            @error('video_category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>{{-- end col-md-8 --}}

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="image" class="form-label">Photo</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating</label>
                            <select class="form-select @error('rating') is-invalid @enderror" id="rating" name="rating" required>
                                <option value="5" {{ old('rating') == 5 ? 'selected' : '' }}>5 Stars</option>
                                <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>4 Stars</option>
                                <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>3 Stars</option>
                                <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>2 Stars</option>
                                <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>1 Star</option>
                            </select>
                            @error('rating')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="order" class="form-label">Display Order</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', 0) }}">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>

                        <div class="mb-3" id="showcase-wrap">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_showcase" name="is_showcase" value="1" {{ old('is_showcase') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_showcase">Main Showcase Video</label>
                            </div>
                            <small class="text-muted d-block mt-1">If checked, this will be displayed as the main featured video on either the Testimonial or Awards page depending on the category selected.</small>
                        </div>

                        <div class="mb-3" id="short-wrap">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_short" name="is_short" value="1" {{ old('is_short') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_short">📱 YouTube Short (portrait video)</label>
                            </div>
                            <small class="text-muted d-block mt-1">Auto-detected on sync for videos ≤ 60 s. Toggle manually if needed.</small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Create Testimonial</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
