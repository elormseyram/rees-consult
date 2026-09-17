@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <img src="{{ asset('reesconsult-logo.png') }}" alt="Rees Consult" style="max-height: 80px; margin-bottom: 20px;">
        <h2>Welcome Back</h2>
        <p>Login to access your account</p>
    </div>

    <div class="auth-body">
        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

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
                       autofocus
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
                       autocomplete="current-password"
                       placeholder="Enter your password">

                @error('password')
                    <div class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input class="form-check-input" 
                       type="checkbox" 
                       name="remember" 
                       id="remember" 
                       {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    Remember Me
                </label>
            </div>

            <button type="submit" class="btn btn-auth">
                <i class="bi bi-box-arrow-in-right me-2"></i>Login
            </button>

            @if (Route::has('password.request'))
                <div class="text-center mt-3">
                    <a href="{{ route('password.request') }}" class="text-muted">
                        <i class="bi bi-question-circle me-1"></i>Forgot Your Password?
                    </a>
                </div>
            @endif
        </form>
    </div>

    @if (Route::has('register'))
    <div class="auth-footer">
        Don't have an account? 
        <a href="{{ route('register') }}">Register Now</a>
    </div>
    @endif
</div>
@endsection
