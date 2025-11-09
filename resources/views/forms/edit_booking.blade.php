@extends('layouts.app')

@section('title', 'Edit Booking')

@section('content')
<div class="container mt-4">
    <h2>Edit Your Booking</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('bookings.update', $booking->id) }}">
        @csrf
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ old('date', $booking->date) }}" required>
        </div>
        <div class="mb-3">
            <label for="time_slot" class="form-label">Time Slot</label>
            <select name="time_slot" id="time_slot" class="form-select" required>
                <option value="">Select a time slot</option>
                <option value="07:50:00-09:40:00" {{ old('time_slot', $booking->time_slot) == '07:50:00-09:40:00' ? 'selected' : '' }}>07:50 - 09:40</option>
                <option value="09:50:00-11:40:00" {{ old('time_slot', $booking->time_slot) == '09:50:00-11:40:00' ? 'selected' : '' }}>09:50 - 11:40</option>
                <option value="11:50:00-13:40:00" {{ old('time_slot', $booking->time_slot) == '11:50:00-13:40:00' ? 'selected' : '' }}>11:50 - 13:40</option>
                <option value="14:10:00-16:00:00" {{ old('time_slot', $booking->time_slot) == '14:10:00-16:00:00' ? 'selected' : '' }}>14:10 - 16:00</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="number_of_people" class="form-label">Number of People</label>
            <input type="number" name="number_of_people" id="number_of_people" class="form-control" min="1" value="{{ old('number_of_people', $booking->number_of_people) }}" required>
        </div>
        <div class="mb-3">
            <label for="purpose" class="form-label">Reason for Request</label>
            <textarea name="purpose" id="purpose" class="form-control" rows="4" required>{{ old('purpose', $booking->purpose) }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
    <form method="POST" action="{{ route('bookings.delete', $booking->id) }}" class="mt-2">
        @csrf
        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this booking?');">Cancel Booking</button>
    </form>
    <div class="mt-3">
        <a href="{{ route('bookings.list') }}" class="btn btn-secondary">Back to My Bookings</a>
    </div>
</div>
@endsection
