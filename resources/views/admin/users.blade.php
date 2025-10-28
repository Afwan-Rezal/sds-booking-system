@extends('layouts.app')
@section('title', 'Manage Users - Admin')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Manage Users</h2>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">All Users</h5>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Gender</th>
                                        <th>Registered</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->profile->full_name ?? 'N/A' }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->username }}</td>
                                            <td>
                                                @if($user->profile)
                                                    @php
                                                        $roleClass = match($user->profile->role) {
                                                            'admin' => 'bg-danger',
                                                            'staff' => 'bg-warning text-dark',
                                                            'student' => 'bg-primary',
                                                            'temporary-access' => 'bg-secondary',
                                                            default => 'bg-secondary'
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $roleClass }}">{{ ucfirst(str_replace('-', ' ', $user->profile->role)) }}</span>
                                                @else
                                                    <span class="badge bg-secondary">No Profile</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->profile->gender ?? 'N/A' }}</td>
                                            <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No users found.</p>
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

