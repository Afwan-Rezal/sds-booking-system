<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Models\Booking;
use App\Models\Room;

use App\Mail\BookingApprovedMail;
use Throwable;
use Carbon\Carbon;

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

    public function showRooms()
    {
        $rooms = Room::with('metadata')->orderBy('name')->get();

        return view('admin.rooms', compact('rooms'));
    }

    public function showBlockForm(Room $room)
    {
        $room->load('metadata');

        if (! $room->metadata) {
            return redirect()->route('admin.rooms')->withErrors([
                'room' => 'Room metadata is missing. Please contact a system administrator.',
            ]);
        }

        if ($room->metadata->is_blocked) {
            return redirect()->route('admin.rooms')->withErrors([
                'room' => 'Room is already blocked.',
            ]);
        }

        return view('admin.block_room', compact('room'));
    }

    public function blockRoom(Request $request, Room $room)
    {
        $room->load('metadata');

        if (! $room->metadata) {
            return redirect()->route('admin.rooms')->withErrors([
                'room' => 'Room metadata is missing. Please contact a system administrator.',
            ]);
        }

        if ($room->metadata->is_blocked) {
            return redirect()->route('admin.rooms')->withErrors([
                'room' => 'Room is already blocked.',
            ]);
        }

        $validated = $request->validate([
            'blocked_reason' => ['required', 'string', 'min:5', 'max:500'],
        ]);

        $room->metadata->update([
            'is_blocked' => true,
            'blocked_reason' => $validated['blocked_reason'],
        ]);

        return redirect()->route('admin.rooms')->with('success', 'Room has been blocked successfully.');
    }

    public function unblockRoom(Request $request, Room $room)
    {
        $room->load('metadata');

        if (! $room->metadata) {
            return redirect()->route('admin.rooms')->withErrors([
                'room' => 'Room metadata is missing. Please contact a system administrator.',
            ]);
        }

        if (! $room->metadata->is_blocked) {
            return redirect()->route('admin.rooms')->withErrors([
                'room' => 'Room is not currently blocked.',
            ]);
        }

        $room->metadata->update([
            'is_blocked' => false,
            'blocked_reason' => null,
        ]);

        return redirect()->route('admin.rooms')->with('success', 'Room has been unblocked successfully.');
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

        // Send approval email
        $mailData =[
            "name" => $booking->user->profile['full_name'],
            "date" => $booking->date,
            "time_slot" => Carbon::parse($booking->start_time)->format('H:i') . ' - ' . Carbon::parse($booking->end_time)->format('H:i'),
            "room" => $booking->room->name,
        ];

        try{
            Mail::to($booking->user->email)->send(new BookingApprovedMail($mailData));
        } catch (Throwable $e) {
            return back()->withErrors(['error' => 'Failed to send approval email: ' . $e->getMessage()]);

        }
        
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

