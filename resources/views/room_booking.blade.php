@extends('layouts.app')
@section('title', 'Room Booking Request')

@section('content')
<div class="container mt-4">
    <h2>Book a Room</h2>
    <form method="POST" action="{{ route('rooms.storeBooking', $selectedRoom->id)}}">
        @csrf

        <input type="hidden" name="room_id" value="{{ $selectedRoom->id }}">
        <p><strong>Room:</strong> {{ $selectedRoom->name }}</p>

        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ old('date') }}" required>
        </div>

        <div class="mb-3">
            <label for="time_slot" class="form-label">Time Slot</label>
            <select name="time_slot" id="time_slot" class="form-select" required>
                <option value="">Select a time slot</option>
                <option value="0750-0940" {{ old('time_slot') == '0750-0940' ? 'selected' : '' }}>07:50 - 09:40</option>
                <option value="0950-1140" {{ old('time_slot') == '0950-1140' ? 'selected' : '' }}>09:50 - 11:40</option>
                <option value="1150-1340" {{ old('time_slot') == '1150-1340' ? 'selected' : '' }}>11:50 - 13:40</option>
                <option value="1410-1600" {{ old('time_slot') == '1410-1600' ? 'selected' : '' }}>14:10 - 16:00</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="number_of_people" class="form-label">Number of People</label>
            <input type="number" name="number_of_people" id="number_of_people" class="form-control" min="1" value="{{ old('number_of_people') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Submit Request</button>
    </form>
</div>
@endsection