
@extends('layouts.app')

@section('title', 'Customer Dashboard')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <h2>Customer Dashboard</h2>
        <p>Welcome, {{ auth()->user()->name }}!</p>
        <p>You are logged in as a customer.</p>
        <a href="{{route('profile.edit')}}" class="btn btn-primary">
            Update Profile
        </a>
    </div>
</div>
@endsection
