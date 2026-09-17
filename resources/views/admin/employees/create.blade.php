@extends('admin.layouts.app')

@section('title', 'Add Employee')

@section('content')
<div class="container-fluid" style="max-width: 760px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="bi bi-person-plus-fill me-2" style="color:#F8A706;"></i>Add Employee</h1>
        <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back</a>
    </div>

    <div class="card border-0">
        <div class="card-body">
            <form action="{{ route('admin.employees.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Full name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email (used to log in)</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror">
                            <option value="employee" {{ old('role','employee') === 'employee' ? 'selected' : '' }}>Employee (limited access)</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator (full access)</option>
                        </select>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Employees can manage leads, applications, consultations &amp; the inbox. Admins can manage everything.</div>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active (can log in)</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Minimum 8 characters.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Confirm password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> Create Account</button>
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-light border">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
