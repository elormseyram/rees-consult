@extends('admin.layouts.app')

@section('title', 'Edit ' . $employee->name)

@php $editingSelf = $employee->id === auth()->id(); @endphp

@section('content')
<div class="container-fluid" style="max-width: 760px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="bi bi-person-gear me-2" style="color:#F8A706;"></i>Edit Account</h1>
        <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back</a>
    </div>

    <div class="card border-0">
        <div class="card-body">
            <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST">
                @csrf @method('PUT')

                @if($editingSelf)
                    {{-- Disabled controls below don't submit; these keep validation happy (controller forces these values anyway). --}}
                    <input type="hidden" name="role" value="admin">
                    <input type="hidden" name="is_active" value="1">
                @endif

                @if($editingSelf)
                    <div class="alert alert-info py-2 small"><i class="bi bi-info-circle me-1"></i> This is your own account — role and active status are locked to prevent locking yourself out.</div>
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Full name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $employee->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email (used to log in)</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $employee->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" {{ $editingSelf ? 'disabled' : '' }}>
                            <option value="employee" {{ old('role', $employee->role) === 'employee' ? 'selected' : '' }}>Employee (limited access)</option>
                            <option value="admin" {{ old('role', $employee->role) === 'admin' ? 'selected' : '' }}>Administrator (full access)</option>
                        </select>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $employee->is_active) ? 'checked' : '' }} {{ $editingSelf ? 'disabled' : '' }}>
                            <label class="form-check-label" for="is_active">Active (can log in)</label>
                        </div>
                    </div>

                    <div class="col-12"><hr class="text-muted"></div>
                    <div class="col-12"><p class="text-muted small mb-0">Leave password fields blank to keep the current password.</p></div>

                    <div class="col-md-6">
                        <label for="password" class="form-label">New password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Confirm new password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> Save Changes</button>
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-light border">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
