<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UserController extends Controller
{
    public function index() {
        return view('auth');
    }

    public function register(Request $request) {
        // Registration function here
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $user = User::create([
            'username' => $request['username'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        return view('home');
    }

    public function login(Request $request) {
        // Login function here
        // TODO: `Run php artisan make:request LoginRequest` to create a request class for login validation
        $loginRequest = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // if (auth()->attempt($loginRequest)) {
        //     $request->session()->regenerate();
        //     return view('home');
        // }

        return view('home');
    }
}
