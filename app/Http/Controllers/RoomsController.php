<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Room; // Make sure to import your Room model

class RoomsController extends Controller
{
    public function index()
    {
        $rooms = Room::all(); // Fetch all rooms from the database
        return view('room_booking', compact('rooms')); // Pass to the view
    }
}
