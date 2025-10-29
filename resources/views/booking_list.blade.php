@extends('layouts.app')
@section('title', 'My Bookings')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">My Bookings</h2>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($bookings->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">All My Bookings</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Room</th>
                                        <th>Date</th>
                                        <th>Time Slot</th>
                                        <th>Number of People</th>
                                        <th>Purpose</th>
                                        <th>Status</th>
                                        <th>Requested On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                        <tr>
                                            <td>#{{ $booking->id }}</td>
                                            <td>{{ $booking->room->name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($booking->date)->format('Y-m-d') }}</td>
                                            <td>{{ date('H:i', strtotime($booking->start_time)) }} - {{ date('H:i', strtotime($booking->end_time)) }}</td>
                                            <td>{{ $booking->number_of_people }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#purposeModal{{ $booking->id }}">
                                                    View Purpose
                                                </button>
                                            </td>
                                            <td>
                                                @if($booking->status === 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($booking->status === 'pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @elseif($booking->status === 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $booking->created_at->format('Y-m-d H:i') }}</td>
                                        </tr>

                                        <!-- Purpose Modal -->
                                        <div class="modal fade" id="purposeModal{{ $booking->id }}" tabindex="-1" aria-labelledby="purposeModalLabel{{ $booking->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="purposeModalLabel{{ $booking->id }}">Reason for Request</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>{{ $booking->purpose }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Cards -->
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Approved</h5>
                                        <h2>{{ $bookings->where('status', 'approved')->count() }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-warning text-dark">
                                    <div class="card-body">
                                        <h5 class="card-title">Pending</h5>
                                        <h2>{{ $bookings->where('status', 'pending')->count() }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-danger text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Rejected</h5>
                                        <h2>{{ $bookings->where('status', 'rejected')->count() }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info">
                    <h5>No bookings found</h5>
                    <p>You haven't made any room booking requests yet.</p>
                    <a href="{{ route('rooms.index') }}" class="btn btn-primary">Book a Room</a>
                </div>
            @endif

            <div class="mt-3">
                <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Back to Room Listings</a>
                @auth
                    @php
                        $role = strtolower(Auth::user()->profile->role ?? '');
                    @endphp
                    @if($role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Dashboard</a>
                    @elseif($role === 'staff')
                        <a href="{{ route('staff.dashboard') }}" class="btn btn-secondary">Dashboard</a>
                    @elseif($role === 'student')
                        <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">Dashboard</a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection


