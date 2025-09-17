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

                    @if($room->is_available == false)
                        <p class="text-danger">This room is currently under maintenance and cannot be booked.</p>
                        <a href="{{ route('rooms.book', ['id' => $room->id]) }}" class="btn btn-secondary" style="pointer-events: none; opacity: 0.5;">
                            Book
                        </a>
                    @else
                        <a href="{{ route('rooms.book', ['id' => $room->id]) }}" class="btn btn-primary">
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