@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit Service</h1>
            <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <form id="service-form" action="{{ route('admin.services.update', $service) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Service Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title', $service->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="subtitle" class="form-label">Subtitle / Hook</label>
                                <input type="text" class="form-control @error('subtitle') is-invalid @enderror"
                                    id="subtitle" name="subtitle" value="{{ old('subtitle', $service->subtitle) }}" placeholder="e.g. Relocate to the UK with legal visa sponsorships">
                                @error('subtitle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <div id="description-editor" style="height: 250px;"></div>
                                <input type="hidden" id="description" name="description"
                                    value="{{ old('description', $service->description) }}">
                                @error('description')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4">

                            <!-- Tier 2: Features -->
                            <div class="mb-4">
                                <label class="form-label d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Program Features / Highlights</span>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="add-feature-btn">
                                        <i class="bi bi-plus-lg"></i> Add Feature
                                    </button>
                                </label>
                                <div id="features-container">
                                    @php
                                        $oldFeatures = old('features', $service->features ?? ['']);
                                        if (empty($oldFeatures) || !is_array($oldFeatures)) {
                                            $oldFeatures = [''];
                                        }
                                    @endphp
                                    @foreach($oldFeatures as $index => $feature)
                                        <div class="input-group mb-2 feature-row">
                                            <input type="text" name="features[]" class="form-control" value="{{ $feature }}" placeholder="e.g. Personalized 1-on-1 speaking evaluations">
                                            <button type="button" class="btn btn-outline-danger remove-row-btn">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                                <small class="text-muted">Key highlights of this service. Empty fields are automatically ignored.</small>
                            </div>

                            <!-- Tier 2: Why Choose Program -->
                            <div class="mb-4">
                                <label class="form-label d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Why Choose This Program (Reasons/Benefits)</span>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="add-reason-btn">
                                        <i class="bi bi-plus-lg"></i> Add Reason
                                    </button>
                                </label>
                                <div id="reasons-container">
                                    @php
                                        $oldReasons = old('why_choose_program', $service->why_choose_program ?? ['']);
                                        if (empty($oldReasons) || !is_array($oldReasons)) {
                                            $oldReasons = [''];
                                        }
                                    @endphp
                                    @foreach($oldReasons as $index => $reason)
                                        <div class="input-group mb-2 reason-row">
                                            <input type="text" name="why_choose_program[]" class="form-control" value="{{ $reason }}" placeholder="e.g. Proven 95% pass rate on first attempt">
                                            <button type="button" class="btn btn-outline-danger remove-row-btn">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                                <small class="text-muted">Reasons/benefits to show on the detail page. Empty fields are automatically ignored.</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="category" class="form-label">Service Category</label>
                                <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" onchange="toggleCountryField()" required>
                                    <option value="standardized_test" {{ old('category', $service->category) === 'standardized_test' ? 'selected' : '' }}>Standardized Test Preparation</option>
                                    <option value="school_application" {{ old('category', $service->category) === 'school_application' ? 'selected' : '' }}>School Placement / Application</option>
                                    <option value="job_abroad" {{ old('category', $service->category) === 'job_abroad' ? 'selected' : '' }}>Job Placement Abroad</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Program Fee (USD)</label>
                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                    id="price" name="price" value="{{ old('price', $service->price) }}" min="0" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="processing_fee" class="form-label">Visa / Support Processing Fee (USD)</label>
                                <input type="number" step="0.01" class="form-control @error('processing_fee') is-invalid @enderror"
                                    id="processing_fee" name="processing_fee" value="{{ old('processing_fee', $service->processing_fee) }}" min="0">
                                @error('processing_fee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3" id="country-container" style="display: none;">
                                <label for="country" class="form-label">Target Placement Country</label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror"
                                    id="country" name="country" value="{{ old('country', $service->country) }}" placeholder="e.g. United Kingdom, Canada">
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="color" class="form-label">Accent Color (Hex)</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color p-1" id="color-picker" value="{{ old('color', $service->color ?? '#2E5BBA') }}" title="Choose your color" style="max-width: 45px; height: 38px;">
                                    <input type="text" class="form-control @error('color') is-invalid @enderror" id="color" name="color" value="{{ old('color', $service->color ?? '#2E5BBA') }}" placeholder="#2E5BBA" pattern="^#[0-9a-fA-F]{6}$">
                                    @error('color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Used for the card border and accent color</small>
                            </div>

                            <div class="mb-3">
                                <label for="duration" class="form-label">Duration</label>
                                <input type="text" class="form-control @error('duration') is-invalid @enderror"
                                    id="duration" name="duration" value="{{ old('duration', $service->duration) }}" placeholder="e.g. 8 Weeks, 12 Months">
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="class_size" class="form-label">Class Size</label>
                                <input type="text" class="form-control @error('class_size') is-invalid @enderror"
                                    id="class_size" name="class_size" value="{{ old('class_size', $service->class_size) }}" placeholder="e.g. Max 12 Students">
                                @error('class_size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="icon" class="form-label">Bootstrap Icon Class</label>
                                <input type="text" class="form-control @error('icon') is-invalid @enderror"
                                    id="icon" name="icon" value="{{ old('icon', $service->icon) }}"
                                    placeholder="bi-dot">
                                <small class="text-muted">e.g., bi-book, bi-briefcase, bi-mortarboard</small>
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="order" class="form-label">Display Order</label>
                                <input type="number" class="form-control @error('order') is-invalid @enderror"
                                    id="order" name="order" value="{{ old('order', $service->order) }}">
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                        value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Active Status</label>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">Update Service</button>
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
                        ['link', 'image'],
                        ['clean']
                    ]
                },
                placeholder: 'Write your service description here...',
            });

            // Set existing content
            quill.root.innerHTML = {!! json_encode(old('description', $service->description)) !!};

            // Sync Quill content with hidden input on form submit
            const form = document.querySelector('#service-form');
            form.addEventListener('submit', function() {
                document.querySelector('#description').value = quill.root.innerHTML;
            });

            // Toggle target country select fields depending on selected category
            function toggleCountryField() {
                const categorySelect = document.getElementById('category');
                const countryContainer = document.getElementById('country-container');
                if (categorySelect.value === 'job_abroad') {
                    countryContainer.style.display = 'block';
                } else {
                    countryContainer.style.display = 'none';
                }
            }

            // Run on load to secure correct state
            document.addEventListener('DOMContentLoaded', toggleCountryField);

            // Dynamic rows for Features
            const featuresContainer = document.getElementById('features-container');
            const addFeatureBtn = document.getElementById('add-feature-btn');
            
            if (addFeatureBtn && featuresContainer) {
                addFeatureBtn.addEventListener('click', function() {
                    const newRow = document.createElement('div');
                    newRow.className = 'input-group mb-2 feature-row';
                    newRow.innerHTML = `
                        <input type="text" name="features[]" class="form-control" placeholder="e.g. Personalized 1-on-1 speaking evaluations">
                        <button type="button" class="btn btn-outline-danger remove-row-btn">
                            <i class="bi bi-trash"></i>
                        </button>
                    `;
                    featuresContainer.appendChild(newRow);
                });
            }

            // Dynamic rows for Why Choose Program
            const reasonsContainer = document.getElementById('reasons-container');
            const addReasonBtn = document.getElementById('add-reason-btn');
            
            if (addReasonBtn && reasonsContainer) {
                addReasonBtn.addEventListener('click', function() {
                    const newRow = document.createElement('div');
                    newRow.className = 'input-group mb-2 reason-row';
                    newRow.innerHTML = `
                        <input type="text" name="why_choose_program[]" class="form-control" placeholder="e.g. Proven 95% pass rate on first attempt">
                        <button type="button" class="btn btn-outline-danger remove-row-btn">
                            <i class="bi bi-trash"></i>
                        </button>
                    `;
                    reasonsContainer.appendChild(newRow);
                });
            }

            // Handle event delegation for removing rows
            document.addEventListener('click', function(e) {
                if (e.target && (e.target.classList.contains('remove-row-btn') || e.target.closest('.remove-row-btn'))) {
                    const btn = e.target.classList.contains('remove-row-btn') ? e.target : e.target.closest('.remove-row-btn');
                    const row = btn.parentElement;
                    const container = row.parentElement;
                    // Keep at least one input if it's the only one left
                    if (container.children.length > 1) {
                        row.remove();
                    } else {
                        row.querySelector('input').value = '';
                    }
                }
            });

            // Color picker & text input synchronization
            const colorPicker = document.getElementById('color-picker');
            const colorInput = document.getElementById('color');

            if (colorPicker && colorInput) {
                colorPicker.addEventListener('input', function() {
                    colorInput.value = colorPicker.value.toUpperCase();
                });

                colorInput.addEventListener('input', function() {
                    let val = colorInput.value;
                    if (val.startsWith('#') && val.length === 7) {
                        colorPicker.value = val;
                    }
                });
            }
        </script>
    @endpush
@endsection
