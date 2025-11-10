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
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                        <tr class="selectable-row" data-booking-id="{{ $booking->id }}">
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
                                                @elseif($booking->status === 'completed')
                                                    <span class="badge bg-info">Completed</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $booking->created_at->format('Y-m-d H:i') }}</td>
                                            <td class="row-actions" style="white-space:nowrap;">
                                                @if($booking->status !== 'completed' && $booking->status !== 'rejected' && $booking->status !== 'cancelled')
                                                    <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-sm btn-primary me-1">Edit</a>
                                                    {{-- <button type="button" class="btn btn-sm btn-danger btn-cancel" data-booking-id="{{ $booking->id }}" data-bs-toggle="modal" data-bs-target="#cancelModal">Cancel</button> --}}
                                                @else
                                                    <span class="text-muted">Actions unavailable</span>
                                                @endif
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

                                        <!-- Hidden cancellation form for this booking -->
                                        <form id="deleteForm{{ $booking->id }}" action="{{ route('bookings.cancel', $booking->id) }}" method="POST" style="display:none;">
                                            @csrf
                                            <input type="hidden" name="cancellation_reason" value="">
                                        </form>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Cards -->
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Approved</h5>
                                        <h2>{{ $bookings->where('status', 'approved')->count() }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-dark">
                                    <div class="card-body">
                                        <h5 class="card-title">Pending</h5>
                                        <h2>{{ $bookings->where('status', 'pending')->count() }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-danger text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Rejected</h5>
                                        <h2>{{ $bookings->where('status', 'rejected')->count() }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Completed</h5>
                                        <h2>{{ $bookings->where('status', 'completed')->count() }}</h2>
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

@section('scripts')

<!-- Cancel modal (single) -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Cancel Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="cancelReason" class="form-label">Reason for cancellation (will be saved in purpose)</label>
                    <textarea id="cancelReason" class="form-control" rows="3"></textarea>
                </div>
                <input type="hidden" id="cancelBookingId" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="confirmCancelBtn">Confirm Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hide all per-row actions initially
    document.querySelectorAll('.row-actions').forEach(function(td) {
        td.classList.add('d-none');
    });

    // Row click shows actions for that row only
    document.querySelectorAll('.selectable-row').forEach(function(row) {
        row.addEventListener('click', function(e) {
            // If clicked an interactive element (button/link), let it handle the event
            if (e.target.closest('a') || e.target.closest('button') || e.target.closest('[data-bs-toggle]')) return;

            // Toggle selection
            var bookingId = row.getAttribute('data-booking-id');

            // Hide actions on all rows
            document.querySelectorAll('.row-actions').forEach(function(td) {
                td.classList.add('d-none');
            });

            // Show actions for clicked row
            var actions = row.querySelector('.row-actions');
            if (actions) {
                actions.classList.remove('d-none');
            }
        });
    });

    // Wire cancel buttons to populate modal with booking id
    document.querySelectorAll('.btn-cancel').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            var id = btn.getAttribute('data-booking-id');
            document.getElementById('cancelBookingId').value = id;
            // Clear previous reason
            document.getElementById('cancelReason').value = '';
        });
    });

    // Confirm cancel: set hidden form value and submit per-row form
    document.getElementById('confirmCancelBtn').addEventListener('click', function() {
        var bookingId = document.getElementById('cancelBookingId').value;
        var reason = document.getElementById('cancelReason').value || 'Cancelled by user';
        var form = document.getElementById('deleteForm' + bookingId);
        if (!form) {
            console.error('Delete form not found for booking', bookingId);
            return;
        }
        // set hidden input
        var input = form.querySelector('input[name="cancellation_reason"]');
        if (input) input.value = reason;
        // Submit the form
        form.submit();
    });
});
</script>
@endsection


