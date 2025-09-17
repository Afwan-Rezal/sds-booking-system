<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomMetadata extends Model
{
    // Mass assignable attributes based on your room_metadatas table columns
    protected $fillable = [
        'capacity',
        'type',
        'location',
        'description',
        // Add other columns if present
    ];

    // If you have timestamps in your migration, leave this as true (default)
    public $timestamps = false; // Set to true if you use $table->timestamps()

    // Relationship: One metadata can have many rooms
    public function rooms()
    {
        return $this->hasMany(Room::class, 'room_metadata_id');
    }
}
