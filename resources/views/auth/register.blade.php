@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <img src="{{ asset('reesconsult-logo.png') }}" alt="Rees Consult" style="max-height: 80px; margin-bottom: 20px;">
        <h2>Create Account</h2>
        <p>Join us to start your journey</p>
    </div>

    <div class="auth-body">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">
                    <i class="bi bi-person me-2"></i>Full Name
                </label>
                <input id="name" 
                       type="text" 
                       class="form-control @error('name') is-invalid @enderror" 
                       name="name" 
                       value="{{ old('name') }}" 
                       required 
                       autocomplete="name" 
                       autofocus
                       placeholder="Enter your full name">

                @error('name')
                    <div class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">
                    <i class="bi bi-envelope me-2"></i>Email Address
                </label>
                <input id="email" 
                       type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autocomplete="email"
                       placeholder="Enter your email">

                @error('email')
                    <div class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">
                    <i class="bi bi-lock me-2"></i>Password
                </label>
                <input id="password" 
                       type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       name="password" 
                       required 
                       autocomplete="new-password"
                       placeholder="Create a password">

                @error('password')
                    <div class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password-confirm" class="form-label">
                    <i class="bi bi-lock-fill me-2"></i>Confirm Password
                </label>
                <input id="password-confirm" 
                       type="password" 
                       class="form-control" 
                       name="password_confirmation" 
                       required 
                       autocomplete="new-password"
                       placeholder="Confirm your password">
            </div>

            <button type="submit" class="btn btn-auth">
                <i class="bi bi-person-plus me-2"></i>Register
            </button>
        </form>
    </div>

    <div class="auth-footer">
        Already have an account? 
        <a href="{{ route('login') }}">Login Here</a>
    </div>
</div>
@endsection
