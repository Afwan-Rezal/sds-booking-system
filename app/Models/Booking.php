<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Room;
use App\Models\User;

class Booking extends Model
{
    // Table name (optional if you follow Laravel's naming convention)
    // protected $table = 'bookings';

    protected $fillable = [
        'room_id',
        'user_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'number_of_people',
    ];

    public $timestamps = true;

    // Relationships
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
