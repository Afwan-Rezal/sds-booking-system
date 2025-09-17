@extends('layouts.app')

@section('content')
    <div class="jumbotron text-center">
        <h1 class="display-4">Welcome to SDS Booking System</h1>
        <p class="lead">This is a simple booking system built with Laravel and Bootstrap.</p>
        <hr class="my-4">
        <p>Use the navigation bar to explore the application.</p>
        <a class="btn btn-primary btn-lg" href="{{ url('/bookings') }}" role="button">View Bookings</a>
    </div>
@endsection