@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{ isset($event) ? 'Edit Event' : 'Create Event' }}</h1>
            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <form id="event-form"
                    action="{{ isset($event) ? route('admin.events.update', $event) : route('admin.events.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($event))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Event Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title', $event->title ?? '') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <div id="description-editor" style="height: 200px;"></div>
                                <input type="hidden" id="description" name="description"
                                    value="{{ old('description', $event->description ?? '') }}">
                                @error('description')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_time" class="form-label">Start Date & Time</label>
                                        <input type="datetime-local"
                                            class="form-control @error('start_time') is-invalid @enderror" id="start_time"
                                            name="start_time"
                                            value="{{ old('start_time', isset($event) ? $event->start_time->format('Y-m-d\TH:i') : '') }}"
                                            required>
                                        @error('start_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_time" class="form-label">End Date & Time</label>
                                        <input type="datetime-local"
                                            class="form-control @error('end_time') is-invalid @enderror" id="end_time"
                                            name="end_time"
                                            value="{{ old('end_time', isset($event) ? $event->end_time->format('Y-m-d\TH:i') : '') }}"
                                            required>
                                        @error('end_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror"
                                    id="location" name="location" value="{{ old('location', $event->location ?? '') }}"
                                    required>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="price" class="form-label">Price (Leave empty or 0 for Free)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01"
                                        class="form-control @error('price') is-invalid @enderror" id="price"
                                        name="price" value="{{ old('price', $event->price ?? '') }}">
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Event Image</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    id="image" name="image" accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if (isset($event) && $event->image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $event->image) }}" alt="Current Image"
                                            class="img-fluid rounded" style="max-height: 200px;">
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                        value="1" {{ old('is_active', $event->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Active Status</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4">
                            {{ isset($event) ? 'Update Event' : 'Create Event' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Quill CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <!-- Quill JS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
        // Initialize Quill editor
        const quill = new Quill('#description-editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        'header': [1, 2, 3, 4, 5, 6, false]
                    }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{
                        'color': []
                    }, {
                        'background': []
                    }],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }],
                    [{
                        'align': []
                    }],
                    ['blockquote', 'code-block'],
                    ['link'],
                    ['clean']
                ]
            },
            placeholder: 'Write event description here...',
        });

        // Set existing content (for both edit mode and validation errors in create mode)
        quill.root.innerHTML = {!! json_encode(old('description', isset($event) ? $event->description : '')) !!};

        // Sync Quill content with hidden input on form submit
        const form = document.querySelector('#event-form');
        form.addEventListener('submit', function() {
            document.querySelector('#description').value = quill.root.innerHTML;
        });
    </script>
@endpush
