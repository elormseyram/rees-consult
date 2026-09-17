@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit Scholarship</h1>
            <a href="{{ route('admin.scholarships.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                @if ($scholarship)
                    <form id="scholarship-form" action="{{ route('admin.scholarships.update', $scholarship->id) }}"
                        method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Scholarship Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $scholarship->name) }}"
                                        required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="country" class="form-label">Country</label>
                                    <input type="text" class="form-control @error('country') is-invalid @enderror"
                                        id="country" name="country" value="{{ old('country', $scholarship->country) }}"
                                        required>
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="deadline" class="form-label">Deadline</label>
                                    <input type="text" class="form-control @error('deadline') is-invalid @enderror"
                                        id="deadline" name="deadline" value="{{ old('deadline', $scholarship->deadline) }}"
                                        placeholder="e.g., 31 JULY, 2026 or VARIES">
                                    @error('deadline')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <div id="description-editor" style="height: 250px;"></div>
                                    <input type="hidden" id="description" name="description"
                                        value="{{ old('description', $scholarship->description) }}">
                                    @error('description')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Requirements</label>
                                    <div id="requirements-container">
                                        @if ($scholarship->requirements && is_array($scholarship->requirements))
                                            @foreach ($scholarship->requirements as $requirement)
                                                <div class="input-group mb-2">
                                                    <input type="text" class="form-control" name="requirements[]"
                                                        value="{{ $requirement }}" placeholder="Enter a requirement">
                                                    <button class="btn btn-outline-danger" type="button"
                                                        onclick="removeRequirement(this)">Remove</button>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="input-group mb-2">
                                                <input type="text" class="form-control" name="requirements[]"
                                                    placeholder="Enter a requirement">
                                                <button class="btn btn-outline-danger" type="button"
                                                    onclick="removeRequirement(this)">Remove</button>
                                            </div>
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                        onclick="addRequirement()">
                                        <i class="bi bi-plus-lg"></i> Add Requirement
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="order" class="form-label">Display Order</label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror"
                                        id="order" name="order" value="{{ old('order', $scholarship->order) }}">
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                            value="1"
                                            {{ old('is_active', $scholarship->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Active</label>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Update Scholarship</button>
                                </div>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="alert alert-danger">Scholarship not found.</div>
                @endif
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
                placeholder: 'Write scholarship description here...',
            });

            // Set existing content
            quill.root.innerHTML = {!! json_encode(old('description', $scholarship->description)) !!};

            // Sync Quill content with hidden input on form submit
            const form = document.querySelector('#scholarship-form');
            form.addEventListener('submit', function() {
                document.querySelector('#description').value = quill.root.innerHTML;
            });

            function addRequirement() {
                const container = document.getElementById('requirements-container');
                const div = document.createElement('div');
                div.className = 'input-group mb-2';
                div.innerHTML = `
            <input type="text" class="form-control" name="requirements[]" placeholder="Enter a requirement">
            <button class="btn btn-outline-danger" type="button" onclick="removeRequirement(this)">Remove</button>
        `;
                container.appendChild(div);
            }

            function removeRequirement(button) {
                button.parentElement.remove();
            }
        </script>
    @endpush
@endsection
