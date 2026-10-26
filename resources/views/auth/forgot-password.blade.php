
@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="mb-3 text-center">Forgot Password?</h2>

                <p class="text-muted text-center">
                    Enter your registered email address to request a
                    password reset link.
                </p>

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">
                            Email Address
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >

                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Send Reset Link
                    </button>
                </form>

                <p class="text-center mt-3 mb-0">
                    <a href="{{ route('login') }}">Back to Login</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
