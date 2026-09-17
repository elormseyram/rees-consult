@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Award</h1>
        <a href="{{ route('admin.awards.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.awards.update', $award) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="row">
                    {{-- Left column --}}
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">Award Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title', $award->title) }}" required>
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="awarded_by" class="form-label fw-semibold">Awarded By</label>
                            <input type="text" class="form-control @error('awarded_by') is-invalid @enderror"
                                   id="awarded_by" name="awarded_by" value="{{ old('awarded_by', $award->awarded_by) }}">
                            @error('awarded_by') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="award_date" class="form-label fw-semibold">Date Received</label>
                            <input type="date" class="form-control @error('award_date') is-invalid @enderror"
                                   id="award_date" name="award_date"
                                   value="{{ old('award_date', $award->award_date?->format('Y-m-d')) }}">
                            @error('award_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="4">{{ old('description', $award->description) }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Right column --}}
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="photo" class="form-label fw-semibold">Award Photo / Certificate</label>
                            @if($award->photo)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $award->photo) }}" id="preview-img"
                                         alt="{{ $award->title }}"
                                         style="max-width:100%; border-radius:8px; border:1px solid #dee2e6;">
                                </div>
                                <div class="form-text text-muted mb-2">Upload a new photo to replace the current one.</div>
                            @else
                                <div id="photo-preview" class="mb-2 d-none">
                                    <img id="preview-img" src="" alt="Preview"
                                         style="max-width:100%; border-radius:8px; border:1px solid #dee2e6;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                   id="photo" name="photo" accept="image/*">
                            <div class="form-text">JPG, PNG, WEBP — max 4 MB</div>
                            @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="order" class="form-label fw-semibold">Display Order</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror"
                                   id="order" name="order" value="{{ old('order', $award->order) }}" min="0">
                            @error('order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active"
                                       name="is_active" value="1" {{ old('is_active', $award->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_active">Active (visible on site)</label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-trophy-fill me-1"></i> Update Award
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <form action="{{ route('admin.awards.destroy', $award) }}" method="POST"
                  onsubmit="return confirm('Permanently delete this award?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline-danger w-100">
                    <i class="bi bi-trash-fill me-1"></i> Delete
                </button>
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
            const img = document.getElementById('preview-img');
            if (img) { img.src = e.target.result; }
            const wrap = document.getElementById('photo-preview');
            if (wrap) { wrap.classList.remove('d-none'); }
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
