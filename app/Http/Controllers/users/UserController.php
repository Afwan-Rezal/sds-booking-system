<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UserController extends Controller
{
    public function index() {
        return view('auth');
    }

    public function register(RegisterRequest $request) {
        $user = new User();

        $user->username = $request['username'];
        $user->email = $request['email'];
        $user->password = Hash::make($request['password']);
        $user->save();

        // Log the user in
        Auth::login($user);

        // Pass data to the test view (optional)
        $data = [
            'username' => $user->username,
            'email' => $user->email,
            // Add more fields if needed
        ];

        return view('profile', compact('data'))->with('message', 'Registration successful!');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $data = [
                'username' => $user->username,
                'email' => $user->email,
                // Add more fields if needed
            ];

            // return view('test', compact('data'))->with('message', 'Login successful!');
            return view('profile', compact('data'))->with('message', 'Login successful!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
        // return view('test')->with('message', 'Logged out successfully.');
    }
}
