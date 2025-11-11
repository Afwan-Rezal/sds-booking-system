@extends('layouts.app')
@section('title', 'Manage Furniture - Admin')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Manage Furniture - {{ $room->name }}</h2>
                <a href="{{ route('admin.rooms.furniture.create', $room) }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Furniture
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Room Information</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-3">Room Name</dt>
                        <dd class="col-sm-9">{{ $room->name }}</dd>

                        <dt class="col-sm-3">Capacity</dt>
                        <dd class="col-sm-9">{{ $room->metadata->capacity ?? 'N/A' }}</dd>

                        <dt class="col-sm-3">Type</dt>
                        <dd class="col-sm-9">{{ $room->metadata->type ?? 'N/A' }}</dd>

                        <dt class="col-sm-3">Location</dt>
                        <dd class="col-sm-9">{{ $room->metadata->location ?? 'N/A' }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Furniture List</h5>
                </div>
                <div class="card-body">
                    @if($room->furniture && $room->furniture->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Furniture Name</th>
                                        <th>Quantity</th>
                                        <th>Description</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($room->furniture as $furniture)
                                        <tr>
                                            <td><strong>{{ $furniture->furniture_name }}</strong></td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $furniture->quantity }}</span>
                                            </td>
                                            <td>
                                                @if($furniture->description)
                                                    {{ $furniture->description }}
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex gap-2 justify-content-end">
                                                    <a href="{{ route('admin.rooms.furniture.edit', [$room, $furniture->id]) }}" class="btn btn-sm btn-outline-primary">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('admin.rooms.furniture.destroy', [$room, $furniture->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this furniture item?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <p class="mb-0">No furniture items found for this room. <a href="{{ route('admin.rooms.furniture.create', $room) }}">Add furniture</a> to get started.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('admin.rooms') }}" class="btn btn-secondary">Back to Rooms</a>
            </div>
        </div>
    </div>
</div>
@endsection

