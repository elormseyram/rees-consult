@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add Pricing</h1>
        <a href="{{ route('admin.pricings.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.pricings.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="test_name" class="form-label">Test Name</label>
                            <input type="text" class="form-control @error('test_name') is-invalid @enderror" id="test_name" name="test_name" value="{{ old('test_name') }}" placeholder="e.g., IELTS" required>
                            @error('test_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="group_in_person" class="form-label">Group (In Person) (GHS)</label>
                                    <input type="number" class="form-control @error('group_in_person') is-invalid @enderror" id="group_in_person" name="group_in_person" value="{{ old('group_in_person', 0) }}" min="0" required>
                                    @error('group_in_person')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="group_online" class="form-label">Group (Online) (GHS)</label>
                                    <input type="number" class="form-control @error('group_online') is-invalid @enderror" id="group_online" name="group_online" value="{{ old('group_online', 0) }}" min="0" required>
                                    @error('group_online')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="one_on_one_in_person" class="form-label">One-on-One (In Person) (GHS)</label>
                                    <input type="number" class="form-control @error('one_on_one_in_person') is-invalid @enderror" id="one_on_one_in_person" name="one_on_one_in_person" value="{{ old('one_on_one_in_person', 0) }}" min="0" required>
                                    @error('one_on_one_in_person')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="one_on_one_online" class="form-label">One-on-One (Online) (GHS)</label>
                                    <input type="number" class="form-control @error('one_on_one_online') is-invalid @enderror" id="one_on_one_online" name="one_on_one_online" value="{{ old('one_on_one_online', 0) }}" min="0" required>
                                    @error('one_on_one_online')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="order" class="form-label">Display Order</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', 0) }}" min="0">
                            <small class="text-muted">Lower numbers appear first</small>
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
                            <button type="submit" class="btn btn-primary">Add Pricing</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
