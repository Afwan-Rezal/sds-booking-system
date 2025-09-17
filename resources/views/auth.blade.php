@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h2 class="mb-4 text-center">Login</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="login-email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="login-email" name="email" required autofocus>
            </div>
            <div class="mb-3">
                <label for="login-password" class="form-label">Password</label>
                <input type="password" class="form-control" id="login-password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>

    <div class="col-md-6">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <h2 class="mb-4 text-center">Register</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
                <label for="register-name" class="form-label">Username</label>
                <input type="text" class="form-control" id="register-username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="register-email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="register-email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="register-password" class="form-label">Password</label>
                <input type="password" class="form-control" id="register-password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="register-password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="register-password_confirmation" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Register</button>
        </form>
    </div>
</div>
@endsection