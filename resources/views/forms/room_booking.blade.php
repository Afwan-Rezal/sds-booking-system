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
            <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}" required>
            @error('date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="time_slot" class="form-label">Time Slot</label>
            <select name="time_slot" id="time_slot" class="form-select @error('time_slot') is-invalid @enderror" required>
                <option value="">Select a time slot</option>
                <option value="07:50:00-09:40:00" {{ old('time_slot') == '07:50:00-09:40:00' ? 'selected' : '' }}>07:50 - 09:40</option>
                <option value="09:50:00-11:40:00" {{ old('time_slot') == '09:50:00-11:40:00' ? 'selected' : '' }}>09:50 - 11:40</option>
                <option value="11:50:00-13:40:00" {{ old('time_slot') == '11:50:00-13:40:00' ? 'selected' : '' }}>11:50 - 13:40</option>
                <option value="14:10:00-16:00:00" {{ old('time_slot') == '14:10:00-16:00:00' ? 'selected' : '' }}>14:10 - 16:00</option>
            </select>
            @error('time_slot')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            @error('booking_limit')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="number_of_people" class="form-label">Number of People</label>
            <input type="number" name="number_of_people" id="number_of_people" class="form-control" min="1" value="{{ old('number_of_people') }}" required>
        </div>

        <div class="mb-3">
            <label for="purpose" class="form-label">Reason for Request</label>
            <textarea name="purpose" id="purpose" class="form-control" rows="4" required>{{ old('purpose') }}</textarea>
            <small class="form-text text-muted">Please provide details about why you need to book this room.</small>
        </div>

        <button type="submit" class="btn btn-primary">Submit Request</button>
    </form>
</div>
@endsection