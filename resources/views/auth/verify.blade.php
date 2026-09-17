@extends('layouts.auth')

@section('title', 'Verify Email')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <img src="{{ asset('reesconsult-logo.png') }}" alt="Rees Consult" style="max-height: 80px; margin-bottom: 20px;">
        <h2>Verify Email Address</h2>
        <p>Check your email for verification link</p>
    </div>

    <div class="auth-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
