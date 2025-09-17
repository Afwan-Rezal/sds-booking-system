<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'full_name',
        'role',
        'gender',
        // add other profile fields here
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
