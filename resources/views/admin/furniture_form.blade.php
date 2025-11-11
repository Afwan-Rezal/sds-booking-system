@extends('layouts.app')
@section('title', isset($furniture) ? 'Edit Furniture - Admin' : 'Add Furniture - Admin')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-12">
            <h2 class="mb-4">{{ isset($furniture) ? 'Edit Furniture' : 'Add Furniture' }} - {{ $room->name }}</h2>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Room Information</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Room Name</dt>
                        <dd class="col-sm-8">{{ $room->name }}</dd>

                        <dt class="col-sm-4">Capacity</dt>
                        <dd class="col-sm-8">{{ $room->metadata->capacity ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Type</dt>
                        <dd class="col-sm-8">{{ $room->metadata->type ?? 'N/A' }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ isset($furniture) ? 'Edit Furniture Details' : 'Furniture Details' }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ isset($furniture) ? route('admin.rooms.furniture.update', [$room, $furniture->id]) : route('admin.rooms.furniture.store', $room) }}" method="POST">
                        @csrf
                        @if(isset($furniture))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label for="furniture_name" class="form-label">Furniture Name *</label>
                            <input
                                type="text"
                                name="furniture_name"
                                id="furniture_name"
                                class="form-control @error('furniture_name') is-invalid @enderror"
                                value="{{ old('furniture_name', $furniture->furniture_name ?? '') }}"
                                placeholder="e.g., Projector, Chair, Desk, Whiteboard"
                                required
                            >
                            @error('furniture_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity *</label>
                            <input
                                type="number"
                                name="quantity"
                                id="quantity"
                                class="form-control @error('quantity') is-invalid @enderror"
                                value="{{ old('quantity', $furniture->quantity ?? '1') }}"
                                min="1"
                                required
                            >
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea
                                name="description"
                                id="description"
                                rows="3"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Optional: Add details about the furniture (e.g., condition, specifications)"
                            >{{ old('description', $furniture->description ?? '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.rooms.furniture', $room) }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                {{ isset($furniture) ? 'Update Furniture' : 'Add Furniture' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('admin.rooms.furniture', $room) }}" class="btn btn-link">Back to Furniture List</a>
            </div>
        </div>
    </div>
</div>
@endsection

