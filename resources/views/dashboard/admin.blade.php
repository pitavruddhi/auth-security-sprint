
@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <h2>Admin Dashboard</h2>
        <p>Welcome, {{ auth()->user()->name }}!</p>
        <p>You are logged in as an administrator.</p>
    </div>
</div>
@endsection
