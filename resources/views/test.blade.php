{{-- filepath: c:\xampp\htdocs\SDS-BookingSystem\resources\views\test.blade.php --}}
@extends('layouts.app')

@section('title', 'Test Page')

@section('content')
    <div class="container mt-4">
        <h1>Test Page</h1>
        <p>This is a simple test page.</p>

        @if(session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        @if(isset($data))
            <div class="card mt-3">
                <div class="card-body">
                    <h5>Data Passed to View:</h5>
                    <pre>{{ print_r($data, true) }}</pre>
                </div>
            </div>
        @else
            <p>No data has been passed to this view yet.</p>
        @endif
    </div>

    @auth
        You are logged in as {{ Auth::user()->username }}.
    @endauth
@endsection