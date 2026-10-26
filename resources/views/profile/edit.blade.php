
@extends('layouts.app')

@section('title', 'Update Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="mb-2">My Profile</h2>
                <p class="text-muted mb-4">
                    Update your personal information.
                </p>

                <div class="mb-3">
                    <label class="form-label">Account Role</label>
                    <input
                        type="text"
                        class="form-control"
                        value="{{ ucfirst($user->role) }}"
                        disabled
                    >
                    <div class="form-text">
                        Your account role cannot be changed here.
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">
                            Full Name
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}"
                            required
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">
                            Email Address
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}"
                            required
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Save Changes
                    </button>
                </form>

                <a href="{{ url()->previous() }}"
                   class="btn btn-outline-secondary w-100 mt-2">
                    Back
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
