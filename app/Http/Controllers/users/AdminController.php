<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;

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

    public function showPendingBookings()
    {
        $bookings = Booking::with(['user.profile', 'room'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.pending_bookings', compact('bookings'));
    }

    public function approveBooking(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        
        if ($booking->status !== 'pending') {
            return back()->withErrors(['error' => 'Booking is not pending approval.']);
        }

        // Check if room is still available (in case another booking was approved in the meantime)
        $conflictingBooking = Booking::where('room_id', $booking->room_id)
            ->where('date', $booking->date)
            ->where('start_time', $booking->start_time)
            ->where('end_time', $booking->end_time)
            ->where('status', 'approved')
            ->where('id', '!=', $booking->id)
            ->first();

        if ($conflictingBooking) {
            return back()->withErrors(['error' => 'Cannot approve: Room is already booked for this date and time slot.']);
        }

        $booking->update(['status' => 'approved']);
        
        return redirect()->route('admin.pending_bookings')->with('success', 'Booking approved successfully!');
    }

    public function rejectBooking(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        
        if ($booking->status !== 'pending') {
            return back()->withErrors(['error' => 'Booking is not pending approval.']);
        }

        $booking->update(['status' => 'rejected']);
        
        return redirect()->route('admin.pending_bookings')->with('success', 'Booking rejected.');
    }
}

