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
        return view('forms.auth');
    }

    public function register(RegisterRequest $request) {
        $user = new User();

        $user->username = $request['username'];
        $user->email = $request['email'];
        $user->password = Hash::make($request['password']);
        $user->save();

        // Create user profile
        $role = $request['user_role'] === 'staff' ? 'temporary-access' : 'student';
        
        $user->profile()->create([
            'full_name' => $request['full_name'],
            'role' => $role,
            'gender' => $request['gender'],
        ]);

        // Log the user in
        Auth::login($user);

        return redirect()->intended('/')->with('success', 'Registration successful!');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/')->with('success', 'Login successful!');
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
