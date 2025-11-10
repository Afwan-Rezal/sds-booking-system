@extends('layouts.app')
@section('title', 'Pending Bookings - Admin')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Pending Booking Requests</h2>

            @if(session('error') || $errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    @if(session('error'))
                        {{ session('error') }}
                    @else
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="alert alert-info">
                <strong>Note:</strong> Review and approve or reject room booking requests from users.
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Pending Approval Requests</h5>
                </div>
                <div class="card-body">
                    @if($bookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Room</th>
                                        <th>Date</th>
                                        <th>Time Slot</th>
                                        <th>People</th>
                                        <th>Purpose</th>
                                        <th>Submitted</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                        <tr>
                                            <td>{{ $booking->id }}</td>
                                            <td>
                                                <div>
                                                    <strong>{{ $booking->user->profile->full_name ?? $booking->user->username }}</strong><br>
                                                    <small class="text-muted">{{ $booking->user->email }}</small>
                                                </div>
                                            </td>
                                            <td>{{ $booking->room->name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($booking->date)->format('Y-m-d') }}</td>
                                            <td>{{ date('H:i', strtotime($booking->start_time)) }} - {{ date('H:i', strtotime($booking->end_time)) }}</td>
                                            <td>{{ $booking->number_of_people }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#purposeModal{{ $booking->id }}">
                                                    View Purpose
                                                </button>
                                            </td>
                                            <td>{{ $booking->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('admin.approve_booking', $booking->id) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.reject_booking', $booking->id) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to reject this booking request?');">Reject</button>
                                                </form>
                                            </td>
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
                    @else
                        <p class="text-muted">No pending booking requests.</p>
                    @endif
                </div>
            </div>
            
            <div class="mt-3">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>
@endsection

