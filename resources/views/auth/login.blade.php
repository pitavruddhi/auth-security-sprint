
@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="mb-4 text-center">Welcome Back</h2>

                <form method="POST" action="{{ route('login.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="email">Email Address</label>
                        <input type="email" id="email" name="email"
                               class="form-control"
                               value="{{ old('email') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" id="password" name="password"
                               class="form-control" required>
                    </div>

                    <div class="mb-3 text-end">
                        <a href="{{route('password.request')}}">
                            Forgot password?
                        </a>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Login
                    </button>
                </form>

                <p class="text-center mt-3 mb-0">
                    Don't have an account?
                    <a href="{{ route('register') }}">Register</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
