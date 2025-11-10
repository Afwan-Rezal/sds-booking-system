@extends('layouts.app')
@section('title', 'Block Room - Admin')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-12">
            <h2 class="mb-4">Block Room</h2>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Room Details</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Room Name</dt>
                        <dd class="col-sm-8">{{ $room->name }}</dd>

                        <dt class="col-sm-4">Capacity</dt>
                        <dd class="col-sm-8">{{ $room->metadata->capacity ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Type</dt>
                        <dd class="col-sm-8">{{ $room->metadata->type ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Location</dt>
                        <dd class="col-sm-8">{{ $room->metadata->location ?? 'N/A' }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-warning">
                    <h5 class="mb-0 text-dark">Provide Blocking Reason</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.rooms.block.store', $room) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="blocked_reason" class="form-label">Reason for Blocking *</label>
                            <textarea
                                name="blocked_reason"
                                id="blocked_reason"
                                rows="4"
                                class="form-control @error('blocked_reason') is-invalid @enderror"
                                placeholder="Describe why this room needs to be blocked."
                                required>{{ old('blocked_reason') }}</textarea>
                            @error('blocked_reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.rooms') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-warning">Confirm Block</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('admin.rooms') }}" class="btn btn-link">Back to Rooms</a>
            </div>
        </div>
    </div>
</div>
@endsection

