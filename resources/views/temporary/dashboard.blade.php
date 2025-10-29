@extends('layouts.app')
@section('title', 'Pending Approval')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">Pending Staff Approval</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Your registration is pending admin approval.</strong>
                        <p class="mb-0 mt-2">You have registered as a staff member. Your account requires admin approval before you can access staff features.</p>
                    </div>
                    
                    <div class="mt-4">
                        <h5>Account Information</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Name:</th>
                                <td>{{ Auth::user()->profile->full_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ Auth::user()->email }}</td>
                            </tr>
                            <tr>
                                <th>Username:</th>
                                <td>{{ Auth::user()->username }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td><span class="badge bg-warning text-dark">Pending Approval</span></td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        <p class="text-muted">
                            <small>Please wait for an administrator to review and approve your staff registration request.</small>
                        </p>
                    </div>
                    
                    <div class="mt-3">
                        <a href="{{ route('bookings.list') }}" class="btn btn-primary me-2">View My Bookings</a>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-secondary">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

