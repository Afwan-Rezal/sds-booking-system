@extends('layouts.app')
@section('title', 'Student Dashboard')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Student Dashboard</h1>
            
            <div class="card sds-card mb-4">
                <div class="card-header sds-card-header">
                    <h4 class="mb-0">Dashboard Overview</h4>
                </div>
                <div class="card-body sds-card-body">
                    <h5 class="card-title">Welcome, Student!</h5>
                    <p class="card-text">This is the student dashboard. Expand upon this page in the future.</p>
                    
                    <div class="mt-3">
                        <p><strong>Your Role:</strong> Student</p>
                        <p><strong>Full Name:</strong> {{ Auth::user()->profile->full_name ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Placeholder for future student features -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card sds-card">
                        <div class="card-header sds-card-header">
                            <h5 class="mb-0">Book a Room</h5>
                        </div>
                        <div class="card-body sds-card-body">
                            <p class="card-text">Reserve a room for your studies.</p>
                            <a href="{{ route('rooms.index') }}" class="btn btn-primary">View Available Rooms</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card sds-card">
                        <div class="card-header sds-card-header">
                            <h5 class="mb-0">My Bookings</h5>
                        </div>
                        <div class="card-body sds-card-body">
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

