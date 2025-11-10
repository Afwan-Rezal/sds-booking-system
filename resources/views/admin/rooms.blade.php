@extends('layouts.app')
@section('title', 'Manage Rooms - Admin')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Manage Rooms</h2>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Rooms Overview</h5>
                </div>
                <div class="card-body">
                    @if($rooms->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Availability</th>
                                        <th>Blocked</th>
                                        <th>Reason</th>
                                        <th>Capacity</th>
                                        <th>Type</th>
                                        <th>Location</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rooms as $room)
                                        @php($meta = $room->metadata)
                                        <tr>
                                            <td>{{ $room->name }}</td>
                                            <td>
                                                @php($meta = $room->metadata)
                                                <span class="badge {{ ($meta && !optional($meta)->is_blocked) ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ ($meta && !optional($meta)->is_blocked) ? 'Available' : 'Unavailable' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($meta && $meta->is_blocked)
                                                    <span class="badge bg-danger">Blocked</span>
                                                @else
                                                    <span class="badge bg-success">Open</span>
                                                @endif
                                            </td>
                                            <td style="max-width: 220px;">
                                                @if($meta && $meta->is_blocked && $meta->blocked_reason)
                                                    <span class="text-muted">{{ $meta->blocked_reason }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>{{ $meta->capacity ?? 'N/A' }}</td>
                                            <td>{{ $meta->type ?? 'N/A' }}</td>
                                            <td>{{ $meta->location ?? 'N/A' }}</td>
                                            <td class="text-end">
                                                @if(! $meta)
                                                    <span class="text-danger small">Missing metadata</span>
                                                @elseif($meta->is_blocked)
                                                    <form action="{{ route('admin.rooms.unblock', $room) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-success btn-sm">
                                                            Unblock
                                                        </button>
                                                    </form>
                                                @else
                                                    <a href="{{ route('admin.rooms.block', $room) }}" class="btn btn-warning btn-sm">
                                                        Block
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">No rooms found.</p>
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

