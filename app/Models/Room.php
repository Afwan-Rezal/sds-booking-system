<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RoomMetadata;

class Room extends Model
{
    // Specify the table if not following Laravel's naming convention
    protected $table = 'rooms';

    // Mass assignable attributes
    protected $fillable = [
        'name',
        'room_metadata_id', // if you have this column
    ];

    
    // Example relationship (uncomment if you have room_metadata_id)
    public function metadata()
    {
        return $this->belongsTo(RoomMetadata::class, 'room_metadata_id');
    }

    // Relationship: Room has many furniture items
    public function furniture()
    {
        return $this->hasMany(RoomFurniture::class, 'room_id');
    }
}
