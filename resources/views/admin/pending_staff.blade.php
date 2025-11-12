@extends('layouts.app')
@section('title', 'Pending Staff Approvals - Admin')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Pending Staff Approvals</h2>
            
            <div class="alert alert-info">
                <strong>Note:</strong> Users who registered as staff are shown here. Approve or reject their staff access requests.
            </div>
            
            <div class="card sds-card">
                <div class="card-header sds-card-header">
                    <h5 class="mb-0">Pending Approval Requests</h5>
                </div>
                <div class="card-body sds-card-body">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Username</th>
                                        <th>Gender</th>
                                        <th>Registered</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->profile->full_name ?? 'N/A' }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->username }}</td>
                                            <td>{{ $user->profile->gender ?? 'N/A' }}</td>
                                            <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('admin.approve_staff', $user->id) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.reject_staff', $user->id) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No pending staff approvals.</p>
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

