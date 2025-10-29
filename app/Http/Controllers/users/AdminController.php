<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function showUsers()
    {
        $users = User::with('profile')->get();
        return view('admin.users', compact('users'));
    }

    public function showPendingStaff()
    {
        $users = User::with('profile')
            ->whereHas('profile', function($query) {
                $query->where('role', 'temporary-access');
            })
            ->get();
        
        return view('admin.pending_staff', compact('users'));
    }

    public function approveStaff(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        
        if ($user->profile && $user->profile->role === 'temporary-access') {
            $user->profile->update(['role' => 'staff']);
            return redirect()->route('admin.pending_staff')->with('success', 'Staff approved successfully!');
        }

        return back()->withErrors(['error' => 'User is not pending approval.']);
    }

    public function rejectStaff(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        
        if ($user->profile && $user->profile->role === 'temporary-access') {
            $user->profile->update(['role' => 'student']);
            return redirect()->route('admin.pending_staff')->with('success', 'Staff request rejected.');
        }

        return back()->withErrors(['error' => 'User is not pending approval.']);
    }
}

