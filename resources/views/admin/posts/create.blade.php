@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Create New Post</h1>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <form id="post-form" action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Post Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">Content</label>
                                <div id="content-editor" style="height: 300px;"></div>
                                <input type="hidden" id="content" name="content" value="{{ old('content') }}">
                                @error('content')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="published_at" class="form-label">Publish Date</label>
                                <input type="datetime-local"
                                    class="form-control @error('published_at') is-invalid @enderror" id="published_at"
                                    name="published_at" value="{{ old('published_at') }}">
                                <div class="form-text">Leave blank to save as draft.</div>
                                @error('published_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Featured Image</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    id="image" name="image" accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Create Post</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Quill CSS -->
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

        <!-- Quill JS -->
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

        <script>
            // Initialize Quill editor
            const quill = new Quill('#content-editor', {
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
                        ['link', 'image'],
                        ['clean']
                    ]
                },
                placeholder: 'Write your post content here...',
            });

            // Set existing content (important for validation errors)
            quill.root.innerHTML = {!! json_encode(old('content')) !!};

            // Sync Quill content with hidden input on form submit
            const form = document.querySelector('#post-form');
            form.addEventListener('submit', function() {
                document.querySelector('#content').value = quill.root.innerHTML;
            });
        </script>
    @endpush
@endsection
