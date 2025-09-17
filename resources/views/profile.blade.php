@extends('layouts.app')
@section('title', 'User Profile')

@section('content')
    @if (!Auth::check())
        <div class="container mt-4">
            <h1>User Profile</h1>
            <p>Please <a href="{{ route('auth') }}">login</a> to view your profile.</p>
        </div>
    @else
        <div class="container mt-4">
            <h1>User Profile</h1>
            <div class="card">
                <div class="card-body">
                    <p><strong>Name:</strong> {{ Auth::user()->profile->full_name ?? 'N/A' }}</p>
                    <p><strong>Gender:</strong> {{ Auth::user()->profile->gender ?? 'N/A' }}</p>
                    <p><strong>Role:</strong> {{ Auth::user()->profile->role ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    @endif
@endsection