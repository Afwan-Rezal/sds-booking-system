<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomFurniture extends Model
{
    protected $table = 'room_furniture';

    // Mass assignable attributes
    protected $fillable = [
        'room_id',
        'furniture_name',
        'quantity',
        'description',
    ];

    // Relationship: Furniture belongs to a room
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
