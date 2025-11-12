@extends('layouts.app')
@section('title', 'Room Listing')

@section('content')
    <div class="container mt-4">
        <h2>Available Rooms</h2>

        @if($rooms->count())
            @foreach($rooms as $room)
                <div class="sds-card mb-4">
                    <div class="sds-card-header rounded-top">
                        <h5 class="mb-0">Room Name: {{ $room->name ?? 'N/A' }}</h5>
                    </div>

                    <div class="sds-card-body">
                        @php($meta = $room->metadata)
                        @php($isBlocked = optional($meta)->is_blocked)

                        <p>Status:
                            @if($isBlocked)
                                <span class="text-danger fw-bold">Blocked</span>
                            @else
                                <span class="text-success fw-bold">Available For Use</span>
                            @endif
                        </p>

                        {{-- Room metadata and furniture side-by-side --}}
                        <div class="row mb-3">
                            <div class="col-12 col-md-6 mb-3 mb-md-0">
                                <div class="p-3 border rounded h-100">
                                    <h6 class="mb-2 text-primary">Room Details</h6>
                                    <p class="mb-1"><strong>Capacity:</strong> {{ optional($meta)->capacity ?? 'N/A' }}</p>
                                    <p class="mb-1"><strong>Type:</strong> {{ optional($meta)->type ?? 'N/A' }}</p>
                                    <p class="mb-1"><strong>Location:</strong> {{ optional($meta)->location ?? 'N/A' }}</p>

                                    @if(optional($meta)->description)
                                        <p class="mb-1"><strong>Description:</strong> {{ $meta->description }}</p>
                                    @endif

                                    @if($isBlocked && optional($meta)->blocked_reason)
                                        <p class="mb-1 text-danger"><strong>Blocked Reason:</strong> {{ $meta->blocked_reason }}</p>
                                    @elseif($isBlocked)
                                        <p class="mb-1 text-danger"><strong>Blocked Reason:</strong> Not specified</p>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="p-3 border rounded h-100">
                                    <h6 class="mb-2 text-primary">Available Furniture</h6>

                                    @if($room->furniture && $room->furniture->count() > 0)
                                        <div class="overflow-auto" style="max-height:220px;">
                                            <ul class="list-unstyled mb-0">
                                                @foreach($room->furniture as $furniture)
                                                    <li class="mb-2">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div>
                                                                <strong>{{ $furniture->furniture_name }}</strong>
                                                                @if($furniture->description)
                                                                    <div class="text-muted small">{{ $furniture->description }}</div>
                                                                @endif
                                                            </div>
                                                            <span class="badge bg-secondary ms-2">{{ $furniture->quantity }}</span>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <p class="text-muted fst-italic mb-0">No furniture information available</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Booking info --}}
                        @php($rv = $roomView[$room->id] ?? null)
                        @if($rv && $rv['has_current'])
                            <div class="alert alert-warning py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Currently booked</strong> by {{ $rv['booker_name'] }}
                                        from {{ $rv['start_time'] }}
                                        to {{ $rv['end_time'] }}
                                    </div>
                                    <small class="text-muted">as of {{ $rv['as_of'] }}</small>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-success py-2">
                                <strong>No ongoing booking right now:</strong>
                                <small class="text-muted fst-italic">
                                    as of {{ $rv['as_of'] ?? $now->format('Y-m-d H:i') }}
                                </small>
                            </div>
                        @endif

                        {{-- Next booking --}}
                        @if($rv && ($rv['has_next'] ?? false))
                            <div class="alert alert-info py-2">
                                <strong>Next booking</strong>:
                                {{ $rv['next']['date'] }}
                                from {{ $rv['next']['start_time'] }}
                                to {{ $rv['next']['end_time'] }}
                                by {{ $rv['next']['booker_name'] }}
                            </div>
                        @endif

                        {{-- Booking button --}}
                        @if($isBlocked)
                            <p class="text-danger">This room is blocked and cannot be booked at the moment.</p>
                            <a href="#" class="btn btn-secondary disabled" aria-disabled="true">Book</a>
                        @else
                            <a href="{{ route('rooms.select', ['id' => $room->id]) }}" class="btn btn-primary">Book</a>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-info">No rooms available.</div>
        @endif
    </div>
@endsection