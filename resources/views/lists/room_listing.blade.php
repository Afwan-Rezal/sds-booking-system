@extends('layouts.app')
@section('title', 'Room Listing')

@section('content')
    <div class="container mt-4">
        <h2>Available Rooms</h2>
        @if($rooms->count())
            @foreach($rooms as $room)
                <div class="container mb-3 p-3 border rounded">
                    <h5>Room Name: {{ $room->name ?? 'N/A' }}</h5>
                    <p>Status: {{ $room->is_available ? 'Available For Use' : 'Under Maintenance' }}</p>

                    {{-- Room metadata details --}}
                    @php($meta = $room->metadata)
                    <div class="mb-2">
                        <p class="mb-1"><strong>Capacity:</strong> {{ optional($meta)->capacity ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Type:</strong> {{ optional($meta)->type ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Location:</strong> {{ optional($meta)->location ?? 'N/A' }}</p>
                        @if(optional($meta)->description)
                            <p class="mb-1"><strong>Description:</strong> {{ $meta->description }}</p>
                        @endif
                    </div>

                    {{-- Current booking info for this room (today) --}}
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
                            <small class="text-muted fst-italic">as of {{ $rv['as_of'] ?? $now->format('Y-m-d H:i') }}</small>
                        </div>
                    @endif

                    {{-- Next booking info (today or future) --}}
                    @if($rv && ($rv['has_next'] ?? false))
                        <div class="alert alert-info py-2">
                            <div>
                                <strong>Next booking</strong>: {{ $rv['next']['date'] }} from {{ $rv['next']['start_time'] }} to {{ $rv['next']['end_time'] }}
                                by {{ $rv['next']['booker_name'] }}
                            </div>
                        </div>
                    @endif

                    @if($room->is_available == false)
                        <p class="text-danger">This room is currently under maintenance and cannot be booked.</p>
                        <a href="{{ route('rooms.select', ['id' => $room->id]) }}" class="btn btn-secondary" style="pointer-events: none; opacity: 0.5;">
                            Book
                        </a>
                    @else
                        <a href="{{ route('rooms.select', ['id' => $room->id]) }}" class="btn btn-primary">
                            Book
                        </a>
                    @endif
                    
                </div>
            @endforeach
        @else
            <div class="alert alert-info">No rooms available.</div>
        @endif
    </div>
@endsection