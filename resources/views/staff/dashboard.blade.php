@extends('layouts.app')
@section('title', 'Staff Dashboard')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Staff Dashboard</h1>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Welcome, Staff Member!</h5>
                    <p class="card-text">This is the staff dashboard. Expand upon this page in the future.</p>
                    
                    <div class="mt-3">
                        <p><strong>Your Role:</strong> Staff</p>
                        <p><strong>Full Name:</strong> {{ Auth::user()->profile->full_name ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Placeholder for future staff features -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Book a Room</h5>
                            <p class="card-text">Reserve a room for your needs.</p>
                            <a href="{{ route('rooms.index') }}" class="btn btn-primary">View Available Rooms</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">My Bookings</h5>
                            <p class="card-text">View your room bookings.</p>
                            <a href="{{ route('bookings.list') }}" class="btn btn-primary">View My Bookings</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

