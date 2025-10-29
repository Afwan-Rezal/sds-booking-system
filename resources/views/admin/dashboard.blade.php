@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Admin Dashboard</h1>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Welcome, Admin!</h5>
                    <p class="card-text">This is the admin dashboard. Expand upon this page in the future.</p>
                    
                    <div class="mt-3">
                        <p><strong>Your Role:</strong> Admin</p>
                        <p><strong>Full Name:</strong> {{ Auth::user()->profile->full_name ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Placeholder for future admin features -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Room Management</h5>
                            <p class="card-text">Manage rooms and their availability.</p>
                            <a href="{{ route('rooms.index') }}" class="btn btn-primary">View Rooms</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Pending Bookings</h5>
                            <p class="card-text">Review and approve booking requests.</p>
                            <a href="{{ route('admin.pending_bookings') }}" class="btn btn-warning">View Pending Bookings</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">My Bookings</h5>
                            <p class="card-text">View your personal room bookings.</p>
                            <a href="{{ route('bookings.list') }}" class="btn btn-primary">View My Bookings</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Users</h5>
                            <p class="card-text">View all users in the system.</p>
                            <a href="{{ route('admin.users') }}" class="btn btn-primary">View All Users</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Pending Staff</h5>
                            <p class="card-text">Approve or reject staff registrations.</p>
                            <a href="{{ route('admin.pending_staff') }}" class="btn btn-warning">Pending Staff</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

