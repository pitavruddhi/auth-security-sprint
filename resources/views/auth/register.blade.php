
@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="mb-4 text-center">Create Account</h2>

                <form method="POST" action="{{ route('register.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="name">Full Name</label>
                        <input type="text" id="name" name="name"
                               class="form-control"
                               value="{{ old('name') }}" required>
                    </div>

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

                    <div class="mb-3">
                        <label class="form-label" for="password_confirmation">
                            Confirm Password
                        </label>
                        <input type="password" id="password_confirmation"
                               name="password_confirmation"
                               class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Register
                    </button>
                </form>

                <p class="text-center mt-3 mb-0">
                    Already registered?
                    <a href="{{ route('login') }}">Login</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
