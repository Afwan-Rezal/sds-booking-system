{{-- TODO: Enhance page after submission--}}

@extends('layouts.app')

@section('title', $errors->has('username') || $errors->has('full_name') || $errors->has('gender') || $errors->has('user_role') ? 'Register' : 'Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <!-- Toggle Buttons -->
        <ul class="nav nav-pills nav-justified mb-4" id="authTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab" aria-controls="login" aria-selected="true">Login</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab" aria-controls="register" aria-selected="false">Register</button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="authTabContent">
            <!-- Login Form -->
            <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                <div class="card">
                    <div class="card-body">
                        <h2 class="mb-4 text-center">Login</h2>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="login-email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="login-email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="login-password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="login-password" name="password" required>
                                @error('password')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Register Form -->
            <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
                <div class="card">
                    <div class="card-body">
                        <h2 class="mb-4 text-center">Register</h2>
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="register-username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="register-username" name="username" value="{{ old('username') }}" required>
                                @error('username')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="register-email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="register-email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="register-password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="register-password" name="password" required>
                                @error('password')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="register-password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="register-password_confirmation" name="password_confirmation" required>
                            </div>
                            
                            <hr class="my-4">
                            
                            <h5 class="mb-3">Profile Information</h5>
                            <div class="mb-3">
                                <label for="register-full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="register-full_name" name="full_name" value="{{ old('full_name') }}" required>
                                @error('full_name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="register-gender" class="form-label">Gender</label>
                                <select class="form-select" id="register-gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Male</option>
                                    <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('gender')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="register-role" class="form-label">I am a</label>
                                <select class="form-select" id="register-role" name="user_role" required>
                                    <option value="">Select Role</option>
                                    <option value="student" {{ old('user_role') == 'student' ? 'selected' : '' }}>Student</option>
                                    <option value="staff" {{ old('user_role') == 'staff' ? 'selected' : '' }}>Staff (Pending Admin Approval)</option>
                                </select>
                                @error('user_role')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-success w-100">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to update page title
    function updatePageTitle(tab) {
        var appName = '{{ config("app.name", "SDS Booking System") }}';
        if (tab === 'login') {
            document.title = 'Login | ' + appName;
        } else if (tab === 'register') {
            document.title = 'Register | ' + appName;
        }
    }
    
    // Auto-switch to register tab if there are validation errors on register form
    document.addEventListener('DOMContentLoaded', function() {
        var loginTab = document.getElementById('login-tab');
        var registerTab = document.getElementById('register-tab');
        var loginEmail = document.getElementById('login-email');
        
        // Check if there are register-specific errors (username, full_name, gender, user_role are unique to register)
        @if($errors->has('username') || $errors->has('full_name') || $errors->has('gender') || $errors->has('user_role'))
            var registerTabElement = new bootstrap.Tab(registerTab);
            registerTabElement.show();
            updatePageTitle('register');
            // Focus on first register field after tab is shown
            setTimeout(function() {
                document.getElementById('register-username').focus();
            }, 150);
        @else
            // Set initial title to Login
            updatePageTitle('login');
            // Focus on login email by default
            loginEmail.focus();
        @endif
        
        // Handle tab switching - focus on first field when tab is shown and update title
        loginTab.addEventListener('shown.bs.tab', function() {
            updatePageTitle('login');
            loginEmail.focus();
        });
        
        registerTab.addEventListener('shown.bs.tab', function() {
            updatePageTitle('register');
            document.getElementById('register-username').focus();
        });
    });
</script>
@endsection