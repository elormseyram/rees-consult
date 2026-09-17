@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add Award</h1>
        <a href="{{ route('admin.awards.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.awards.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    {{-- Left column --}}
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">Award Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title') }}"
                                   placeholder="e.g. Best IELTS Prep Centre 2024" required>
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="awarded_by" class="form-label fw-semibold">Awarded By</label>
                            <input type="text" class="form-control @error('awarded_by') is-invalid @enderror"
                                   id="awarded_by" name="awarded_by" value="{{ old('awarded_by') }}"
                                   placeholder="e.g. British Council, IDP Education">
                            @error('awarded_by') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="award_date" class="form-label fw-semibold">Date Received</label>
                            <input type="date" class="form-control @error('award_date') is-invalid @enderror"
                                   id="award_date" name="award_date" value="{{ old('award_date') }}">
                            @error('award_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="4"
                                      placeholder="Brief description of the award or achievement…">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Right column --}}
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="photo" class="form-label fw-semibold">Award Photo / Certificate</label>
                            <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                   id="photo" name="photo" accept="image/*">
                            <div class="form-text">JPG, PNG, WEBP — max 4 MB</div>
                            @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div id="photo-preview" class="mt-2 d-none">
                                <img id="preview-img" src="" alt="Preview"
                                     style="max-width:100%; border-radius:8px; border:1px solid #dee2e6;">
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active"
                                       name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_active">Active (visible on site)</label>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-trophy-fill me-1"></i> Save Award
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('photo').addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('photo-preview').classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
